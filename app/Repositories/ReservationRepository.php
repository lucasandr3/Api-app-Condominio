<?php
namespace App\Repositories;

use App\Models\Area;
use Carbon\Carbon;

class ReservationRepository
{
    public function reservations(): array
    {
        $areas = Area::where('allowed', 1)->get();

        // função pega os dias disponiveis formata e retorna
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
            $dayGroups[] = $daysHelper[end($daysList)];

            // juntando as datas
            $dates = '';
            $close = 0;
            foreach ($dayGroups as $group) {
                if($close === 0) {
                    $dates .= $group;
                } else {
                    $dates .= '-'.$group.',';
                }
                $close = 1 - $close;
            }
            $dates = explode(',', $dates);
            array_pop($dates);

            // adicionando o time
            $start = Carbon::parse($area['start_time'])->format('H:i');
            $end   = Carbon::parse($area['end_time'])->format('H:i');

            foreach ($dates as $key => $value) {
                $dates[$key] .= ' '.$start.' às '.$end;
            }

            $list[] = [
                'id' => $area['id'],
                'cover' => asset('storage/'.$area['cover']),
                'title' => $area['title'],
                'dates' => $dates
            ];
        }

        return ['error' => '', 'list' => $list];
    }
}
