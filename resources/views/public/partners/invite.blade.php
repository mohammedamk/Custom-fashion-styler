@extends( 'layouts.portal' )

@section( 'portal_title', $partner->name )

@section( 'portal_content' )

    <div class="align--center margin--b-45">

        <p class="text text--type-1">We would like to connect to your <strong>{{$integration->integration_name}}</strong></p>
        <!-- /.text text--type-1 -->

    </div>
    <!-- /.align--center -->

    <?php

    switch( $integration->integration_name )
    {
        case 'Shopify':

            ?>

            <integration-connect-shopify
                :partner="{{json_encode( $partner )}}"
                :integration="{{json_encode( $integration )}}"
                invite="{{$invite}}"
                secret="{{$secret}}"
            ></integration-connect-shopify>

            <?php

            break;
    }

    ?>

@endsection
