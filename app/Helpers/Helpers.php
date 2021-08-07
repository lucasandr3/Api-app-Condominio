<?php

namespace App\Helpers;


class Helpers
{
    public static function daysAvailable($areas)
    {
        $daysHelper = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

        foreach ($areas as $area) {
            $daysList = explode(',', $area['days']);

            $dayGroups = [];

            // adiciona o primeiro dia
            $lastDay = intval(current($daysList));
            $dayGroups[] = $daysHelper[$lastDay];
            array_shift($daysList);

            // adicionando dias relevantes
            foreach ($daysList as $day) {
                if (intval($day) != $lastDay + 1) {
                    $dayGroups[] = $daysHelper[$lastDay];
                    $dayGroups[] = $daysHelper[$day];
                }
                $lastDay = intval($day);
            }

            // adiciona o ultimo dia
            return $dayGroups[] = $daysHelper[end($daysList)];

            // juntando as datas
            //$close = 0;
        }
    }
}
