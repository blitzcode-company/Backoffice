<?php
namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlyRegistrationsChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(array $months, array $data): \ArielMejiaDev\LarapexCharts\LineChart
    {
        return $this->chart->lineChart()
            ->setTitle('Usuarios Registrados por Mes')
            ->setSubtitle('Tendencia de crecimiento de usuarios')
            ->addData('Usuarios Registrados', $data)
            ->setXAxis($months)
            ->setColors(['#008FFB']);
    }
}
