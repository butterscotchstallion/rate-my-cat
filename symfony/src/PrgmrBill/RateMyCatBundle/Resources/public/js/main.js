require.config({
    baseUrl: "/bundles/prgmrbillratemycat/js/",
    
    // don't cache me bro
    urlArgs: "bust=" + (new Date()).getTime(),
    
    paths: {
        // Libs
        bootstrap     : 'lib/bootstrap.min',
        cycle         : 'lib/cycle'
    }
});

require(['jquery', 'cycle'], function($) {
    $(function() {
        $('#slideshow').cycle({
            next: $('#next'),
            prev: $('#prev')
        });
    });
});
