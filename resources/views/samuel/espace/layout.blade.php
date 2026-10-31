<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Samuel App - Espace Administrateur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --navy:#172554; --navy-light:#1E3A8A; --gold:#D4A72C; --grey:#F3F4F6; }
        * { font-family:'Roboto', system-ui, -apple-system, BlinkMacSystemFont, sans-serif; }
        body { background: var(--grey); margin:0; }
        .app-bar { background: var(--navy); color:#fff; display:flex; align-items:center; justify-content:space-between; padding:12px 16px; position:sticky; top:0; z-index:10; }
        .brand { display:flex; align-items:center; gap:10px; }
        .brand-text { font-family:'Cinzel', serif; font-weight:700; letter-spacing:1px; }
        .content { padding:16px; display:flex; flex-direction:column; gap:16px; }
        .card { background:#fff; border-radius:18px; border:1px solid #e5e7eb; box-shadow:0 10px 30px rgba(23,37,84,.05); overflow:hidden; }
        .card-pad { padding:18px; }
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; border-radius:14px; font-weight:600; text-decoration:none; padding:12px 16px; }
        .btn-gold { background:var(--gold); color:var(--navy); }
        .btn-navy { background:var(--navy); color:#fff; }
        .btn-green { background:#15803D; color:#fff; }
        .btn-light { background:#f8fafc; color:var(--navy); border:1px solid #e2e8f0; }
        .grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
        .action { background:#fff; border:1px solid #e5e7eb; border-radius:18px; padding:14px 10px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:8px; text-decoration:none; color:var(--navy); font-weight:600; }
        .action-icon { width:52px; height:52px; border-radius:16px; display:flex; align-items:center; justify-content:center; }
        .list-item { display:flex; align-items:center; justify-content:space-between; padding:14px 18px; text-decoration:none; color:#1f2937; border-top:1px solid #f1f5f9; }
        .list-item:first-child { border-top:none; }
        .input { width:100%; border:1px solid #e5e7eb; border-radius:14px; padding:10px 14px; }
        .row { display:flex; gap:10px; flex-wrap:wrap; }
        .chip { display:inline-flex; align-items:center; padding:6px 12px; border-radius:999px; border:1px solid #e5e7eb; text-decoration:none; color:var(--navy); }
        .table-row { display:grid; grid-template-columns:1.2fr repeat(4,0.6fr); gap:8px; padding:12px 14px; align-items:center; border-top:1px solid #f1f5f9; }
        .table-row.header { background:var(--navy); color:#fff; font-weight:600; }
        .pill { display:flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:999px; border:1.6px solid #d1d5db; margin:0 auto; }
        .pill.active-present { background:#15803D; border-color:#15803D; color:#fff; }
        .pill.active-absent { background:#DC2626; border-color:#DC2626; color:#fff; }
        .pill.active-retard { background:#EA580C; border-color:#EA580C; color:#fff; }
        .pill.active-permissionnaire { background:#1D4ED8; border-color:#1D4ED8; color:#fff; }
        @media (max-width:420px){ .grid-3{grid-template-columns:1fr;} .table-row{grid-template-columns:1fr; gap:6px;} }
    </style>
</head>
<body>
    <header class="app-bar">
        <div class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Samuel" style="height:38px;">
            <div>
                <div class="brand-text">Samuel</div>
                <div style="font-size:11px; opacity:.8;">Foi • Service • Engagement</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button style="background:transparent; border:none; color:#fff; display:flex; align-items:center; gap:6px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Déconnexion
            </button>
        </form>
    </header>
    <main class="content">
        @if(session('success'))
            <div class="card card-pad" style="background:#dcfce7; color:#166534;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="card card-pad" style="background:#fee2e2; color:#991b1b;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
