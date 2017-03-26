/*
 *  Document   : base_pages_dashboard.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Dashboard Page
 */

var BasePagesDashboard = function () {
    // Chart.js Chart, for more examples you can check out http://www.chartjs.org/docs
    var initDashChartJS = function () {
        // Get Chart Container
        var $dashChartLinesCon = jQuery('.js-dash-chartjs-lines')[0].getContext('2d');

        // Set Chart and Chart Data variables
        var $dashChartLines, $dashChartLinesData;

        // Lines Chart Data
        var $dashChartLinesData = {
            labels: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'],
            datasets: [
                {
                    label: 'This Week',
                    fillColor: 'rgba(44, 52, 63, .07)',
                    strokeColor: 'rgba(44, 52, 63, .25)',
                    pointColor: 'rgba(44, 52, 63, .25)',
                    pointStrokeColor: '#fff',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(44, 52, 63, 1)',
                    data: [34, 42, 40, 65, 48, 56, 80]
                },
                {
                    label: 'Last Week',
                    fillColor: 'rgba(44, 52, 63, .1)',
                    strokeColor: 'rgba(44, 52, 63, .55)',
                    pointColor: 'rgba(44, 52, 63, .55)',
                    pointStrokeColor: '#fff',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(44, 52, 63, 1)',
                    data: [18, 19, 20, 35, 23, 28, 50]
                }
            ]
        };

        // Init Lines Chart
        $dashChartLines = new Chart($dashChartLinesCon).Line($dashChartLinesData, {
            scaleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
            scaleFontColor: '#999',
            scaleFontStyle: '600',
            tooltipTitleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
            tooltipCornerRadius: 3,
            maintainAspectRatio: false,
            responsive: true
        });
    };

    // Chart.js Chart, for more examples you can check out http://www.chartjs.org/docs
    var initDashChart = function () {

        $('.follower-count-chart-lines').each(
                function ( index ) {
                    // Get Chart Container
                    var $dashChartLinesCon = jQuery('.follower-count-chart-lines')[index].getContext('2d');
                    var $dashChartLinesContainer = jQuery('.follower-count-chart-lines')[index];
                    var $data = $($dashChartLinesContainer).attr("data-csv").split(",");
                    var $data_label = $($dashChartLinesContainer).attr("data-label").split(",");
                    // Set Chart and Chart Data variables
                    var $dashChartLines, $dashChartLinesData;

                    // Lines Chart Data
                    var $dashChartLinesData = {
                        labels: $data_label,
                        datasets: [
                            {
                                label: 'This Week',
                                fillColor: 'rgba(20, 173, 196, .07)',
                                strokeColor: 'rgba(20, 173, 196, .25)',
                                pointColor: 'rgba(20, 173, 196, .25)',
                                pointStrokeColor: '#fff',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: 'rgba(20, 173, 196, 1)',
                                data: $data
                            }
                        ]
                    };

                    // Init Lines Chart
                    $dashChartLines = new Chart($dashChartLinesCon).Line($dashChartLinesData, {
                        scaleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
                        scaleFontColor: '#428bca',
                        scaleFontStyle: '600',
                        tooltipTitleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
                        tooltipCornerRadius: 3,
                        maintainAspectRatio: false,
                        responsive: true
                    });
                    
//                    $dashChartLines = new Chart($dashChartLinesCon).Line($dashChartLinesData, {
//                        scaleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
//                        scaleFontColor: '#999',
//                        scaleFontStyle: '600',
//                        tooltipTitleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
//                        tooltipCornerRadius: 3,
//                        maintainAspectRatio: false,
//                        responsive: true
//                    });
                }
        );



    };

    return {
        init: function () {
            // Init ChartJS chart
            initDashChartJS();
            initDashChart();
        }
    };
}();

// Initialize when page loads
jQuery(function () {
    BasePagesDashboard.init();
});