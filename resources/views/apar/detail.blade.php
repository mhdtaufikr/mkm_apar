@extends('layouts.master')

@section('content')
<main>
    <!-- Page header -->
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-0">
            </div>
        </div>
    </header>

    <!-- Main page content -->
    <div class="container-fluid px-4 mt-n10">
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="mb-3 col-sm-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">APAR Checksheet - {{ $data->aparInformation->no_apar ?? 'N/A' }}</h3>
                                </div>

                                <div class="card-body">
                                    <!-- Display APAR Head Information -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><label class="form-label">APAR Type</label></strong>
                                            <p>{{ $data->aparInformation->type ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><label class="form-label">APAR Number</label></strong>
                                            <p>{{ $data->aparInformation->no_apar ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><label class="form-label">Location</label></strong>
                                            <p>{{ $data->aparInformation->location ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><label class="form-label">Year</label></strong>
                                            <p>{{ $data->aparInformation->year ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><label class="form-label">Group</label></strong>
                                            <p>{{ $data->aparInformation->group == '1' ? 'Stamping' : 'Engine' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><label class="form-label">PIC</label></strong>
                                            <p>{{ $data->pic ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><label class="form-label">Image</label></strong><br>
                                            @if($data->img)
                                                <img src="{{ asset($data->img) }}" alt="Image" class="img-fluid" style="width: 200px; height: 200px;">
                                            @else
                                                <p>N/A</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <strong><label class="form-label">Signature</label></strong>
                                            @if($data->signature)
                                                <img src="{{ $data->signature }}" alt="Signature" class="img-fluid">
                                            @else
                                                <p>N/A</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Checklist Legend -->
                                    <div class="top-0 end-0 bg-white p-3 border rounded shadow mt-4">
                                        <span style="padding: 10px">B : Bagus</span>
                                        <span style="padding: 10px">R : Repair</span>
                                        <span style="padding: 10px">G : Ganti</span>
                                        <span style="padding: 10px">PP: Perlu Perbaikan</span>
                                    </div>

                                    <!-- Item checklist table -->
                                    <div class="table-responsive mt-4">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>B</th>
                                                    <th>R</th>
                                                    <th>G</th>
                                                    <th>PP</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data->details as $detail)
                                                    <tr>
                                                        <td>{{ $detail->item_check }}</td>
                                                        <td>{{ $detail->B ? 'V' : '' }}</td>
                                                        <td>{{ $detail->R ? 'V' : '' }}</td>
                                                        <td>{{ $detail->G ? 'V' : '' }}</td>
                                                        <td>{{ $detail->PP ? 'V' : '' }}</td>
                                                        <td>{{ $detail->remarks ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Yearly Schedule -->
                                    <h3>Yearly Schedule</h3>
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
                                                        @else

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
            </section>
        </div>
    </div>
</main>
@endsection
