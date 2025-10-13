<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Video;
use App\Models\Blitzvideo\Etiqueta;
use Illuminate\Http\Request;

class StatsSummaryController extends Controller
{
    public function showSummary()
    {
        $totalUsers = User::whereNull('deleted_at')->count();
        $totalCanals = Canal::whereNull('deleted_at')->count();
        $totalVideos = Video::whereNull('deleted_at')->count();
        $totalEtiquetas = Etiqueta::count();

        return view('estadisticas', compact('totalUsers', 'totalCanals', 'totalVideos', 'totalEtiquetas'));
    }
}
