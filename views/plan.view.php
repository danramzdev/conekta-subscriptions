<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conekta Suscriptores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>
<form action="suscripcion.php" method="POST" id="card-form">
    <span class="card-errors"></span>
    <div>
        <label>
            <span>Nombre del tarjetahabiente</span>
            <input type="text" size="20" data-conekta="card[name]">
        </label>
    </div>
    <div>
        <label>
            <span>Número de tarjeta de crédito</span>
            <input type="text" size="20" data-conekta="card[number]">
        </label>
    </div>
    <div>
        <label>
            <span>CVC</span>
            <input type="text" size="4" data-conekta="card[cvc]">
        </label>
    </div>
    <div>
        <label>
            <span>Fecha de expiración (MM/AAAA)</span>
            <input type="text" size="2" data-conekta="card[exp_month]">
        </label>
        <span>/</span>
        <input type="text" size="4" data-conekta="card[exp_year]">
    </div>
    <button type="submit">Crear token</button>
</form>

<script src="assets/js/conekta-client.js"></script>
</body>
</html>