<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\DonutChart;

class NotificationStatusChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(int $readCount, int $unreadCount): DonutChart
    {
        return $this->chart->donutChart()
            ->setTitle('Uso de Notificaciones')
            ->setSubtitle('Proporción de notificaciones leídas vs. no leídas')
            ->addData([$readCount, $unreadCount])
            ->setLabels(['Leídas', 'No Leídas'])
            ->setColors(['#4CAF50', '#FFC107']);
    }
}
