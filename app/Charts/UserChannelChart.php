<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class UserChannelChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($totalUsers, $contentCreators, $usersWithoutCanal, $premiumCreators, $nonPremiumCreators, $premiumWithoutCanal, $nonPremiumWithoutCanal): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $labels = ['Usuarios Totales', 'Creadores de Contenido', 'Usuarios sin Canal', 'Creadores Premium', 'Creadores No Premium', 'Usuarios Premium sin Canal', 'Usuarios No Premium sin Canal'];
        $data = [$totalUsers, $contentCreators, $usersWithoutCanal, $premiumCreators, $nonPremiumCreators, $premiumWithoutCanal, $nonPremiumWithoutCanal];
        $colors = $this->generateRandomColors(count($labels));

        return $this->chart->barChart()
            ->setTitle('Descripción general del estado del usuario')
            ->setXAxis($labels)
            ->setDataset([
                [
                    'name' => 'Usuarios',
                    'data' => $data
                ]
            ])
            ->setColors($colors); 
    }

    private function generateRandomColors($count)
    {
        $colors = [];

        for ($i = 0; $i < $count; $i++) {
            $colors[] = '#' . substr(md5(mt_rand()), 0, 6); 
        }

        return $colors;
    }
}
