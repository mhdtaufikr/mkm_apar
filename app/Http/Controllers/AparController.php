<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PmFormHead;
use App\Models\AparInformations;

class AparController extends Controller
{
    public function index(){

        // Retrieve all ChecksheetFormHead records sorted by the newest data

        // Retrieve all machines with their op_name and location
        $item = PMFormHead::get();
        $apar = AparInformations::get();

        return view('apar.index',compact('item','apar'));
    }
}
