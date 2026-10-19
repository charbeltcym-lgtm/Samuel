<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'photo_data',
        'api_token',
        'role',
        'activation_code',
        'reset_code',
        'reset_code_expires_at',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'activation_code',
        'reset_code',
        'reset_code_expires_at',
        'photo_data',
    ];

    protected $appends = [
        'photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'activation_code' => 'string',
            'reset_code' => 'string',
            'reset_code_expires_at' => 'datetime',
            'status' => 'string',
        ];
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo_data) {
            $v = $this->updated_at ? $this->updated_at->timestamp : '0';
            return route('photo.show', ['user' => $this->id]) . '?v=' . $v;
        }

        if ($this->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }

        return null;
    }

    public static function encodePhoto(\Illuminate\Http\UploadedFile $file, int $max = 480): string
    {
        $binary = file_get_contents($file->getRealPath());
        $mime = $file->getMimeType() ?: 'image/jpeg';

        if ($binary !== false && function_exists('imagecreatefromstring')) {
            $src = @imagecreatefromstring($binary);
            if ($src !== false) {
                $w = imagesx($src);
                $h = imagesy($src);
                $scale = min(1, $max / max($w, $h));
                $nw = max(1, (int) round($w * $scale));
                $nh = max(1, (int) round($h * $scale));

                $dst = imagecreatetruecolor($nw, $nh);
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

                ob_start();
                imagepng($dst);
                $binary = ob_get_clean();

                imagedestroy($src);
                imagedestroy($dst);

                $mime = 'image/png';
            }
        }

        return 'data:' . $mime . ';base64,' . base64_encode($binary);
    }

    public function appNotifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Notification::class)->orderByDesc('created_at');
    }
}
