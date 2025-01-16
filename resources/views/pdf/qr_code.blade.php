<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APAR QR Codes</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Add styles for formatting -->
    <style>
        body {
            font-family: arial, helvetica, sans-serif;
            width: 7cm;
            height: 3cm;
            margin: 0.10000in 0.00000in 0.00000in 0.00000in;
            font-size: 9pt;
        }

        .plate {
            width: 7cm;
            height: 3cm;
            overflow: hidden;
            box-sizing: border-box;
        }

        #header {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center; /* Center header text */
        }

        #qrCode img {
            width: auto;
            height: auto;
            margin-left: auto; /* Align the image to the right by pushing it to the left as much as possible */
        }

        .plate td {
            padding: 0;
            padding-left: 3px; /* Add padding to table cells */
        }

        .qr_img {
            width: 0.40866in;
            height: 0.40866in;
            float: left;
            padding-right: .10in;
        }

        .qr_img img {
            width: 100%;
            height: 100%;
            margin-top: -6.9%;
            margin-left: -6.9%;
            padding-bottom: .04in;
        }

        .qr_text {
            width: calc(7cm - 0.40866in);
            height: 3cm;
            padding-top: 0.00000in;
            font-family: arial, helvetica, sans-serif;
            font-size: 6;
            padding-right: .01in;
            overflow: hidden !important;
            word-wrap: break-word;
            word-break: break-all;
            background-color: aqua;
            box-sizing: border-box;
        }

        .pull-left {
            padding-left: 5mm;
        }

        .next-padding {
            margin: 0.10000in 0.00000in 0.00000in 0.00000in;
        }
        #additionalInfo {
            font-size: 10px;
            text-align: left; /* Center additional info text */
        }
        @page { margin-left: -2;
        margin-top: -2; }
    </style>
</head>

<body>
    @foreach ($assets as $apar)
    <table class="table table-bordered plate custom-table">
        <tbody>
            <tr>
                <td style="width:30%" id="qrCode">
                    <img src="data:image/png;base64,{{ $apar->qr_code }}" alt="QR Code for Asset ID: {{ $apar->id }}">
                </td>
                <td style="width:70%" id="additionalInfo">
                    <h3 style="margin-bottom: 0; margin-top:25px">APAR PT.MKM</h3>
                    <h1 style="margin-bottom: 0">{{ $apar->no_apar }}</h1>
                    <p style="margin-bottom: 0">{{ $apar->type }}</p>
                    <p style="margin-bottom: 0"><strong>{{ $apar->location }}</strong> </p>

                </td>
            </tr>
        </tbody>
    </table>
    <div style="page-break-before: always;"></div>
    @endforeach
</body>

</html>
