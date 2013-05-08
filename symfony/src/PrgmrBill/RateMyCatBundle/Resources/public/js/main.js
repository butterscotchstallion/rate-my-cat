require.config({
    baseUrl: "/bundles/prgmrbillratemycat/js/",
    
    // don't cache me bro
    urlArgs: "bust=" + (new Date()).getTime(),
    
    paths: {
        // Libs
        bootstrap     : 'lib/bootstrap.min',
        cycle         : 'lib/cycle',
        rating        : 'lib/jquery.rating.pack',
        highcharts    : 'lib/highcharts',
        
        // App
        charts        : 'app/charts'
    },
    shim: {
        "highcharts": {
            "exports": "Highcharts",
            "deps": ["jquery"] 
        }
    }
});

require(['jquery', 'cycle', 'rating', 'bootstrap', 'charts'], function($) {
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
        
        // Initialize highcharts
        
        // Activate tab
        var tab = getParameterByName('tab');
        
        if (tab) {
            // Deactivate other tabs
            $('.tab-pane').removeClass('active');
            
            // Activate tab
            var tabEl = $('#cat-profile-tab-nav a[href="#' + tab + '"]');
            tabEl.tab('show');
            
            // Add active class
            $('#' + tab).addClass('active');
        }
    });
});

function getParameterByName(name)
{
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.search);
    
    if (results == null) {
        return "";
    } else {
        return decodeURIComponent(results[1].replace(/\+/g, " "));
    }
}
