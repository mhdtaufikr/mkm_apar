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
        // Get the list of all APARs with their monthly check information
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

        // Prepare data for APAR check count
        $checkedCount = $aparList->where('checked_this_month', true)->count();
        $notCheckedCount = $aparList->count() - $checkedCount;
        $chartData = [
            ['apar' => 'Checked', 'checks' => $checkedCount],
            ['apar' => 'Not Checked', 'checks' => $notCheckedCount]
        ];

        // Planned and Actual Checks for each month (assuming 12 months)
        $plannedData = array_fill(0, 12, $aparList->count()); // Planned: Each APAR checked once per month

        // Gather actual data per month for the current year
        $actualData = PMFormHead::selectRaw('MONTH(date) as month, COUNT(id) as count')
            ->whereYear('date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Format actual data into a 12-month array
        $actualData = array_replace(array_fill(0, 12, 0), $actualData);

        // Calculate percentage accuracy trend
        $trendData = [];
        foreach ($plannedData as $index => $plannedQty) {
            $actualQty = $actualData[$index] ?? 0;
            $trendData[$index] = $plannedQty ? ($actualQty / $plannedQty) * 100 : 0;
        }

        return view('home.index', compact('aparList', 'chartData', 'plannedData', 'actualData', 'trendData'));
    }



}
