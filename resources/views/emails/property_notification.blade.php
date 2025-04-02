<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificação de Propriedades</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f7f7f7;
            color: #555;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 450px;
            width: 100%;
            text-align: center;
        }
        h2 {
            color: #892C95;
            margin-bottom: 20px;
        }
        h3 {
            color: #ffffff;
            background-color: #892C95;
            padding: 15px;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }
        li {
            background-color: #f4f4f9;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        a {
            text-decoration: none;
            margin-top: 20px;
        }
        button {
            background-color: #892C95;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background-color: #6d2175;
        }
        .footer {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Notificação de Propriedades</h2>
        <p>Olá, {{ $user->name }}. Alerta, propriedade de seu interesse terá o leilão encerrado hoje.</p>
        <ul>
            @foreach ($properties as $property)
                <li>
                    <strong>Número da Propriedade:</strong> {{ $property->property_number }}<br>
                    <strong>Endereço:</strong> {{ $property->address }}, {{ $property->city }} - {{ $property->state }} <br>
                    <strong>Preço Inicial:</strong> R$ {{ number_format($property->price, 2, ',', '.') }}<br>
                    <strong>Data do Leilão:</strong> {{ \Carbon\Carbon::parse($property->auction_date)->format('d/m/Y') }}<br>
                </li>
                <a href="{{ $property->link }}" target="_blank">
                    <button>Ver Propriedade {{ $property->property_number }}</button>
                </a>
            @endforeach
        </ul>
        <div class="footer">
            <p>Esta é uma mensagem automática. Por favor, não responda a este email.</p>
        </div>
    </div>
</body>
</html>
