@extends( 'layouts.dashboard' )

@section( 'dashboard_content' )

    <section class="page__section">

        <header class="section__header">

            <div class="section__headerWrapper">

                <div class="section__headerLeft">


                    <h1 class="section__title">Partners / {{$partner->name}}</h1>
                    <!-- /.section__title -->

                </div>
                <!-- /.section__headerLeft -->

                <div class="section__headerRight">

                    <a href="{{route( 'dashboard.partners' )}}" class="btn btn--button">Go back</a>
                    <!-- /.btn btn--button -->

                </div>
                <!-- /.section__headerRight -->

            </div>
            <!-- /.section__headerWrapper -->

        </header>
        <!-- /.section__header -->

        <div class="section__content">

            <div class="dashboard__widgets">

                <div class="dashboard__widget dashboard__widget--6">

                    <partner-form :partner="{{json_encode( $partner )}}"></partner-form>

                </div>
                <!-- /.dashboard__widget--6 -->

                <div class="dashboard__widget dashboard__widget--6">

                    <partner-integration-form
                        :partner="{{json_encode( $partner )}}"
                        :integration="{{json_encode( $integration )}}"
                    ></partner-integration-form>

                    <partner-models-form
                        :partner="{{json_encode( $partner )}}"
                    ></partner-models-form>

                    <partner-import-hub :partner="{{json_encode( $partner )}}"></partner-import-hub>

                </div>
                <!-- /.dashboard__widget dashboard__widget--6 -->

            </div>
            <!-- /.dashboard__widgets -->

        </div>
        <!-- /.section__content -->

    </section>
    <!-- /.page__section -->

@endsection
