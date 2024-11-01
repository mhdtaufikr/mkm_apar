@extends('layouts.master')

@section('content')
<style>
    #lblGreetings {
        font-size: 1rem;
    }
    .page-header .page-header-content {
        padding-top: 0rem;
        padding-bottom: 1rem;
    }
    #chartdiv {
        width: 100%;
        height: 400px;
    }
</style>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-2">
                        <h1 class="page-header-title">
                            <label id="lblGreetings"></label>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <section class="content">
        <div class="container-fluid">
            <div class="container-fluid px-4 mt-n10">
                <div class="row">
                    <!-- Preventive Maintenance Chart Card -->
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h3>
                                    Preventive Maintenance Yearly
                                </h3>
                            </div>
                            <div class="card-body">
                                <div id="pmChartDiv" style="width: 100%; height: 375px;"></div>
                            </div>
                        </div>
                    </div>

                        <script>
                            am5.ready(function() {
                                function createPMChart(plannedData, actualData, trendData, endDate, year) {
                                    var root = am5.Root.new("pmChartDiv");

                                    root.setThemes([am5themes_Animated.new(root)]);

                                    // Add Year Label at the top of the chart
                                    var yearLabel = root.container.children.push(am5.Label.new(root, {
                                        text: year, // Display the year
                                        fontSize: 20,
                                        fontWeight: "bold",
                                        x: am5.p50,
                                        centerX: am5.p50,
                                        y: -10 // Adjust position of the year label
                                    }));

                                    var chart = root.container.children.push(am5xy.XYChart.new(root, {
                                        panX: false,
                                        panY: false,
                                        wheelX: "none",
                                        wheelY: "none",
                                        layout: root.verticalLayout
                                    }));

                                    // Define month names for X-axis tooltip
                                    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                                    // X-axis - Months
                                    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                                        categoryField: "month",
                                        tooltip: am5.Tooltip.new(root, {
                                            labelText: "{category}", // Show month name in the tooltip
                                        }),
                                        renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 30 })
                                    }));

                                    // Set X-axis categories (Months)
                                    xAxis.data.setAll(Array.from({ length: endDate }, (_, i) => ({ month: monthNames[i] })));

                                    // Y-axis - Quantities
                                    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                                        min: 0,
                                        renderer: am5xy.AxisRendererY.new(root, { strokeOpacity: 0.1 })
                                    }));

                                    var yAxisRight = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                                        min: 0,
                                        max: 120,  // For percentage accuracy
                                        strictMinMax: true,
                                        renderer: am5xy.AxisRendererY.new(root, { opposite: true, strokeOpacity: 0.1 })
                                    }));

                                    // Y-axis labels
                                    yAxis.children.moveValue(am5.Label.new(root, {
                                        rotation: -90,
                                        text: "Quantity",
                                        y: am5.p50,
                                        centerX: am5.p50
                                    }), 0);

                                    yAxisRight.children.moveValue(am5.Label.new(root, {
                                        rotation: -90,
                                        text: "Percentage (%)",
                                        y: am5.p50,
                                        centerX: am5.p50
                                    }), 0);

                                    // Planned series (Bars)
                                    var plannedSeries = chart.series.push(am5xy.ColumnSeries.new(root, {
                                        name: "Planned PM",
                                        xAxis: xAxis,
                                        yAxis: yAxis,
                                        valueYField: "planned",
                                        categoryXField: "month",
                                        clustered: true,
                                        tooltip: am5.Tooltip.new(root, { labelText: "{name}: {valueY}" })
                                    }));

                                    plannedSeries.columns.template.setAll({ fill: am5.color("#36A2EB"), width: am5.percent(80) });
                                    plannedSeries.data.setAll(plannedData.map((value, i) => ({ month: monthNames[i], planned: value || 0 })));

                                    // Actual series (Bars)
                                    var actualSeries = chart.series.push(am5xy.ColumnSeries.new(root, {
                                        name: "Actual PM",
                                        xAxis: xAxis,
                                        yAxis: yAxis,
                                        valueYField: "actual",
                                        categoryXField: "month",
                                        clustered: true,
                                        tooltip: am5.Tooltip.new(root, { labelText: "{name}: {valueY}" })
                                    }));

                                    actualSeries.columns.template.setAll({ fill: am5.color("#FF9F40"), width: am5.percent(80) });
                                    actualSeries.data.setAll(actualData.map((value, i) => ({ month: monthNames[i], actual: value || 0 })));

                                    // Percentage Trend (Line)
                                    var percentageSeries = chart.series.push(am5xy.LineSeries.new(root, {
                                        name: "Percentage Accuracy",
                                        xAxis: xAxis,
                                        yAxis: yAxisRight,
                                        valueYField: "percentage",
                                        categoryXField: "month",
                                        tooltip: am5.Tooltip.new(root, { labelText: "{name}: {valueY}%" }),
                                        stroke: am5.color(0x000000),
                                        fill: am5.color(0x000000)
                                    }));

                                    percentageSeries.strokes.template.setAll({ strokeWidth: 3 });
                                    percentageSeries.data.setAll(trendData.map((value, i) => ({ month: monthNames[i], percentage: value || 0 })));

                                    percentageSeries.bullets.push(function(root, series, dataItem) {
                                        var value = dataItem.dataContext.percentage;
                                        var bulletColor = value < 100 ? am5.color(0xff0000) : am5.color(0x00ff00);
                                        return am5.Bullet.new(root, {
                                            sprite: am5.Circle.new(root, {
                                                strokeWidth: 3,
                                                stroke: series.get("stroke"),
                                                radius: 5,
                                                fill: bulletColor
                                            })
                                        });
                                    });

                                    // Standard Line (Dashed 100% Line)
                                    var standardLine = chart.series.push(am5xy.LineSeries.new(root, {
                                        name: "Standard (100%)",
                                        xAxis: xAxis,
                                        yAxis: yAxisRight,
                                        valueYField: "standard",
                                        categoryXField: "month",
                                        stroke: am5.color(0x00FF00),
                                        tooltip: am5.Tooltip.new(root, { labelText: "Standard: 100%" })
                                    }));

                                    standardLine.strokes.template.setAll({
                                        strokeWidth: 2,
                                        strokeDasharray: [5, 5],  // Dashed line
                                        stroke: am5.color(0x00FF00)  // Green color
                                    });

                                    // Set 100% line data
                                    var standardData = Array.from({ length: endDate }, (_, i) => ({ month: monthNames[i], standard: 100 }));
                                    standardLine.data.setAll(standardData);

                                    // Trend Line (Actual + Planned / 2) with Dotted Style
                                    var trendSeries = chart.series.push(am5xy.LineSeries.new(root, {
                                        name: "Trend Line",
                                        xAxis: xAxis,
                                        yAxis: yAxis,
                                        valueYField: "trend",
                                        categoryXField: "month",
                                        tooltip: am5.Tooltip.new(root, { labelText: "{name}: {valueY}" }),
                                        stroke: am5.color(0xFFA500)  // Orange color for the trend line
                                    }));

                                    // Make the trend line dotted
                                    trendSeries.strokes.template.setAll({
                                        strokeWidth: 3,
                                        strokeDasharray: [4, 4]  // Dotted line
                                    });

                                    var trendData = plannedData.map((planned, i) => {
                                        var actual = actualData[i] || 0;
                                        return { month: monthNames[i], trend: (planned + actual) / 2 };
                                    });
                                    trendSeries.data.setAll(trendData);

                                    // Add Legend
                                    var legend = chart.children.push(am5.Legend.new(root, {
                                        centerX: am5.p50,
                                        x: am5.p50
                                    }));

                                    legend.data.setAll(chart.series.values);

                                    // Add Cursor
                                    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                                        behavior: "none",
                                        xAxis: xAxis
                                    }));

                                    cursor.lineY.set("visible", false);

                                    chart.appear(1000, 100);
                                    actualSeries.appear();
                                    plannedSeries.appear();
                                    percentageSeries.appear();
                                    trendSeries.appear();
                                    standardLine.appear();
                                }

                                // Data from the controller
                                var plannedData = {!! json_encode($plannedData) !!};
                                var actualData = {!! json_encode($actualData) !!};
                                var trendData = {!! json_encode($trendData) !!};
                                var endDate = 12;  // Assuming 12 months
                                var currentYear = new Date().getFullYear();  // Get the current year

                                // Create the chart
                                createPMChart(plannedData, actualData, trendData, endDate, currentYear);
                            });
                        </script>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3>Accumulation of Fire Extinguishers Inspected This Month ({{ now()->format('F') }})</h3>

                            </h3>
                            </div>
                            <div class="card-body">
                                <div id="chartdiv" style="width: 100%; height: 375px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart for Accumulated APAR Checks -->
                <div class="card">
                <div class="card-header">
                <!-- Table for APAR Check Status -->
                <h3>List of APARs that Must be Checked</h3>
                </div>
                <div class="card-body">
                    <table id="tableUser" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>APAR Number</th>
                                <th>Location</th>
                                <th>Last Checked Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aparList as $apar)
                                <tr>
                                    <td>{{ $apar->no_apar }}</td>
                                    <td>{{ $apar->location }} - {{ $apar->group == '1' ? 'Stamping' : 'Engine' }}</td>
                                    <td>{{ $apar->last_checked_date ?? 'Not checked this month' }}</td>
                                    <td>{{ $apar->status }}</td>
                                    <td>
                                        @if($apar->checked_this_month)
                                            <button class="btn btn-success" disabled>Checked</button>
                                        @else
                                            <form action="{{ route('apar.check') }}" method="POST">
                                                @csrf
                                                <input type="text" value="{{$apar->no_apar}}" hidden name="mechine" >
                                                <button type="submit" class="btn btn-primary">Mark as Checked</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>



            </div>
        </div>
    </section>

    <script>
        // Greetings Script
        var myDate = new Date();
        var hrs = myDate.getHours();
        var greet;
        if (hrs < 12) greet = 'Good Morning';
        else if (hrs >= 12 && hrs <= 17) greet = 'Good Afternoon';
        else if (hrs >= 17 && hrs <= 24) greet = 'Good Evening';
        document.getElementById('lblGreetings').innerHTML =
            '<b>' + greet + '</b> and welcome to Checksheet Preventive Maintenance APAR';

        // amCharts Script for Accumulated APAR Checks
        am5.ready(function() {
            var root = am5.Root.new("chartdiv");
            root.setThemes([ am5themes_Animated.new(root) ]);

            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: true,
                panY: true,
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true
            }));

            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "apar",
                renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 30 })
            }));

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {})
            }));

            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "APAR Checks",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "checks",
                categoryXField: "apar"
            }));

            series.data.setAll(@json($chartData));

            series.columns.template.setAll({ tooltipText: "{categoryX}: {valueY}" });

            xAxis.data.setAll(@json($chartData));
        });
    </script>
    <script>
        $(document).ready(function() {
          var table = $("#tableUser").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
          });
        });
      </script>
</main>
@endsection
