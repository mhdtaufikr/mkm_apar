@extends('layouts.master')

@section('content')
<main>
    <!-- Page header -->
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-0">
               {{--  <h2 class="text-white">APAR Information - {{ $data->type ?? 'N/A' }}</h2> --}}
            </div>
        </div>
    </header>

    <!-- Main page content -->
    <div class="container-fluid px-4 mt-n10">
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="mb-3 col-sm-12">
                            <form action="{{ url('/checksheet/store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input name="no_apar" hidden type="text" class="form-control" value="{{ $data->no_apar ?? 'N/A' }}" >
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">APAR Checksheet - {{ $data->no_apar ?? 'N/A' }}</h3>
                                        <div>

                                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                Submit
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <strong><label class="form-label">APAR Type</label></strong>
                                                     <P>{{ $data->type ?? 'N/A' }}</P>
                                                 </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <strong> <label class="form-label">APAR Number</label></strong>
                                                     <P>{{ $data->no_apar ?? 'N/A' }}</P>
                                                 </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <strong>  <label class="form-label">Location</label></strong>
                                                      <P>{{ $data->location ?? 'N/A' }}</P>
                                                  </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <strong>  <label class="form-label">Year</label></strong>
                                                      <P>{{ $data->year ?? 'N/A' }}</P>
                                                  </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <strong>  <label class="form-label">Group</label></strong>
                                                          <P>{{ $data->group == '1' ? 'Stamping' : 'Engine' }}</P>
                                                      </div>
                                            </div>
                                        </div>
                                        <!-- Display APAR information -->






                                        {{-- <!-- Display dropdown items -->
                                        <div class="mb-3">
                                            <label class="form-label">Select Item</label>
                                            <select name="selected_item" class="form-control">
                                                @foreach ($item as $dropdown)
                                                    <option value="{{ $dropdown->name_value }}">{{ $dropdown->name_value }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}

                                        <!-- Checklist Legend -->
                                        <div class="top-0 end-0 bg-white p-3 border rounded shadow">
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
                                                        <th>Spec</th>
                                                        <th>B</th>
                                                        <th>G</th>
                                                        <th>R</th>
                                                        <th>PP</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $item)
                                                    <input type="hidden" name="items[{{ $item['name_value'] }}]" value="{{ $item['name_value'] }}">
                                                        <tr>
                                                            <td>{{ $item['name_value'] }}</td>
                                                            <td>{{ $item['code_format'] }}</td>

                                                            <td><input type="checkbox" class="checkbox" name="items[{{ $item['name_value'] }}][B]" value="1" {{ $item['B'] == 1 ? 'checked' : '' }}></td>
                                                            <td><input type="checkbox" class="checkbox" name="items[{{ $item['name_value'] }}][R]" value="1" {{ $item['R'] == 1 ? 'checked' : '' }}></td>
                                                            <td><input type="checkbox" class="checkbox" name="items[{{ $item['name_value'] }}][G]" value="1" {{ $item['G'] == 1 ? 'checked' : '' }}></td>
                                                            <td><input type="checkbox" class="checkbox" name="items[{{ $item['name_value'] }}][PP]" value="1" {{ $item['PP'] == 1 ? 'checked' : '' }}></td>

                                                            <td><input type="text" name="items[{{ $item['name_value'] }}][remarks]" value="{{ $item['remarks'] }}"></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Modal for input details -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Input Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="pic" class="form-label">User</label>
                                                        <input type="text" class="form-control" id="pic" name="pic" value="{{ old('pic', $data->pic ?? '') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="remarks" class="form-label">Remarks</label>
                                                        <textarea class="form-control" id="remarks" name="remarks">{{ old('remarks', $data->remark ?? '') }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="img" class="form-label">Image</label>
                                                        <input type="file" class="form-control" id="img" name="img">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="signature" class="form-label">Signature</label>
                                                        <canvas id="signature-pad" class="signature-pad" width="400" height="200" style="border: 1px solid #000;"></canvas>
                                                        <input type="hidden" id="signature-data" name="signature">
                                                        <button type="button" class="btn btn-danger" id="clear-signature">Clear</button>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" id="oneClickButton">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<!-- JavaScript for datatables, signature pad, and checkbox logic -->
<script>
    $(document).ready(function () {
        $("#tableUser").DataTable({
            responsive: true,
            lengthChange: false,
            autoWidth: false
        });
    });

    // Uncheck other checkboxes in the same row when one is selected
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    const row = this.parentElement.parentElement;
                    row.querySelectorAll('.checkbox').forEach(otherCheckbox => {
                        if (otherCheckbox !== this) otherCheckbox.checked = false;
                    });
                }
            });
        });
    });

    // Signature Pad functionality
    document.addEventListener('DOMContentLoaded', function () {
        const signaturePad = new SignaturePad(document.getElementById('signature-pad'));
        const clearButton = document.getElementById('clear-signature');
        const signatureInput = document.getElementById('signature-data');

        clearButton.addEventListener('click', function () {
            signaturePad.clear();
        });

        document.getElementById('oneClickButton').addEventListener('click', function (event) {
            if (signaturePad.isEmpty()) {
                alert("Please provide a signature.");
                event.preventDefault();
            } else {
                const dataUrl = signaturePad.toDataURL('image/png');
                signatureInput.value = dataUrl;
            }
        });
    });
</script>
@endsection
