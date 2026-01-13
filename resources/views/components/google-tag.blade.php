@if(config('emperan.google_tag_id'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('emperan.google_tag_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag () { dataLayer.push( arguments ); }
        gtag( 'js', new Date() );

        gtag( 'config', '{{ config('emperan.google_tag_id') }}' );
    </script>
@endif