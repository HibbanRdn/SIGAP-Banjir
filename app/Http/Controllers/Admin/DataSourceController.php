<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataSourceMonitoringService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DataSourceController extends Controller
{
    public function index(Request $request, DataSourceMonitoringService $monitoring): View
    {
        $filters = $request->only([
            'search',
            'module',
            'data_status',
            'source_type',
            'verification',
            'district',
        ]);

        return view('admin.data-sources.index', $monitoring->summary($filters));
    }
}
