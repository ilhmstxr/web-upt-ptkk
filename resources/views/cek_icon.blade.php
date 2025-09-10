<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>label baru</h1>
    <img src="/favicon.ico" alt="">
    <img src="/favicon.svg" alt="">
    <h1>label lama</h1>
    <img src="/favicon_old.ico" alt="">
    <img src="/favicon_old.svg" alt="">
    {{-- <link rel="icon" href="/favicon.ico" sizes="any"> --}}
    <h1>label asset</h1>
    <link rel="icon" href="{{ asset('images/logo-upt-ptkk.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('images/logo-upt-ptkk.png') }}" type="image/png" sizes="any">
    <link rel="icon" href="/favicon.ico" sizes="any">

    <img src="{{ asset('images/logo-upt-ptkk.png')}}" alt="">
</body>

</html>
