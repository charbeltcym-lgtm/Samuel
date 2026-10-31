<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire la présence - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
    <style>
        .pres-table { width: 100%; border-collapse: collapse; }
        .pres-table th, .pres-table td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        .pres-table th { color: #172554; font-size: 13px; text-transform: uppercase; letter-spacing: .4px; }
        .pres-table td.statut { text-align: center; }
        .pres-table input[type="radio"] { width: 20px; height: 20px; accent-color: #172554; cursor: pointer; }
        .pres-table tr:hover { background: #F8FAFC; }
        .pres-toolbar { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; margin-bottom: 20px; }
        .pres-toolbar .form-group { margin-bottom: 0; flex: 1 1 260px; min-width: 220px; }
        .pres-toolbar .hint { display: block; color: #6B7280; font-size: 12.5px; margin-top: 5px; }
        .statut-legend { display: flex; gap: 14px; flex-wrap: wrap; font-size: 12.5px; color: #6B7280; margin-bottom: 14px; }
        .statut-legend span::before { content: "●"; margin-right: 5px; }
        .leg-present::before { color: #15803D !important; }
        .leg-absent::before { color: #DC2626 !important; }
        .leg-retard::before { color: #EA580C !important; }
        .leg-permission::before { color: #1D4ED8 !important; }
        .alert-error { background:#fee2e2; color:#DC2626; padding:12px 16px; border-radius:10px; margin-bottom:18px; font-size:14px; }
        .alert-success { background:#dcfce7; color:#15803D; padding:12px 16px; border-radius:10px; margin-bottom:18px; font-size:14px; }
        .row-name strong { color: #172554; }
        .row-name small { color: #6B7280; display: block; }
        .pres-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 10px; }
        .pres-head .compte { font-size: 14px; color: #6B7280; }
        .pres-head .compte small { display: block; font-size: 12.5px; margin-top: 3px; }
        .pres-actions { margin-top: 22px; display: flex; gap: 10px; flex-wrap: wrap; }
        .pres-table .col-name { min-width: 170px; }
        .pres-table .col-statut { width: 90px; text-align: center; }
        @media (max-width: 640px) {
            .pres-toolbar { flex-direction: column; align-items: stretch; }
            .pres-toolbar .form-group { flex: 1 1 auto; min-width: 0; }
            .pres-toolbar .btn { width: 100%; text-align: center; padding: 12px 16px; }
            .pres-head { flex-direction: column; align-items: stretch; }
            .pres-head .btn { width: 100%; text-align: center; }
            .pres-actions { flex-direction: column; }
            .pres-actions .btn { width: 100%; text-align: center; }
            .pres-table .col-statut { width: 56px; }
            .pres-table th, .pres-table td { padding: 9px 4px; }
            .pres-table th { font-size: 10px; letter-spacing: 0; }
            .pres-table input[type="radio"] { width: 22px; height: 22px; }
            .statut-legend { gap: 10px; font-size: 12px; }
        }
    </style>
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Faire la présence</div>
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
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $erreur)
                        <div>{{ $erreur }}</div>
                    @endforeach
                </div>
            @endif

            <div class="panel">
                <h3>Répétition du {{ ucfirst(\Carbon\Carbon::parse($dateIso)->translatedFormat('l j F Y')) }}</h3>

                <form method="GET" action="/nouvelle-seance" class="pres-toolbar">
                    <div class="form-group">
                        <label for="date">Choisir un samedi</label>
                        <input type="date" id="date" name="date" value="{{ $dateIso }}">
                        <small class="hint">
                            Les répétitions ont lieu le samedi. Une autre date est ramenée au samedi précédent.
                        </small>
                    </div>
                    <button type="submit" class="btn btn-navy" style="padding:11px 20px;">Afficher cette date</button>
                    <a href="/presences" class="btn btn-light" style="padding:11px 20px;">Historique</a>
                    <a href="{{ route('presences.pdf', ['date' => $dateIso]) }}" class="btn btn-gold" style="padding:11px 20px;">📄 Télécharger le PDF</a>
                </form>

                <div class="statut-legend">
                    <span class="leg-present">Présent</span>
                    <span class="leg-absent">Absent</span>
                    <span class="leg-retard">Retard</span>
                    <span class="leg-permission">Permissionnaire</span>
                </div>

                @if ($samuels->isEmpty())
                    <div class="empty-state">
                        <div class="icon">👥</div>
                        <p>Aucun Samuel enregistré</p>
                        <p style="font-size:13px;">Ajoutez des Samuels avant de faire la présence.</p>
                        <a href="/nouveau-samuel" class="btn btn-gold" style="margin-top:12px;">+ Nouveau Samuel</a>
                    </div>
                @else
                    <form method="POST" action="/nouvelle-seance">
                        @csrf
                        <input type="hidden" name="date_repetition" value="{{ $dateIso }}">

                        <div class="pres-head">
                            <div class="compte">
                                {{ $samuels->count() }} Samuel{{ $samuels->count() > 1 ? 's' : '' }} —
                                {{ \Carbon\Carbon::parse($dateIso)->format('d/m/Y') }}
                                <small>Les Samuels sans statut sélectionné ne seront pas enregistrés.</small>
                            </div>
                            <button type="button" class="btn btn-light" onclick="toutPresent()">Tout marquer présent</button>
                        </div>

                        <div class="table-wrap">
                            <table class="pres-table">
                                <thead>
                                    <tr>
                                        <th class="col-name">Samuel</th>
                                        <th class="col-statut">Présent</th>
                                        <th class="col-statut">Absent</th>
                                        <th class="col-statut">Retard</th>
                                        <th class="col-statut">Permission</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($samuels as $samuel)
                                        @php $actuel = $presences[$samuel->id]->statut ?? null; @endphp
                                        <tr>
                                            <td class="row-name">
                                                <strong>{{ $samuel->nom }}</strong>
                                                @if ($samuel->prenoms)<small>{{ $samuel->prenoms }}</small>@endif
                                            </td>
                                            @foreach (['present', 'absent', 'retard', 'permissionnaire'] as $code)
                                                <td class="statut">
                                                    <input type="radio"
                                                           name="statuts[{{ $samuel->id }}]"
                                                           value="{{ $code }}"
                                                           class="statut-radio"
                                                           @checked($actuel === $code)>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pres-actions">
                            <button type="submit" class="btn btn-gold" style="padding:12px 28px; font-size:15px;">Enregistrer la présence</button>
                            <a href="/presences" class="btn btn-light" style="padding:12px 20px;">Annuler</a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
        @include('samuel.dashboards.partials.footer')
    </div>

    <script>
        function toutPresent() {
            document.querySelectorAll('.statut-radio').forEach(function (radio) {
                if (radio.value === 'present') {
                    radio.checked = true;
                }
            });
        }
    </script>
</body>
</html>
