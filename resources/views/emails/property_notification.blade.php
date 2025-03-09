<!DOCTYPE html>
<html>
<head>
    <title>Notificação de Propriedades</title>
</head>
<body>
    <h1>Olá, {{ $user->name }}</h1>
    <p>Estas são as propriedades que correspondem aos seus filtros:</p>
    <ul>
        @foreach ($properties as $property)
            <li>{{ $property->property_number }} - {{ $property->address }}</li>
        @endforeach
    </ul>
</body>
</html>
