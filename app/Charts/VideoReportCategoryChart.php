<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\DonutChart;

class VideoReportCategoryChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(array $data, array $labels): DonutChart
    {
        // Asegúrate de que los datos sean floats explícitamente, ya que ApexCharts puede ser estricto.
        $floatData = array_map('floatval', $data);

        return $this->chart->donutChart()
            ->setTitle('Reportes de Videos por Categoría')
            ->setSubtitle('Distribución de tipos de problemas reportados en videos')
            ->setLabels($labels)
            ->setColors(['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'])
            ->addData($floatData); // <--- Usa los datos convertidos a float
    }
}
