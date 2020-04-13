@if (!env('APP_DEBUG'))
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162371764-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-162371764-1');
    </script>
@endif
