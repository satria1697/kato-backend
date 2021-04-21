<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- JavaScript Bundle with Popper -->
    <title>Email</title>
</head>
<body>
<div class="container">
    <p>hello <strong>Andhika</strong></p>
    <p>We have received your order(s).</p>
    <div class="list-group">
        @for($i = 1; $i < 3; $i++)
        <div class="list-group-item">
            <p>Kratom {{$i}}</p>
            <p>Price {{$i *100}}</p>
            <p>Amount {{$i *10}}</p>
        </div>
        @endfor
    </div>
    <p>Our customer service will reach you as soon as possible</p>
</div>
</body>
</html>
