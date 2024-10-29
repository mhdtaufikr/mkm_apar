<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>APAR Checksheet</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { margin: 20px; }
        .info-table { width: 100%; }
        .info-table th, .info-table td { vertical-align: top; padding: 10px; }
        .image, .signature { margin-top: 10px; text-align: center; }
        .image img, .signature img { width: 150px; height: 150px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table, .table th, .table td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
      <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="header">
        <h2>APAR Checksheet</h2>
        <p>APAR Number: {{ $data->aparInformation->no_apar ?? 'N/A' }} - ({{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }})        </p>
    </div>

    <div class="content">
        <table class="info-table">
            <tr>
                <td>
                    <p><strong>APAR Type:</strong> {{ $data->aparInformation->type ?? 'N/A' }}</p>
                    <p><strong>Location:</strong> {{ $data->aparInformation->location ?? 'N/A' }}</p>
                    <p><strong>Year:</strong> {{ $data->aparInformation->year ?? 'N/A' }}</p>
                    <p><strong>Group:</strong> {{ $data->aparInformation->group == '1' ? 'Stamping' : 'Engine' }}</p>
                    <p><strong>PIC:</strong> {{ $data->pic ?? 'N/A' }}</p>
                </td>
                <td>
                    <div class="image">
                        <strong>Image:</strong><br>
                        @if($data->img)
                            <img src="{{ public_path($data->img) }}" alt="Image">
                        @else
                            <p>No Image Available</p>
                        @endif
                    </div>


                </td>
                <td>
                    <div class="signature">
                        <strong>Signature:</strong><br>
                        @if($data->signature)
                            <img src="{{ $data->signature }}" alt="Signature">
                        @else
                            <p>No Signature Available</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>



    </div>

    <!-- Checklist Table -->
    <h3>Checklist</h3>
    <table class="table" style="font-size: 12px; width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 4px; text-align: left;">Description</th>
                <th style="padding: 4px; text-align: left;">B</th>
                <th style="padding: 4px; text-align: left;">R</th>
                <th style="padding: 4px; text-align: left;">G</th>
                <th style="padding: 4px; text-align: left;">PP</th>
                <th style="padding: 4px; text-align: left;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->details as $detail)
                <tr>
                    <td style="padding: 4px;" >{{ $detail->item_check }}</td>
                    <td style="padding: 4px;" >{{ $detail->B ? 'V' : '' }}</td>
                    <td style="padding: 4px;" >{{ $detail->R ? 'V' : '' }}</td>
                    <td style="padding: 4px;" >{{ $detail->G ? 'V' : '' }}</td>
                    <td style="padding: 4px;" >{{ $detail->PP ? 'V' : '' }}</td>
                    <td style="padding: 4px;" >{{ $detail->remarks ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

          <!-- Yearly Schedule Table -->
          <h3>Yearly Schedule</h3>
          <table class="table" style="font-size: 12px; width: 100%; border-collapse: collapse;">
              <thead>
                  <tr>
                      <th style="padding: 4px; text-align: left;">Month</th>
                      <th style="padding: 4px; text-align: left;">Dates</th>
                      <th style="padding: 4px; text-align: left;">Remarks</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                      <tr>
                          <td style="padding: 4px;">{{ $month }}</td>
                          <td style="padding: 4px;">
                            @if(isset($yearlyRecords[$month]))
                            *
                            {{ $yearlyRecords[$month]->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d'))->implode(', ') }}
                        @else
                            -
                        @endif

                          </td>
                          <td style="padding: 4px;">
                              @if(isset($yearlyRecords[$month]))
                                  {{ $yearlyRecords[$month]->pluck('remarks')->implode(', ') }}
                              @else
                                  -
                              @endif
                          </td>
                      </tr>
                  @endforeach
              </tbody>
          </table>

</body>
</html>
