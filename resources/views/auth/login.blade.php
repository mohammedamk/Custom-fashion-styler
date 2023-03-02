@extends( 'layouts.base' )

@section( 'content' )

    <div id="portal">

        <div class="portal__wrapper">

            <figure class="portal__image">

                <img src="{{asset( '/assets/images/jpg/moda-match-portal.jpg' )}}" alt="" data-rjs="3">

            </figure>
            <!-- /.portal__image -->

            <aside class="portal__content" data-simplebar>

                <section class="portal__inner">

                    <header class="portal__header">

                        <a href="{{url( '/' )}}" class="portal__logo">
                            <img src="{{asset('/assets/images/svg/moda-match-logo-svg.svg')}}" alt="Moda Match">
                        </a>
                        <!-- /.portal__logo -->

                        <div class="portal__tagline">MODA MATCH</div>
                        <!-- /.portal__tagline -->

                        <p class="text text--type-2">Welcome back! Please login to your account.</p>
                        <!-- /.text text--type-2 -->

                    </header>
                    <!-- /.portal__header -->

                    <login-form></login-form>

                </section>
                <!-- /.portal__inner -->

            </aside>
            <!-- /.portal__content -->

        </div>
        <!-- /.portal__wrapper -->

    </div>
    <!-- /#portal -->

@endsection
