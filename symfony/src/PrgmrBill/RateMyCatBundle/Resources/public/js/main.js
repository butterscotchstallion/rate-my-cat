require.config({
    baseUrl: "/bundles/prgmrbillratemycat/js/",
    
    // don't cache me bro
    urlArgs: "bust=" + (new Date()).getTime(),
    
    paths: {
        // Libs
        bootstrap     : 'lib/bootstrap.min',
        cycle         : 'lib/cycle',
        rating        : 'lib/jquery.rating.pack'
    }
});

require(['jquery', 'cycle', 'rating', 'bootstrap'], function($) {
    $(function() {
        // Initialize slideshow
        $('#slideshow').cycle({
            next: $('#next'),
            prev: $('#prev'),
            // turns off autoadvancing
            timeout: 0
        });
        
        // Initialize rating
        $('.rating').rating({
            
        });
    });
});
