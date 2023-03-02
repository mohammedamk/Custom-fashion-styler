@extends('layouts.base')

@section('content')

    <div id="dashboard">

        <div class="dashboard__wrapper">

            <aside class="dashboard__sidebar" data-simplebar>

                <a href="{{route( 'dashboard' )}}" class="sidebar__logo">
                    <span>Moda Match</span>
                </a>
                <!-- /.sidebar__logo -->

                @include( 'partials.dashboard.menu' )

            </aside>
            <!-- /.dashboard__sidebar -->

            @include( 'partials.dashboard.header' )

            <div class="dashboard__page" data-simplebar>

                @yield( 'dashboard_content' )

            </div>
            <!-- /.dashboard__page -->

        </div>
        <!-- /.dashboard__wrapper -->

    </div>
    <!-- /#dashboard -->

@endsection
