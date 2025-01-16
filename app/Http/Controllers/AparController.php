<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PmFormHead;
use App\Models\AparInformations;
use App\Models\Dropdown;
use App\Models\PmFormDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;


class AparController extends Controller
{
    public function index(){

        // Retrieve all ChecksheetFormHead records sorted by the newest data

        // Retrieve all machines with their op_name and location
        $item = PmFormHead::with('aparInformation')->get();
        $apar = AparInformations::get();

        return view('apar.index',compact('item','apar'));
    }

    public function checksheet(Request $request)
{
    // Get the current month and year
    $currentMonth = now()->format('m');
    $currentYear = now()->format('Y');

    // Try to find the APAR information based on no_apar from $request->mechine
    $data = AparInformations::where('no_apar', $request->mechine)->first();

    if (!$data) {
        // Parse the URL and extract the ID
        $urlSegments = explode('/', $request->no_mechine); // Split the URL by '/'
        $id = end($urlSegments); // Get the last segment (ID)

        // Query the database using the extracted ID
        $data = AparInformations::where('id', $id)->first();
    }


    if (!$data) {
        // If still not found, redirect back with a failure message
        return redirect()->back()->with('failed', 'APAR information not found.');
    }

// Proceed with the found $data
// Your logic here...


    // Check if a record with the same apar_information_id and current month and year already exists in pm_form_heads
    $existingChecksheet = PmFormHead::where('apar_information_id', $data->id)
                                    ->whereMonth('date', $currentMonth)
                                    ->whereYear('date', $currentYear)
                                    ->exists();

    if ($existingChecksheet) {
        // Redirect back with an failed message
        return redirect()->back()->with('failed', 'This APAR has already been checked this month.');
    }

    // Proceed if no record exists for this month
    $items = Dropdown::where('category', 'Item')->get();

    return view('apar.checksheet', compact('data', 'items'));
}



    public function store(Request $request)
    {
        // Dump all request data for debugging (remove this in production)
        // dd($request->all());

        // Validate the incoming request data as needed
        $request->validate([
            'no_apar' => 'required|string',  // Assuming 'no_apar' is required to find 'apar_information_id'
            'pic' => 'required|string|max:45',
            'signature' => 'required|string',
            'items' => 'required|array',
        ]);

        // Query to get apar_information_id based on no_apar
        $aparInformation = AparInformations::where('no_apar', $request->input('no_apar'))->first();
        if (!$aparInformation) {
            return redirect()->back()->withErrors(['no_apar' => 'APAR not found with the given number.']);
        }
        $aparInformationId = $aparInformation->id;

        // Handle image upload if provided
        $imgPath = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('images');
            $file->move($destinationPath, $fileName);
            $imgPath = 'images/' . $fileName;
        }

        // Save to pm_form_heads table
        $pmFormHead = new PmFormHead();
        $pmFormHead->apar_information_id = $aparInformationId; // Store fetched apar_information_id
        $pmFormHead->signature = $request->input('signature');
        $pmFormHead->date = now(); // or $request->input('date') if date is provided in request
        $pmFormHead->pic = $request->input('pic');
        $pmFormHead->img = $imgPath;
        $pmFormHead->remarks = $request->remarks;
        $pmFormHead->save();

        // Get the ID of the newly created pm_form_heads record
        $idHeader = $pmFormHead->id;

        // Loop through each item in the 'items' array and save to pm_form_details table
        foreach ($request->input('items') as $itemName => $itemData) {
            $pmFormDetail = new PmFormDetail();
            $pmFormDetail->id_header = $idHeader;
            $pmFormDetail->item_check = $itemName;
            $pmFormDetail->B = isset($itemData['B']) ? (int)$itemData['B'] : 0;
            $pmFormDetail->R = isset($itemData['R']) ? (int)$itemData['R'] : 0;
            $pmFormDetail->G = isset($itemData['G']) ? (int)$itemData['G'] : 0;
            $pmFormDetail->PP = isset($itemData['PP']) ? (int)$itemData['PP'] : 0;
            $pmFormDetail->remarks = $itemData['remarks'] ?? null;
            $pmFormDetail->save();
        }

        // Redirect with a success message
        return redirect()->route('list')->with('status', 'Checksheet submitted successfully.');
    }

    public function detail($id)
{
    $id = decrypt($id);

    // Fetch the pm_form_head record and related pm_form_details
    $data = PmFormHead::with(['aparInformation', 'details'])->findOrFail($id);

    // Fetch all records for the same apar_information_id, grouped by month
    $yearlyRecords = PmFormHead::where('apar_information_id', $data->apar_information_id)
                                ->orderBy('date', 'asc')
                                ->get()
                                ->groupBy(function($record) {
                                    return \Carbon\Carbon::parse($record->date)->format('M'); // Group by abbreviated month name
                                });

    return view('apar.detail', compact('data', 'yearlyRecords'));
}


public function generatePdf($id)
{
    $id = decrypt($id);

    // Fetch the main record and related data
    $data = PmFormHead::with(['aparInformation', 'details'])->findOrFail($id);

    // Fetch all records for the same apar_information_id
    $yearlyRecords = PmFormHead::where('apar_information_id', $data->apar_information_id)
                                ->orderBy('date', 'asc')
                                ->get()
                                ->groupBy(function($record) {
                                    return \Carbon\Carbon::parse($record->date)->format('M'); // Group by abbreviated month name
                                });

    // Load the view and pass data to it
    $pdf = Pdf::loadView('apar.pdf', compact('data', 'yearlyRecords'));

    // Return the generated PDF as a stream
    return $pdf->download('APAR_Checksheet_' . $data->aparInformation->no_apar . '.pdf');
}

public function mstApar(Request $request)
{
    if ($request->ajax()) {
        $apar = AparInformations::select(['id', 'type', 'no_apar', 'location', 'year', 'group', 'created_at', 'updated_at'])->get();

        // Encrypt the ID
        foreach ($apar as $row) {
            $row->encrypted_id = encrypt($row->id);
        }

        return DataTables::of($apar)
            ->addIndexColumn() // Adds a virtual numbering column
            ->make(true); // Return the DataTable response
    }

    return view('master.index');
}

public function mstAparDetail($id)
{
    try {
        // Attempt to decrypt the ID
        $id = decrypt($id);
    } catch (\Exception $e) {
        // If decryption fails, assume it's a plain ID
        // Log or handle the error if needed
    }

    // Fetch the APAR information and related pm_form_head records
    $apar = AparInformations::with('checks')->findOrFail($id);

  // Fetch all records for the same apar_information_id in the current year, grouped by month
    $yearlyRecords = PmFormHead::where('apar_information_id', $apar->id)
    ->whereYear('date', Carbon::now()->year) // Filter by current year
    ->orderBy('date', 'asc')
    ->get()
    ->groupBy(function ($record) {
        return Carbon::parse($record->date)->format('M'); // Group by abbreviated month name
    });

    return view('master.detail', compact('apar', 'yearlyRecords'));
}


public function generateQrCodePdf()
{
    // Fetch 10 APAR records
    $aparRecords = AparInformations::limit(10)->get();

    // Generate QR codes for each APAR
    foreach ($aparRecords as $apar) {
        $apar->qr_code = base64_encode(\QrCode::size(120)
            ->margin(5)
            ->generate(url("mst/apar/detail/public/" . $apar->id)));
    }

    // Generate the PDF using the Blade template
    $pdf = PDF::loadView('pdf.qr_code', ['assets' => $aparRecords])->setPaper('a4', 'landscape');;

    // Return the PDF as a stream
    return $pdf->stream('apar_qr_codes.pdf');
}
public function mstAparDetailPublic($id)
{
    try {
        // Attempt to decrypt the ID
        $id = decrypt($id);
    } catch (\Exception $e) {
        // If decryption fails, assume it's a plain ID
        // Log or handle the error if needed
    }

    // Fetch the APAR information and related pm_form_head records
    $apar = AparInformations::with('checks')->findOrFail($id);

  // Fetch all records for the same apar_information_id in the current year, grouped by month
    $yearlyRecords = PmFormHead::where('apar_information_id', $apar->id)
    ->whereYear('date', Carbon::now()->year) // Filter by current year
    ->orderBy('date', 'asc')
    ->get()
    ->groupBy(function ($record) {
        return Carbon::parse($record->date)->format('M'); // Group by abbreviated month name
    });

    return view('master.public', compact('apar', 'yearlyRecords'));
}






    }
