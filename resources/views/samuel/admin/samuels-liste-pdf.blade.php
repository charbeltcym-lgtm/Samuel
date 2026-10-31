<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste des Samuels</title>
    <style>
        @page { margin: 15mm 12mm 18mm 12mm; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1F2937; font-size: 11px; margin: 0; }
        .header { width: 100%; border-collapse: collapse; border-bottom: 3px solid #D4A72C; margin-bottom: 16px; }
        .header td { border: none; padding: 0 0 10px; vertical-align: middle; }
        .brand { color: #172554; font-size: 16px; font-weight: bold; }
        .motto { color: #D4A72C; font-size: 8.5px; letter-spacing: 1.6px; text-transform: uppercase; margin-top: 2px; }
        h1 { color: #172554; font-size: 18px; margin: 0 0 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #172554; color: #FFFFFF; font-size: 8.5px; text-transform: uppercase; letter-spacing: .5px; padding: 7px 8px; text-align: left; }
        td { padding: 7px 8px; border-bottom: 1px solid #E5E7EB; font-size: 10.5px; }
        tr:nth-child(even) td { background: #F9FAFB; }
        .num { width: 26px; text-align: center; color: #9CA3AF; }
        .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: center; color: #9CA3AF; font-size: 8px; border-top: 1px solid #E5E7EB; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="footer">Samuel App © 2026 · Foi • Service • Engagement</div>
    <table class="header">
        <tr>
            <td>
                <div class="brand">SAMUEL APP</div>
                <div class="motto">Foi • Service • Engagement</div>
            </td>
            <td style="text-align:right;">
                <div style="color:#172554; font-weight:bold;">Liste des Samuels</div>
                <div style="color:#6B7280; font-size:9.5px;">{{ now()->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>
    <h1>Liste des Samuels ({{ $samuels->count() }})</h1>
    <table>
        <thead>
            <tr>
                <th class="num">N°</th>
                <th>Nom</th>
                <th>Prénoms</th>
            </tr>
        </thead>
        <tbody>
            @foreach($samuels as $i => $samuel)
                <tr>
                    <td class="num">{{ $i+1 }}</td>
                    <td style="font-weight:bold; color:#172554; text-transform:uppercase;">{{ $samuel->nom }}</td>
                    <td>{{ $samuel->prenoms }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
