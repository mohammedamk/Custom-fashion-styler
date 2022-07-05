@extends('layouts.base')

@section('content')

    <div id="portal">

        <div class="portal__wrapper">

            <figure class="portal__image">

                <img src="{{asset( '/assets/images/jpg/moda-match-portal.jpg' )}}" alt="" data-rjs="3">

            </figure>
            <!-- /.portal__image -->

            <div class="portal__page" data-simplebar>

                <div class="portal__inner">

                    <header class="portal__header">

                        <a href="#" class="portal__logo">

                            <img src="{{asset( '/assets/images/png/moda-match-icon.png' )}}" alt="" data-rjs="3">

                        </a>
                        <!-- /.portal__logo -->

                        <h1 class="portal__title">
                            @yield( 'portal_title', 'Moda Match' )
                        </h1>
                        <!-- /.portal__title -->

                    </header>
                    <!-- /.portal__header -->

                    @yield( 'portal_content' )

                </div>
                <!-- /.portal__inner -->

            </div>
            <!-- /.portal__page -->

        </div>
        <!-- /.portal__wrapper -->

    </div>
    <!-- /#portal -->

@endsection
