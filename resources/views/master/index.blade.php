@extends('layouts.master')

@section('content')

<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-fluid px-4 mt-n10">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">List APAR</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('generate.qr.code.pdf') }}" class="btn btn-sm btn-primary" target="_blank">
                                            Generate QR Code PDF
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <!-- Alert success -->
                                            @if (session('status'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <strong>{{ session('status') }}</strong>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            @endif

                                            @if (session('failed'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <strong>{{ session('failed') }}</strong>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            @endif

                                            <!-- Validasi form -->
                                            @if (count($errors) > 0)
                                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                <ul>
                                                    <li><strong>Data Process Failed !</strong></li>
                                                    @foreach ($errors->all() as $error)
                                                    <li><strong>{{ $error }}</strong></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                            <!-- End validasi form -->
                                        </div>
                                        <div class="mb-3 col-sm-12">
                                            <div class="table-responsive">
                                                <table id="tableApar" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Apar</th>
                                                            <th>Date</th>
                                                            <th>Location</th>
                                                            <th>Year</th>
                                                            <th>Group</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Data will be populated by DataTables -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
</main>


<script>
    $(document).ready(function () {
    var table = $("#tableApar").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('mst.Apar') }}",
        "columns": [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", "orderable": false, "searchable": false },
            { "data": "no_apar", "name": "no_apar" },
            { "data": "created_at", "name": "created_at" },
            { "data": "location", "name": "location" },
            { "data": "year", "name": "year" },
            { "data": "group", "name": "group" }
        ]
    });

    // Add click event to redirect to detail route
    $('#tableApar tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        if (data) {
            window.location.href = "{{ url('/mst/apar/detail') }}/" + data.encrypted_id;
        }
    });
});

</script>



@endsection
