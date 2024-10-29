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
            <div class="container-xl px-4 mt-n10">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Accumulation of Fire Extinguishers Inspected This Month ({{ now()->format('F') }})</h3>

                    </h3>
                    </div>
                    <div class="card-body">
                        <div id="chartdiv"></div>
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
