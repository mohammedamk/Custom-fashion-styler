@extends( 'layouts.portal' )

@section( 'portal_title', 'Authorizing...' )

@section( 'portal_content' )

    <integration-iframe-shopify
            api-key="{{$api_key}}"
            host="{{$host}}"
            shop="{{$shop}}"
            scopes="{{$scopes}}"
            redirect-uri="{{$redirect_uri}}"
    ></integration-iframe-shopify>

@endsection

