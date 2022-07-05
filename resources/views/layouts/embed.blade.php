@if ( isset( $options['is_font_enabled'] ) && $options['is_font_enabled'] )
<style type="text/css">
@font-face {
  font-family: Graphik;
  font-weight: 300;
  src: url('{{ config('app.url') }}/font_file/Graphik-Light.otf');
}
@font-face {
  font-family: Graphik;
  font-weight: 400;
  src: url('{{ config('app.url') }}/font_file/Graphik-Regular.otf');
}
@font-face {
  font-family: Graphik;
  font-weight: 500;
  src: url('{{ config('app.url') }}/font_file/Graphik-Medium.otf');
}
@font-face {
  font-family: Graphik;
  font-weight: 700;
  src: url('{{ config('app.url') }}/font_file/Graphik-Bold.otf');
}
#moda-match-app {
  font-family: 'Graphik' !important;
}
</style>
@endif

<div id="moda-match-embed">

    <app ref="app">

    </app>

</div>
<!-- /#moda-match-embed -->

<script>
  window.moda_match_options = <?php echo json_encode( $options ); ?>
</script>

<script> !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="{{ config( 'services.segment.key' )  }}";analytics.SNIPPET_VERSION="4.15.3"; analytics.load("{{ config( 'services.segment.key' )  }}"); }}(); </script>
