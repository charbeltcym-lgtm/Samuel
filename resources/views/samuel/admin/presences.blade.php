<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Présences - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
    <style>
        .pres-row-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        @media (max-width: 600px) {
            .pres-row-actions { flex-direction: column; }
            .pres-row-actions .btn { width: 100%; text-align: center; }
            .pres-total { display: none; }
        }
    </style>
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Présences</div>
            <div class="topbar-right">
                @include('samuel.dashboards.partials.notification-bell')
                <div class="profile">
                    @include('samuel.dashboards.partials.avatar')
                    <div class="info"><div class="name">{{ auth()->user()->name }}</div><div class="role">Administrateur principal</div></div>
                </div>
            </div>
        </header>
        <div class="content">
            @if (session('success'))
                <div style="background:#dcfce7; color:#15803D; padding:14px 16px; border-radius:10px; margin-bottom:18px; font-size:14px;">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div style="background:#fee2e2; color:#DC2626; padding:14px 16px; border-radius:10px; margin-bottom:18px; font-size:14px;">
                    @foreach ($errors->all() as $erreur)
                        <div>{{ $erreur }}</div>
                    @endforeach
                </div>
            @endif
            <div class="panel">
                <div class="page-head">
                    <h3>Historique des présences</h3>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <a href="/nouvelle-seance?date={{ now()->copy()->previous(\Carbon\Carbon::SATURDAY)->toDateString() }}" class="btn btn-light">Samedi passé</a>
                        <a href="/nouvelle-seance" class="btn btn-gold">+ Faire la présence</a>
                    </div>
                </div>
                @if ($dates->isEmpty())
                    <div class="empty-state"><div class="icon">✅</div><p>Aucune présence enregistrée pour le moment</p><p style="font-size:13px;">Les répétitions ont lieu tous les samedis</p></div>
                @else
                    @if(auth()->user()->role === 'super_admin')
                        <form id="multiPdfForm" method="POST" action="{{ route('presences.pdf.multiple') }}" style="margin-bottom:12px; display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                            @csrf
                            <button type="submit" class="btn btn-green" style="padding:8px 14px;">📄 Télécharger la sélection (PDF)</button>
                            <button type="button" class="btn btn-light" style="padding:8px 14px;" onclick="document.querySelectorAll('.sel-presence').forEach(c=>c.checked=true)">Tout sélectionner</button>
                            <button type="button" class="btn btn-light" style="padding:8px 14px;" onclick="document.querySelectorAll('.sel-presence').forEach(c=>c.checked=false)">Vider</button>
                            <span style="font-size:12px; color:#6B7280;">Coche le/les samedi(s) voulu(s) puis télécharge le PDF pour l’imprimer et le signer à la main. Clique « Signer » après avoir signé.</span>
                        </form>
                    @endif
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    @if(auth()->user()->role === 'super_admin')
                                        <th style="width:44px; text-align:center;">Sel.</th>
                                    @endif
                                    <th>Date (samedi)</th>
                                    <th style="text-align:center;">Présents</th>
                                    <th style="text-align:center;">Absents</th>
                                    <th style="text-align:center;">Retards</th>
                                    <th style="text-align:center;">Permissions</th>
                                    <th style="text-align:center;" class="pres-total">Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dates as $ligne)
                                @php $jour = \Carbon\Carbon::parse($ligne->date_repetition); $dateStr = $jour->toDateString(); $signed = isset($signatures[$dateStr]); @endphp
                                <tr>
                                    @if(auth()->user()->role === 'super_admin')
                                        <td style="text-align:center;">
                                            <input type="checkbox" name="dates[]" value="{{ $dateStr }}" form="multiPdfForm" class="sel-presence" style="width:18px; height:18px;">
                                        </td>
                                    @endif
                                    <td>
                                        <strong>{{ $jour->format('d/m/Y') }}</strong>
                                        <div style="font-size:12px; color:#6B7280;">{{ $jour->translatedFormat('l') }}</div>
                                    </td>
                                    <td style="text-align:center;"><span class="badge badge-present">{{ (int) $ligne->presents }}</span></td>
                                    <td style="text-align:center;"><span class="badge badge-absent">{{ (int) $ligne->absents }}</span></td>
                                    <td style="text-align:center;"><span class="badge badge-retard">{{ (int) $ligne->retards }}</span></td>
                                    <td style="text-align:center;"><span class="badge badge-permission">{{ (int) $ligne->permissions }}</span></td>
                                    <td style="text-align:center;" class="pres-total"><strong>{{ (int) $ligne->total }}</strong></td>
                                    <td>
                                        <div class="pres-row-actions">
                                            @if(auth()->user()->role === 'super_admin')
                                                @if(isset($signatures[$jour->toDateString()]))
                                                    <span class="btn btn-light" style="padding:5px 10px; opacity:.7;">Signé</span>
                                                @else
                                                    <form method="POST" action="{{ route('presences.signer', $jour->toDateString()) }}" style="display:inline;">
                                                        @csrf
                                                        <button class="btn btn-gold" style="padding:5px 10px;">Signer</button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('presences.pdf', ['date' => $jour->toDateString()]) }}" class="btn btn-green" style="padding:5px 10px;">PDF</a>
                                            @else
                                                <span class="btn btn-light" style="padding:5px 10px; opacity:.7;">En attente</span>
                                            @endif
                                            <a href="/nouvelle-seance?date={{ $jour->toDateString() }}" class="btn btn-navy" style="padding:5px 10px;">Modifier</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        @include('samuel.dashboards.partials.footer')
    </div>
</body>
</html>

