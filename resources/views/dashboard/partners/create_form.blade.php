@extends( 'layouts.dashboard' )

@section( 'dashboard_content' )

    <section class="page__section">

        <header class="section__header">

            <h1 class="section__title">Partners / New Partner</h1>
            <!-- /.section__title -->

        </header>
        <!-- /.section__header -->

        <div class="section__content">

            <div class="dashboard__widgets">

                <div class="dashboard__widget dashboard__widget--6">

                    <partner-form></partner-form>

                </div>
                <!-- /.dashboard__widget--6 -->

                <div class="dashboard__widget dashboard__widget--6"></div>
                <!-- /.dashboard__widget dashboard__widget--6 -->

            </div>
            <!-- /.dashboard__widgets -->

        </div>
        <!-- /.section__content -->

    </section>
    <!-- /.page__section -->

@endsection
