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
                            <button type="button" class="btn btn-green" style="padding:8px 14px;" onclick="sigDownloadMulti()">📄 Télécharger la sélection (PDF)</button>
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
                                                    <button type="button" class="btn btn-gold" style="padding:5px 10px;" onclick="sigSignSingle('{{ route('presences.signer', $jour->toDateString()) }}','{{ $jour->format('d/m/Y') }}')">Signer</button>
                                                @endif
                                                <button type="button" class="btn btn-green" style="padding:5px 10px;" onclick="sigDownloadSingle('{{ $jour->toDateString() }}','{{ $jour->format('d/m/Y') }}')">PDF</button>
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

    <div id="sigModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:1000; align-items:center; justify-content:center; padding:16px;">
        <div style="background:#fff; width:min(560px,100%); border-radius:14px; box-shadow:0 20px 50px rgba(0,0,0,.25); overflow:hidden;">
            <div style="padding:16px 18px; border-bottom:1px solid #E5E7EB; display:flex; justify-content:space-between; align-items:center; gap:10px;">
                <div>
                    <div style="font-weight:700; color:#172554; font-size:18px;">Signature du responsable</div>
                    <div id="sigModalSub" style="font-size:12px; color:#6B7280;">Signe dans le cadre ci-dessous avant de continuer.</div>
                </div>
                <button type="button" onclick="closeSigModal()" style="border:none; background:#F3F4F6; width:34px; height:34px; border-radius:8px; cursor:pointer; font-size:18px;">×</button>
            </div>
            <div style="padding:16px 18px;">
                <div style="border:1px dashed #94A3B8; border-radius:10px; background:#fff; touch-action:none;">
                    <canvas id="sigCanvas" width="500" height="180" style="width:100%; height:180px; display:block; cursor:crosshair;"></canvas>
                </div>
                <div style="display:flex; gap:8px; justify-content:space-between; margin-top:12px; flex-wrap:wrap;">
                    <button type="button" class="btn btn-light" onclick="clearSig()" style="padding:8px 14px;">Effacer</button>
                    <div style="display:flex; gap:8px;">
                        <button type="button" class="btn btn-light" onclick="closeSigModal()" style="padding:8px 14px;">Annuler</button>
                        <button type="button" class="btn btn-gold" onclick="confirmSig()" style="padding:8px 14px;">Confirmer la signature</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="sigSingleForm" method="POST" style="display:none;">@csrf <input type="hidden" name="signature" id="sigSingleInput"></form>
    <form id="sigPdfSingleForm" method="POST" action="{{ route('presences.pdf') }}" style="display:none;">@csrf <input type="hidden" name="date" id="sigPdfDate"><input type="hidden" name="signature" id="sigPdfSingleInput"></form>

    <script>
    (function(){
        const modal = document.getElementById('sigModal');
        const canvas = document.getElementById('sigCanvas');
        const ctx = canvas.getContext('2d');
        let drawing=false, hasInk=false, mode=null, modeData=null;
        function resizeCanvas(){
            const ratio = Math.max(window.devicePixelRatio||1,1);
            const rect = canvas.getBoundingClientRect();
            const saved = hasInk ? canvas.toDataURL() : null;
            canvas.width = Math.floor(rect.width * ratio);
            canvas.height = Math.floor(180 * ratio);
            ctx.setTransform(ratio,0,0,ratio,0,0);
            ctx.lineWidth = 2; ctx.lineCap='round'; ctx.strokeStyle='#172554';
            ctx.fillStyle='#fff'; ctx.fillRect(0,0,rect.width,180);
            if(saved){ const img=new Image(); img.onload=()=>ctx.drawImage(img,0,0,rect.width,180); img.src=saved; }
        }
        function pos(e){ const r=canvas.getBoundingClientRect(); const t=e.touches?e.touches[0]:e; return {x:t.clientX-r.left, y:t.clientY-r.top}; }
        function start(e){ drawing=true; hasInk=true; const p=pos(e); ctx.beginPath(); ctx.moveTo(p.x,p.y); e.preventDefault(); }
        function move(e){ if(!drawing) return; const p=pos(e); ctx.lineTo(p.x,p.y); ctx.stroke(); e.preventDefault(); }
        function end(){ drawing=false; }
        canvas.addEventListener('mousedown',start); canvas.addEventListener('mousemove',move); window.addEventListener('mouseup',end);
        canvas.addEventListener('touchstart',start,{passive:false}); canvas.addEventListener('touchmove',move,{passive:false}); window.addEventListener('touchend',end);
        window.clearSig = function(){ const r=canvas.getBoundingClientRect(); ctx.fillStyle='#fff'; ctx.fillRect(0,0,r.width,180); hasInk=false; };
        window.openSigModal = function(m,data,sub){ mode=m; modeData=data; document.getElementById('sigModalSub').textContent=sub||'Signe dans le cadre ci-dessous avant de continuer.'; modal.style.display='flex'; setTimeout(()=>{resizeCanvas(); clearSig();},30); };
        window.closeSigModal = function(){ modal.style.display='none'; mode=null; modeData=null; };
        window.confirmSig = function(){
            if(!hasInk){ alert('Veuillez apposer votre signature avant de continuer.'); return; }
            const dataUrl = canvas.toDataURL('image/png');
            if(mode==='sign-single'){
                const f=document.getElementById('sigSingleForm'); f.action=modeData.action; document.getElementById('sigSingleInput').value=dataUrl; f.submit();
            } else if(mode==='pdf-single'){
                document.getElementById('sigPdfDate').value=modeData.date; document.getElementById('sigPdfSingleInput').value=dataUrl; document.getElementById('sigPdfSingleForm').submit();
            } else if(mode==='pdf-multi'){
                const f=document.getElementById('multiPdfForm'); let inp=document.getElementById('sigMultiInput');
                if(!inp){ inp=document.createElement('input'); inp.type='hidden'; inp.name='signature'; inp.id='sigMultiInput'; f.appendChild(inp); }
                inp.value=dataUrl; f.submit();
            }
        };
        window.sigDownloadMulti = function(){
            const checked=document.querySelectorAll('.sel-presence:checked');
            if(!checked.length){ alert('Sélectionne au moins une date.'); return; }
            openSigModal('pdf-multi',null,'Signe avant de télécharger les listes sélectionnées.');
        };
        window.sigDownloadSingle = function(date,label){ openSigModal('pdf-single',{date:date},'Signe avant de télécharger la liste du '+label+'.'); };
        window.sigSignSingle = function(action,label){ openSigModal('sign-single',{action:action},'Signe pour valider la liste du '+label+'.'); };
        window.addEventListener('resize',()=>{ if(modal.style.display==='flex') resizeCanvas(); });
    })();
    </script>
</body>
</html>

