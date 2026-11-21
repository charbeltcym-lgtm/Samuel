<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste de présence</title>
    <style>
        @page { margin: 12mm 10mm 16mm 10mm; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1F2937; font-size: 10px; margin: 0; }
        .header { width: 100%; border-collapse: collapse; border-bottom: 3px solid #D4A72C; margin-bottom: 12px; }
        .header td { border: none; padding: 0 0 8px; vertical-align: middle; }
        .logo { width: 48px; height: 48px; border-radius: 10px; vertical-align: middle; }
        .brand { color: #172554; font-size: 15px; font-weight: bold; }
        .motto { color: #D4A72C; font-size: 8px; letter-spacing: 1.4px; text-transform: uppercase; margin-top: 2px; }
        .doc-title { color: #172554; font-size: 13px; font-weight: bold; text-align: right; }
        .doc-date { color: #6B7280; font-size: 9px; margin-top: 2px; text-align: right; }
        h1 { color: #172554; font-size: 16px; margin: 0 0 2px; }
        .sub { color: #6B7280; font-size: 9.5px; margin: 0 0 10px; }
        .sub strong { color: #172554; }
        .legend { margin: 0 0 10px; font-size: 8.5px; color: #374151; }
        .legend span { display: inline-block; margin-right: 10px; }
        .dot { display: inline-block; width: 8px; height: 8px; border-radius: 2px; margin-right: 3px; vertical-align: middle; }
        table.liste { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.liste th { background: #172554; color: #FFFFFF; font-size: 7.5px; text-transform: uppercase; letter-spacing: .3px; padding: 6px 3px; text-align: center; }
        table.liste th.name-col, table.liste td.name-col { text-align: left; padding-left: 6px; }
        table.liste th.num-col, table.liste td.num-col { width: 24px; text-align: center; }
        table.liste td { padding: 5px 2px; border-bottom: 1px solid #E5E7EB; font-size: 9px; text-align: center; vertical-align: middle; }
        table.liste tr:nth-child(even) td { background: #F9FAFB; }
        .name-col { font-weight: bold; color: #172554; font-size: 9.5px; }
        .st { display: inline-block; padding: 2px 3px; border-radius: 6px; font-size: 7.5px; font-weight: bold; min-width: 22px; }
        .st-p { background: #DCFCE7; color: #15803D; }
        .st-a { background: #FEE2E2; color: #B91C1C; }
        .st-r { background: #FEF3C7; color: #B45309; }
        .st-pe { background: #DBEAFE; color: #1D4ED8; }
        .st-v { background: #F3F4F6; color: #9CA3AF; }
        .totals td { background: #EFF6FF !important; font-weight: bold; color: #172554; font-size: 8px; border-top: 2px solid #172554; }
        .signatures { width: 100%; border-collapse: collapse; margin-top: 24px; }
        .signatures td { width: 50%; border: none; padding: 0; font-size: 9.5px; color: #6B7280; vertical-align: top; }
        .signatures .line { margin-top: 26px; border-top: 1px solid #9CA3AF; width: 70%; }
        .signatures .right { text-align: right; }
        .signatures .right .line { margin-left: 30%; }
        .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: center; color: #9CA3AF; font-size: 8px; border-top: 1px solid #E5E7EB; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="footer">Document généré le {{ now()->format('d/m/Y à H:i') }} — Samuel App © 2026 · Foi • Service • Engagement</div>

    <table class="header">
        <tr>
            <td style="width:60%;">
                <table><tr>
                    @if ($logo)
                        <td style="width:56px; border:none; padding:0;"><img src="{{ $logo }}" class="logo" alt="Samuel"></td>
                    @endif
                    <td style="border:none; padding:0 0 0 8px;">
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
    @endphp
    <h1>Présences — {{ $periode }}</h1>
    <p class="sub"><strong>{{ $samuels->count() }}</strong> Samuel{{ $samuels->count() > 1 ? 's' : '' }} &nbsp;·&nbsp; <strong>{{ $dates->count() }}</strong> samedi{{ $dates->count() > 1 ? 's' : '' }} sélectionné{{ $dates->count() > 1 ? 's' : '' }}</p>

    <div class="legend">
        <span><span class="dot" style="background:#15803D;"></span>P = Présent</span>
        <span><span class="dot" style="background:#B91C1C;"></span>A = Absent</span>
        <span><span class="dot" style="background:#B45309;"></span>R = Retard</span>
        <span><span class="dot" style="background:#1D4ED8;"></span>Pe = Permissionnaire</span>
        <span><span class="dot" style="background:#9CA3AF;"></span>- = Non renseigné</span>
    </div>

    <table class="liste">
        <thead>
            <tr>
                <th class="num-col">N°</th>
                <th class="name-col" style="width:{{ max(120, 260 - ($dates->count() * 18)) }}px;">Nom et prénoms</th>
                @foreach ($dates as $d)
                    <th>{{ $d->format('d/m') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($samuels as $index => $samuel)
                <tr>
                    <td class="num-col" style="color:#9CA3AF;">{{ $index + 1 }}</td>
                    <td class="name-col">{{ $samuel->nom_complet }}</td>
                    @foreach ($dates as $d)
                        @php $statut = $presencesByDate[$d->toDateString()][$samuel->id]->statut ?? null; @endphp
                        <td>
                            @if ($statut === 'present') <span class="st st-p">P</span>
                            @elseif ($statut === 'absent') <span class="st st-a">A</span>
                            @elseif ($statut === 'retard') <span class="st st-r">R</span>
                            @elseif ($statut === 'permissionnaire') <span class="st st-pe">Pe</span>
                            @else <span class="st st-v">-</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            <tr class="totals">
                <td></td>
                <td class="name-col">Totaux (P/A/R/Pe)</td>
                @foreach ($dates as $d)
                    @php
                        $p = $presencesByDate[$d->toDateString()];
                        $tp = $p->where('statut','present')->count();
                        $ta = $p->where('statut','absent')->count();
                        $tr = $p->where('statut','retard')->count();
                        $tpe = $p->where('statut','permissionnaire')->count();
                    @endphp
                    <td>{{ $tp }}/{{ $ta }}/{{ $tr }}/{{ $tpe }}</td>
                @endforeach
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
