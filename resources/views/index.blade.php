<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <script language="JavaScript" type="text/javascript">
        var txt = "My Daily - Idil Ini Boss!! -  - ";
        var speed = 250;
        var refresh = null;

        function move() {
            document.title = txt;
            txt = txt.substring(1, txt.length) + txt.charAt(0);
            refresh = setTimeout("move()", speed);
        }
        move();
    </script>

    <meta name="description" content="My Daily" />
    <meta name="keywords" content="My Daily" />
    <meta name="author" content="Idil" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('/') }}aset/images/logo_idil.png" />

    <style>
        .force-mobile {
            width: 370px;
            max-width: 370px;
            height: 816px;
            margin: 0px;
            padding: 0px;
            border-style: none;
            border-color: inherit;
            border-width: 0px;
            -webkit-transform-origin: 0 0;
            display: block;
        }

    </style>
</head>

<body>
    <iframe id="forceMobile" class="force-mobile" src="{{ route('login') }}"></iframe>
</body>

</html>
