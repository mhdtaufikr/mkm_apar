<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PmFormHead;
use App\Models\AparInformations;
use App\Models\Dropdown;
use App\Models\PmFormDetail;

class HomeController extends Controller
{
    public function index()
    {
        // Get the list of all APARs
        $aparList = AparInformations::select('id', 'no_apar', 'location')
            ->with(['checks' => function($query) {
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
            }])
            ->get()
            ->map(function ($apar) {
                $apar->checked_this_month = $apar->checks->isNotEmpty();
                $apar->last_checked_date = $apar->checks->isNotEmpty() ? $apar->checks->first()->date->format('d/m/Y') : null;
                $apar->status = $apar->checked_this_month ? 'Checked' : 'Not Checked';
                return $apar;
            });

        // Prepare data for the chart (count of checked APARs this month)
        $checkedCount = $aparList->where('checked_this_month', true)->count();
        $notCheckedCount = $aparList->count() - $checkedCount;

        $chartData = [
            ['apar' => 'Checked', 'checks' => $checkedCount],
            ['apar' => 'Not Checked', 'checks' => $notCheckedCount]
        ];

        return view('home.index', compact('aparList', 'chartData'));
    }


}
