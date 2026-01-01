<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport AgriAlert</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        /* En-tête avec logo */
        .header {
            width: 100%;
            border-bottom: 2px solid #4A5D4E;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px; /* Ajustez la taille */
            float: left;
        }
        .company-info {
            float: right;
            text-align: right;
        }
        .company-info h1 {
            color: #4A5D4E;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .company-info p {
            margin: 2px 0;
            font-size: 12px;
            color: #666;
        }
        /* Tableau élégant */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th {
            background-color: #4A5D4E; /* Votre vert admin */
            color: white;
            text-align: left;
            padding: 12px;
            font-size: 13px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Effet zébré */
        }
        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .clearfix { clear: both; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo.jpg') }}" class="logo">
        <div class="company-info">
            <h1>Rapport National</h1>
            <p>AgriAlert Mali - Secteur Primaire</p>
            <p>Généré le : {{ date('d/m/Y à H:i') }}</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <table shadow>
        <thead>
            <tr>
                <th>Nom de l'Agriculteur</th>
                <th>Contact</th>
                <th>Surface Totale (Ha)</th>
                <th>Région Principale</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td><strong>{{ strtoupper($user->nom) }}</strong></td>
                <td>{{ $user->telephone }}</td>
                <td>{{ number_format($user->champs_sum_aire, 2) ?? '0.00' }} Ha</td>
                <td>{{ $user->champs->first()->region ?? 'Non définie' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document officiel généré par le système AgriAlert. Page 1 / 1
    </div>

</body>
</html>