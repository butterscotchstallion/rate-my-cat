/**
 * Initializes all charts
 *
 */
define('charts', ['jquery', 'highcharts'], function ($, Highcharts) {

    // Radialize the colors
    Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
        return {
            radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    });
    
    // Build the chart
    var votesChart = $('#votes-chart')
    
    votesChart.highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: votesChart.data('title')
        },
        tooltip: {
            //pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            //percentageDecimals: 1
            formatter: function() {
                return '<b>'+ this.point.name +'</b>: '+ this.point.y + '%';
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    formatter: function() {
                        return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: votesChart.data('title'),
            data: votesChart.data('vote-data')
        }]
    });
    
});