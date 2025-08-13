<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\DonutChart;

class CommentReportCategoryChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(array $data, array $labels): DonutChart
    {
        $floatData = array_map('floatval', $data);
        return $this->chart->donutChart()
            ->setTitle('Reportes de Comentarios por Categoría')
            ->setSubtitle('Distribución de tipos de problemas reportados en comentarios')
            ->setLabels($labels)
            ->setColors(['#FFCD56', '#4BC0C0', '#9966FF', '#FF8F33', '#1ABC9C'])
            ->addData($floatData); // <--- Usa los datos convertidos a float
    }
}
