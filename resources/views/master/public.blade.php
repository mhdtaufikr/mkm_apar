<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="APAR Details and Yearly Schedule">
    <meta name="author" content="PT MKM">
    <title>APAR Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2, h3, h5 {
            font-weight: bold;
        }

        .card {
            margin-bottom: 20px;
        }

        .table {
            font-size: 14px;
        }

        .fas.fa-circle {
            color: green;
        }

        .far.fa-circle {
            color: gray;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Page Header -->
        <header class="mb-4">
            <h1 class="text-center">APAR Details and Yearly Schedule</h1>
        </header>

        <div class="card">
            <div class="card-header">
                <h2>APAR Details</h2>
            </div>
            <div class="card-body">
                <!-- APAR Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><strong>APAR Information</strong></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Type:</strong> {{ $apar->type }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Number:</strong> {{ $apar->no_apar }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Location:</strong> {{ $apar->location }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Year:</strong> {{ $apar->year }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Group:</strong> {{ $apar->group }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yearly Schedule -->
                <div class="card">
                    <div class="card-header">
                        <h3>Yearly Schedule</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Dates</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                <tr>
                                    <td>{{ $month }}</td>
                                    <td>
                                        @if(isset($yearlyRecords[$month]))
                                            <i class="fas fa-circle"></i>
                                            {{ $yearlyRecords[$month]->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d'))->implode(', ') }}
                                        @else
                                            <i class="far fa-circle"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($yearlyRecords[$month]))
                                            {{ $yearlyRecords[$month]->pluck('remarks')->implode(', ') }}
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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
