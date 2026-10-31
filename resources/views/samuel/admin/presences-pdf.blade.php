<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste de présence - {{ $date->format('d/m/Y') }}</title>
    <style>
        @page { margin: 15mm 12mm 18mm 12mm; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1F2937; font-size: 11px; margin: 0; }

        .header { width: 100%; border-collapse: collapse; border-bottom: 3px solid #D4A72C; margin-bottom: 16px; }
        .header td { border: none; padding: 0 0 10px; vertical-align: middle; }
        .header .left { width: 60%; }
        .header .right { width: 40%; text-align: right; }
        .logo { width: 52px; height: 52px; border-radius: 10px; vertical-align: middle; }
        .brand { color: #172554; font-size: 16px; font-weight: bold; line-height: 1.2; }
        .motto { color: #D4A72C; font-size: 8.5px; letter-spacing: 1.6px; text-transform: uppercase; margin-top: 2px; }
        .doc-title { color: #172554; font-size: 14px; font-weight: bold; }
        .doc-date { color: #6B7280; font-size: 9.5px; margin-top: 2px; }

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
        .statut-cell { width: 90px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8.5px; font-weight: bold; }
        .b-present { background: #DCFCE7; color: #15803D; }
        .b-absent { background: #FEE2E2; color: #B91C1C; }
        .b-retard { background: #FEF3C7; color: #B45309; }
        .b-permission { background: #DBEAFE; color: #1D4ED8; }
        .b-vide { background: #F3F4F6; color: #6B7280; }

        .signatures { width: 100%; border-collapse: collapse; margin-top: 26px; }
        .signatures td { width: 50%; border: none; padding: 0; font-size: 9.5px; color: #6B7280; vertical-align: top; }
        .signatures .line { margin-top: 26px; border-top: 1px solid #9CA3AF; width: 70%; }
        .signatures .right { text-align: right; }
        .signatures .right .line { margin-left: 30%; }

        .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: center; color: #9CA3AF; font-size: 8px; border-top: 1px solid #E5E7EB; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} — Samuel App © 2026 · Foi • Service • Engagement
    </div>

    <table class="header">
        <tr>
            <td class="left">
                <table>
                    <tr>
                        @if ($logo)
                            <td style="width:60px; border:none; padding:0;"><img src="{{ $logo }}" class="logo" alt="Samuel"></td>
                        @endif
                        <td style="border:none; padding:0 0 0 10px;">
                            <div class="brand">SAMUEL APP</div>
                            <div class="motto">Foi • Service • Engagement</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="right">
                <div class="doc-title">Liste de présence</div>
                <div class="doc-date">Éditée le {{ now()->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <h1>Répétition du {{ ucfirst($date->translatedFormat('l j F Y')) }}</h1>
    <p class="sub">
        <strong>{{ $samuels->count() }}</strong> Samuel{{ $samuels->count() > 1 ? 's' : '' }} inscrit{{ $samuels->count() > 1 ? 's' : '' }}
        &nbsp;·&nbsp; <strong>{{ $presences->count() }}</strong> statut{{ $presences->count() > 1 ? 's' : '' }} renseigné{{ $presences->count() > 1 ? 's' : '' }}
    </p>

    @php
        $presents = $presences->where('statut', 'present')->count();
        $absents = $presences->where('statut', 'absent')->count();
        $retards = $presences->where('statut', 'retard')->count();
        $permissions = $presences->where('statut', 'permissionnaire')->count();
    @endphp

    <table class="summary">
        <tr>
            <td class="s-present"><span class="n">{{ $presents }}</span><span class="l">Présents</span></td>
            <td class="s-absent"><span class="n">{{ $absents }}</span><span class="l">Absents</span></td>
            <td class="s-retard"><span class="n">{{ $retards }}</span><span class="l">Retards</span></td>
            <td class="s-permission"><span class="n">{{ $permissions }}</span><span class="l">Permissions</span></td>
        </tr>
    </table>

    <table class="liste">
        <thead>
            <tr>
                <th class="num">N°</th>
                <th>Nom et prénoms</th>
                <th class="statut-cell">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($samuels as $index => $samuel)
                @php $statut = $presences[$samuel->id]->statut ?? null; @endphp
                <tr>
                    <td class="num">{{ $index + 1 }}</td>
                    <td class="name">{{ $samuel->nom_complet }}</td>
                    <td class="statut-cell">
                        @if ($statut === 'present') <span class="badge b-present">Présent</span>
                        @elseif ($statut === 'absent') <span class="badge b-absent">Absent</span>
                        @elseif ($statut === 'retard') <span class="badge b-retard">Retard</span>
                        @elseif ($statut === 'permissionnaire') <span class="badge b-permission">Permissionnaire</span>
                        @else <span class="badge b-vide">Non renseigné</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Le responsable
                <div class="line"></div>
            </td>
            <td class="right">
                Fait le {{ $date->format('d/m/Y') }}
                <div class="line"></div>
            </td>
        </tr>
    </table>
</body>
</html>
