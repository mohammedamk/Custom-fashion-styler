<!doctype html>
<html lang="en" @auth class="my-flow-is-auth" @endauth >
<head>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{\Code4mk\LaraHead\Facades\Khead::getTitle()}}

    @if( config( 'app.env' ) !== 'local' )
        <link rel="stylesheet" href="{{asset( '/assets/dist/bundle/app.min.css' )}}?v={{config('app.version')}}">
    @endif

    <script>

        <?php

            $app_options = [
                'base_url'          => url( '/' ),
                'urls'              => $js_routes(),
                'is_debug'          => config( 'app.env' ) == 'local',
                'is_laravel'        => true,
                'localization'      => [
                    'loading'       => 'Loading...'
                ]
            ];

            ?>

            window.app_options = {!! json_encode( $app_options, JSON_PRETTY_PRINT ) !!}

    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>

<div id="wrapper">

    <div id="vue">

        <app ref="app">

            @if( empty( $is_portal ) )

                @include( 'partials.header' )

            @endif

            <main id="page" @if( ! empty( $is_portal ) ) class="page--fullscreen" @endif>

                @yield( 'content' )

            </main>
            <!-- /#page -->

            @stack( 'panels' )

            @if( empty( $is_portal ) )

                @include( 'partials.footer' )

            @endif

        </app>

    </div>
    <!-- /#vue -->

</div>
<!-- /#wrapper -->

@if( config( 'app.env' ) == 'local' )
    <script src="https://localhost:9000/app.js"></script>
@else
    <script src="{{asset('/assets/dist/bundle/app.min.js')}}?v={{ config( 'app.version' ) }}"></script>
@endif

@stack( 'footer' )

</body>
</html>
