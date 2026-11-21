<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste de présence consolidée</title>
    <style>
        @page { margin: 15mm 12mm 18mm 12mm; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1F2937; font-size: 11px; margin: 0; }
        .header { width: 100%; border-collapse: collapse; border-bottom: 3px solid #D4A72C; margin-bottom: 16px; }
        .header td { border: none; padding: 0 0 10px; vertical-align: middle; }
        .logo { width: 52px; height: 52px; border-radius: 10px; vertical-align: middle; }
        .brand { color: #172554; font-size: 16px; font-weight: bold; }
        .motto { color: #D4A72C; font-size: 8.5px; letter-spacing: 1.6px; text-transform: uppercase; margin-top: 2px; }
        .doc-title { color: #172554; font-size: 14px; font-weight: bold; text-align: right; }
        .doc-date { color: #6B7280; font-size: 9.5px; margin-top: 2px; text-align: right; }
        h1 { color: #172554; font-size: 19px; margin: 0 0 3px; }
        .sub { color: #6B7280; font-size: 10.5px; margin: 0 0 14px; }
        .sub strong { color: #172554; }
        .summary { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin: 0 -6px 14px; }
        .summary td { width: 25%; text-align: center; padding: 9px 4px; border: none; border-radius: 8px; }
        .summary .n { display: block; font-size: 17px; font-weight: bold; line-height: 1.1; }
        .summary .l { display: block; font-size: 8px; text-transform: uppercase; letter-spacing: .6px; margin-top: 2px; }
        .s-present { background: #DCFCE7; color: #15803D; }
        .s-absent { background: #FEE2E2; color: #B91C1C; }
        .s-retard { background: #FEF3C7; color: #B45309; }
        .s-permission { background: #DBEAFE; color: #1D4ED8; }
        table.liste { width: 100%; border-collapse: collapse; }
        table.liste th { background: #172554; color: #FFFFFF; font-size: 8.5px; text-transform: uppercase; letter-spacing: .5px; padding: 7px 8px; text-align: left; }
        table.liste td { padding: 7px 8px; border-bottom: 1px solid #E5E7EB; font-size: 10.5px; }
        table.liste tr:nth-child(even) td { background: #F9FAFB; }
        .num { width: 26px; text-align: center; color: #9CA3AF; }
        .name { font-weight: bold; color: #172554; }
        .c { text-align: center; width: 72px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; min-width: 24px; text-align: center; }
        .b-present { background: #DCFCE7; color: #15803D; }
        .b-absent { background: #FEE2E2; color: #B91C1C; }
        .b-retard { background: #FEF3C7; color: #B45309; }
        .b-permission { background: #DBEAFE; color: #1D4ED8; }
        .b-vide { background: #F3F4F6; color: #6B7280; }
        .totals td { background: #EFF6FF !important; font-weight: bold; color: #172554; border-top: 2px solid #172554; }
        .signatures { width: 100%; border-collapse: collapse; margin-top: 26px; }
        .signatures td { width: 50%; border: none; padding: 0; font-size: 9.5px; color: #6B7280; vertical-align: top; }
        .signatures .line { margin-top: 26px; border-top: 1px solid #9CA3AF; width: 70%; }
        .signatures .right { text-align: right; }
        .signatures .right .line { margin-left: 30%; }
        .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: center; color: #9CA3AF; font-size: 8px; border-top: 1px solid #E5E7EB; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="footer">Document généré le {{ now()->format('d/m/Y à H:i') }} — Samuel App © 2026 · Foi • Service • Engagement</div>

    <table class="header">
        <tr>
            <td style="width:60%;">
                <table><tr>
                    @if ($logo)
                        <td style="width:60px; border:none; padding:0;"><img src="{{ $logo }}" class="logo" alt="Samuel"></td>
                    @endif
                    <td style="border:none; padding:0 0 0 10px;">
                        <div class="brand">SAMUEL APP</div>
                        <div class="motto">Foi • Service • Engagement</div>
                    </td>
                </tr></table>
            </td>
            <td style="width:40%;">
                <div class="doc-title">Liste de présence consolidée</div>
                <div class="doc-date">Éditée le {{ now()->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    @php
        $first = $dates->first();
        $last = $dates->last();
        $periode = $first->isSameDay($last)
            ? ucfirst($first->translatedFormat('l j F Y'))
            : $first->format('d/m/Y') . ' au ' . $last->format('d/m/Y');
        $totalP = $rows->sum('presents');
        $totalA = $rows->sum('absents');
        $totalR = $rows->sum('retards');
        $totalPe = $rows->sum('permissions');
    @endphp

    <h1>Présences — {{ $periode }}</h1>
    <p class="sub">
        <strong>{{ $rows->count() }}</strong> Samuel{{ $rows->count() > 1 ? 's' : '' }}
        &nbsp;·&nbsp; <strong>{{ $dates->count() }}</strong> samedi{{ $dates->count() > 1 ? 's' : '' }} sélectionné{{ $dates->count() > 1 ? 's' : '' }}
    </p>

    <table class="summary">
        <tr>
            <td class="s-present"><span class="n">{{ $totalP }}</span><span class="l">Présents</span></td>
            <td class="s-absent"><span class="n">{{ $totalA }}</span><span class="l">Absents</span></td>
            <td class="s-retard"><span class="n">{{ $totalR }}</span><span class="l">Retards</span></td>
            <td class="s-permission"><span class="n">{{ $totalPe }}</span><span class="l">Permissions</span></td>
        </tr>
    </table>

    <table class="liste">
        <thead>
            <tr>
                <th class="num">N°</th>
                <th>Nom et prénoms</th>
                <th class="c">Présents</th>
                <th class="c">Absents</th>
                <th class="c">Retards</th>
                <th class="c">Permissions</th>
                <th class="c">N. R.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $index => $row)
                <tr>
                    <td class="num">{{ $index + 1 }}</td>
                    <td class="name">{{ $row['samuel']->nom_complet }}</td>
                    <td class="c"><span class="badge b-present">{{ $row['presents'] }}</span></td>
                    <td class="c"><span class="badge b-absent">{{ $row['absents'] }}</span></td>
                    <td class="c"><span class="badge b-retard">{{ $row['retards'] }}</span></td>
                    <td class="c"><span class="badge b-permission">{{ $row['permissions'] }}</span></td>
                    <td class="c"><span class="badge b-vide">{{ $row['non_renseignes'] }}</span></td>
                </tr>
            @endforeach
            <tr class="totals">
                <td></td>
                <td class="name">TOTAL</td>
                <td class="c">{{ $totalP }}</td>
                <td class="c">{{ $totalA }}</td>
                <td class="c">{{ $totalR }}</td>
                <td class="c">{{ $totalPe }}</td>
                <td class="c">{{ $rows->sum('non_renseignes') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Le responsable
                @if(!empty($signature))
                    <div style="margin-top:8px;"><img src="{{ $signature }}" style="height:55px; max-width:220px;"></div>
                @else
                    <div class="line"></div>
                @endif
                <div style="border-top:1px solid #9CA3AF; width:70%; margin-top:4px;"></div>
            </td>
            <td class="right">
                Fait le {{ now()->format('d/m/Y') }}
                <div class="line"></div>
            </td>
        </tr>
    </table>
</body>
</html>
