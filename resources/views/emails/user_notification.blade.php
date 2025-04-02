<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro no Radar Imobiliário</title>
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
        a {
            color: #ffffff;
            background-color: #892C95;
            padding: 15px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin: 20px 0;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
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
        <h2>Cadastro no Radar Imobiliário</h2>
        <p>Olá, {{ $notifiable->name }}</p>
        <p>Você realizou o cadastro em nossa plataforma. Clique no botão abaixo para acessar a página:</p>
        <a href="{{ $actionUrl }}">Acessar</a>
        <p>Obrigado por confiar na nossa plataforma!</p>
        <div class="footer">
            <p>Se você não esperava esta notificação, por favor ignore este email.</p>
        </div>
    </div>
</body>
</html>
