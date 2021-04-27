<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <!-- JavaScript Bundle with Popper -->
    <title>Email</title>
</head>
<body>
<div class="container mx-auto" style="width: 100%; max-width: 768px; margin: 0 auto;">
    <p>Hello Sir/Madam {{$name}}</p>
    <p>Thanks for doing business with us. Here {{ count($cart) > 1 ? 'are' : 'is'}} your order{{ count($cart) > 1 ? 's' : ''}} Summary:</p>
    <table class="table-fixed border-collapse w-3/4 mx-auto my-6" style="
        table-layout: fixed;
        border-collapse: collapse;
        width: 75%;
        margin: 1.5rem auto;
        ">
        <thead>
            <tr class="border-t border-b-2 border-black"
            style="
                border-top: 1px solid black;
                border-bottom: 2px solid black;
            ">
                <th class="w-1/3" style="width: 33%">Product Name</th>
                <th class="w-1/3" style="width: 33%">Price</th>
                <th class="w-1/3" style="width: 33%">Buy</th>
            </tr>
        </thead>
        <tbody class="text-center border-b-2 border-black"
        style="border-bottom: 2px solid black;
        text-align: center;">
            @foreach ($cart as $ca)
            <tr>
                <td>{{$ca->goods->name}}</td>
                <td>$ {{$ca->goods->price}}</td>
                <td>{{$ca->buying}}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <p>We are currently processing your orders. Our operational manager will contact you shortly</p>
    <p>Cheers, Kratomedical</p>
</div>
</body>
</html>
