@extends( 'layouts.dashboard' )

@section( 'dashboard_content' )

    <section class="page__section">

        <header class="section__header">

            <div class="section__headerWrapper">

                <div class="section__headerLeft">

                    <h1 class="section__title">Models</h1>
                    <!-- /.section__title -->

                </div>
                <!-- /.section__headerLeft -->

                <div class="section__headerRight">

                    <a href="{{route( 'dashboard.models.new' )}}" class="btn">Add model</a>
                    <!-- /.btn -->

                </div>
                <!-- /.section__headerRight -->

            </div>
            <!-- /.section__headerWrapper -->


        </header>
        <!-- /.section__header -->

        <div class="section__content">

            <div class="dashboard__widgets">

                <div class="dashboard__widget dashboard__widget--12">

                    <models-list></models-list>

                </div>
                <!-- /.dashboard__widget--12 -->

            </div>
            <!-- /.dashboard__widgets -->

        </div>
        <!-- /.section__content -->

    </section>
    <!-- /.page__section -->

@endsection
