<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">    

    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col">
                    1
                    <div id="chart_1" style="width:auto; height:300px;"></div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    2
                    <div id="chart_2" style="width:auto; height:300px;"></div> 
                </div>
            </div>

            <div class="row">
                <div class="col">
                    3
                    <div id="chart_3" style="width:auto; height:300px;"></div> 
                </div>
            </div>

        </div>
        
    </body>
    
    <script>
        // Visualization API with the 'corechart' package.
        google.charts.load('visualization', { packages: ['corechart'] });
        google.charts.setOnLoadCallback(drawLineChart);

        function drawLineChart() {
            $.ajax({
                url: "./benchmark.json",
                dataType: "json",
                type: "GET",
                contentType: "application/json; charset=utf-8",
                success: function (data) {
                    var arrSales = [['Month', 'Sales Figure', 'Perc. (%)']];    // Define an array and assign columns for the chart.

                    // Loop through each data and populate the array.
                    $.each(data, function (index, value) {

                        arrSales.push([value.Month, value.Sales_Figure, value.Perc]);
                    });

                    // Set chart Options.
                    var options = {
                        title: 'Monthly Sales',
                        curveType: 'function',
                        legend: { position: 'bottom', textStyle: { color: '#555', fontSize: 14} }  // You can position the legend on 'top' or at the 'bottom'.
                    };

                    // Create DataTable and add the array to it.
                    var figures = google.visualization.arrayToDataTable(arrSales)

                    // Define the chart type (LineChart) and the container (a DIV in our case).
                    var chart_1 = new google.visualization.LineChart(document.getElementById('chart_1'));
                    var chart_2 = new google.visualization.LineChart(document.getElementById('chart_2'));
                    var chart_3 = new google.visualization.LineChart(document.getElementById('chart_3'));

                    // Draw the chart with Options.
                    chart_1.draw(figures, options);
                    chart_2.draw(figures, options);
                    chart_3.draw(figures, options);    
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('Got an Error');
                }
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>