@extends( 'layouts.portal' )

@section( 'portal_title', 'Connected! You will be contacted shortly by the Moda Match team.' )

@section( 'portal_content' )

    <integration-manage-shopify
            api-key="{{$api_key}}"
            host="{{$host}}"
            shop="{{$shop}}"
    ></integration-manage-shopify>

@endsection

