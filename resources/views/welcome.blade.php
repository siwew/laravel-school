<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
{{--        <script type="text/javascript" src="/js/app.js"></script>--}}
        <link rel="stylesheet/scss" lang="scss"  href="/css/app.css">

    </head>
    <body>
        <div id="app">
            <router-view></router-view>
        </div>
        <script type="text/javascript" src="/js/app.js"></script>
    </body>
</html>
