
/*
Date Picker
========================================================================================
*/

// Format date picker to show only year.
$("#datepicker").datepicker({
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years",
    startDate: "01/01/2022",
    autoclose:true //to close picker once year is selected
});


// Set default year
$('#datepicker').datepicker('update', '2022-01-01');

// Detect change year
$("#datepicker").on("changeDate",function(){
    var selected = $(this).val();
    drawChart();
});


/*
Handle Chart Page
========================================================================================
*/

google.charts.load('current', {'packages':['corechart']});
//google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    // Get URL Parameters
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    // Object to send to server
    var sendData = {
        platfom: urlParams.get('platform'),
        instance_type: urlParams.get('instance_type'),
        year: $('#datepicker').datepicker('getFormattedDate')

    };


    // Calling ajax to get json data and render to chartpage
    $.ajax({
            url: "/Home/HandleChartpage",
            dataType: "json",
            type: "post",
            data: {
                'function': 'getBenchmarkData',
                'platform': sendData.platfom,
                'instance_type': sendData.instance_type,
                'year': sendData.year
            },
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            success: function (data) {

                var content_data = data.content;
                array = [];
                if (content_data !== "No data") {
                    content_data.forEach(function(number, i) {
                        console.log(content_data[i]);
                        array.append
                        
                        var data = google.visualization.arrayToDataTable([
                            ['Month', 'Score'],
                            ['Jan',  1000],
                            ['Feb',  1170],
                            ['Mar',  660],
                            ['Apr',  1030],
                            ['May',  1000],
                            ['Jun',  1170],
                            ['Jul',  660],
                            ['Aug',  1030],
                            ['Oct',  1000],
                            ['Nov',  1170],
                            ['Dec',  660],
                            ]
                        );


                    });
    
                }


                // CPU section

                // Memory section

                // Disk section

                // Filesytem section

                // var data = google.visualization.arrayToDataTable([
                //     ['Month', 'Score'],
                //     ['Jan',  1000],
                //     ['Feb',  1170],
                //     ['Mar',  660],
                //     ['Apr',  1030],
                //     ['May',  1000],
                //     ['Jun',  1170],
                //     ['Jul',  660],
                //     ['Aug',  1030],
                //     ['Oct',  1000],
                //     ['Nov',  1170],
                //     ['Dec',  660],
                //     ]);
                
                //     var options = {
                //     title: 'Company Performance',
                //     hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
                //     vAxis: {minValue: 0}
                //     };
                
                //     const start = 1;
                //     const end = 12;
                
                //     let chart_id_list = [...Array(end - start + 1).keys()].map(x => x + start);
                //     for (let chart_id of chart_id_list) {
                //         var chart = new google.visualization.AreaChart(document.getElementById('chart_div_'+ chart_id));
                //         chart.draw(data, options);
                //     }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Got an Error');
            }
    });

}