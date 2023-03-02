@extends( 'layouts.dashboard' )

@section( 'dashboard_content' )

    <section class="page__section">

        <header class="section__header">

            <div class="section__headerWrapper">

                <div class="section__headerLeft">

                    <h1 class="section__title">Products / {{$partner->name}}</h1>
                    <!-- /.section__title -->

                </div>
                <!-- /.section__headerLeft -->

                <div class="section__headerRight">

                    <a href="{{route( 'dashboard.partners.edit', [ 'partner' => $partner ] )}}" class="btn">Manage partner</a>
                    <!-- /.btn -->

                </div>
                <!-- /.section__headerRight -->

            </div>
            <!-- /.section__headerWrapper -->


        </header>
        <!-- /.section__header -->

        <div class="section__content">

            <div class="dashboard__widgets">

                <div class="dashboard__widget dashboard__widget--8">

                    <products-list :partner="{{json_encode( $partner )}}"></products-list>

                </div>
                <!-- /.dashboard__widget--8 -->

                <div class="dashboard__widget dashboard__widget--4">

                    <partner-import-hub :partner="{{json_encode( $partner )}}" :refresh="true"></partner-import-hub>

                    <category-mapper :partner="{{json_encode( $partner )}}"></category-mapper>

                </div>
                <!-- /.dashboard__widget--4 -->

            </div>
            <!-- /.dashboard__widgets -->

        </div>
        <!-- /.section__content -->

    </section>
    <!-- /.page__section -->

@endsection
