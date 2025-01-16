@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>APAR Details</h2>
        </div>
        <div class="card-body">

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
            <!-- Yearly Schedule -->


        </div>
    </div>

</div>
@endsection
