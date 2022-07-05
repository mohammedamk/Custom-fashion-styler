@extends( 'layouts.dashboard' )

@section( 'dashboard_content' )

    <section class="page__section">

        <header class="section__header">

            <h1 class="section__title">{{$model->name}} / Models</h1>
            <!-- /.section__title -->

        </header>
        <!-- /.section__header -->

        <div class="section__content">

            <model-form :model="{{json_encode( $model )}}"></model-form>

        </div>
        <!-- /.section__content -->

    </section>
    <!-- /.page__section -->

@endsection
