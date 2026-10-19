$base='https://samuel-1-bhvd.onrender.com'
for($i=1;$i -le 3;$i++){
  try{
    $s = New-Object Microsoft.PowerShell.Commands.WebRequestSession
    $r = Invoke-WebRequest -Uri "$base/login" -WebSession $s -UseBasicParsing -TimeoutSec 120
    $token = [regex]::Match($r.Content,'name="_token"\s+value="([^"]+)"').Groups[1].Value
    $p = Invoke-WebRequest -Uri "$base/login" -Method POST -WebSession $s -Body @{ _token=$token; email='charbeltcym@gmail.com'; password='12345678' } -UseBasicParsing -MaximumRedirection 5 -TimeoutSec 120
    "essai $i -> url finale: $($p.BaseResponse.ResponseUri) | dashboard: " + ($p.Content -match 'Tableau de bord|Dashboard|Charbel')
  } catch {
    $resp=$_.Exception.Response
    if($resp){ "essai $i -> status: " + [int]$resp.StatusCode } else { "essai $i -> ERR: $($_.Exception.Message)" }
  }
  Start-Sleep -Seconds 3
}
