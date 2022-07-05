<header class="dashboard__header">

    <div class="header__wrapper">

        <div class="header__left"></div>
        <!-- /.header__left -->

        <div class="header__right">

            @if( $user = auth()->user() )

                <span class="header__toggle">{{$user->name}}</span>
                <!-- /.header__toggle -->

            @else

                <span class="header__toggle">User</span>
                <!-- /.header__toggle -->

            @endif

        </div>
        <!-- /.header__right -->

    </div>
    <!-- /.header__wrapper -->

</header>
<!-- /.dashboard__header -->
