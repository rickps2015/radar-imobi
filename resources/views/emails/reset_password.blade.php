<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha</title>
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
        .footer {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Redefinição de Senha</h2>
        <p>Olá, você solicitou a redefinição de senha para a sua conta. Use o código abaixo para redefinir sua senha:</p>
        <h3>{{ $resetCode }}</h3>
        <p>Este código é válido por 10 minutos.</p>
        <div class="footer">
            <p>Se você não solicitou a redefinição de senha, por favor ignore este email.</p>
        </div>
    </div>
</body>
</html>
