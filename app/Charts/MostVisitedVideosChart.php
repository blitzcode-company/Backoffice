<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class MostVisitedVideosChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($labels, $data): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $currentMonth = Carbon::now()->locale('es')->monthName; // Obtener el nombre del mes actual en español

        return $this->chart->barChart()
            ->setTitle("Videos Más Visitados en $currentMonth")
            ->setXAxis($labels)
            ->addData('Visitas', $data);
    }
}
