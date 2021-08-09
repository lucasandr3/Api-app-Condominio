<?php

namespace App\Repositories;

use App\Models\Area;
use App\Models\AreaDisabledDay;
use App\Models\Reservation;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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
                if ($close === 0) {
                    $dates .= $group;
                } else {
                    $dates .= '-' . $group . ',';
                }
                $close = 1 - $close;
            }
            $dates = explode(',', $dates);
            array_pop($dates);

            // adicionando o time
            $start = Carbon::parse($area['start_time'])->format('H:i');
            $end = Carbon::parse($area['end_time'])->format('H:i');

            foreach ($dates as $key => $value) {
                $dates[$key] .= ' ' . $start . ' às ' . $end;
            }

            $list[] = [
                'id' => $area['id'],
                'cover' => asset('storage/' . $area['cover']),
                'title' => $area['title'],
                'dates' => $dates
            ];
        }

        return ['error' => '', 'list' => $list];
    }

    public function disabledDays($id_area): array
    {
        $area = Area::find($id_area);

        if ($area) {

            // days disabled
            $disabledDays = AreaDisabledDay::where('id_area', $id_area)->get();
            foreach ($disabledDays as $disabledDay) {
                $list[] = $disabledDay['day'];
            }

            //days permitidos
            $allowedDays = explode(',', $area['days']);
            $offDays = [];

            for ($i = 0; $i < 7; $i++) {
                if (!in_array($i, $allowedDays)) {
                    $offDays[] = $i;
                }
            }

            // list days not permitidos
            $start = time();
            $end = strtotime('+3 months');

            for (
                $current = $start;
                $current < $end;
                $current = strtotime('+1 day', $current)
            ) {
                $wd = date('w', $current);
                if (in_array($wd, $offDays)) {
                    $list[] = date('Y-m-d', $current);
                }
            }

        } else {
            return ['error' => 'Está area não existe.'];
        }

        return ['error' => '', 'list' => $list];
    }

    public function newReserve($id_area, $data): array
    {
        $can = true;

        $validator = Validator::make($data->all(), [
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i:s',
            'property' => 'required'
        ]);

        if (!$validator->fails()) {

            $unit = Unit::find($data->input('property'));
            $area = Area::find($id_area);

            if ($unit && $area) {

                $weekDay = date('w', strtotime($data->input('date')));

                // verificar se esta dentro da disponibilidade
                $allowedDays = explode(',', $area['days']);
                if (!in_array($weekDay, $allowedDays)) {
                    $can = false;
                } else {
                    $start = strtotime($area['start_time']);
                    $end = strtotime('-1 hour', strtotime($area['end_time']));
                    $revTime = strtotime($data->input('time'));

                    if ($revTime < $start || $revTime > $end) {
                        $can = false;
                    }
                }

                // verificar se esta dentro dos disableddays
                $existingDisabledDay = AreaDisabledDay::where('id_area', $id_area)
                    ->where('day', $data->input('date'))
                    ->count();

                if ($existingDisabledDay > 0) {
                    $can = false;
                }

                // verificar se nao existe outra reserva no mesmo dia hora
                $existingReservations = Reservation::where('id_area', $id_area)
                    ->where('reservation_date', $data->input('date') . ' ' . $data->input('time'))
                    ->count();

                if ($existingReservations > 0) {
                    $can = false;
                }

                if ($can == true) {

                    $newReservation = new Reservation();
                    $newReservation->id_unit = $data->input('property');
                    $newReservation->id_area = $id_area;
                    $newReservation->reservation_date = $data->input('date') . ' ' . $data->input('time');
                    $newReservation->save();

                } else {
                    return ['error' => 'Reserva não permitida neste dia / horário'];
                }

            } else {
                return ['error' => 'Dados Incorretos.'];
            }

        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => ''];
    }

    public function times($id_area, $data): array
    {
        $can = true;

        $validator = Validator::make($data->all(), [
            'date' => 'required|date_format:Y-m-d',
        ]);

        if (!$validator->fails()) {

            $area = Area::find($id_area);

            if ($area) {

                // verificar se esta dentro dos disableddays
                $existingDisabledDay = AreaDisabledDay::where('id_area', $id_area)
                    ->where('day', $data->input('date'))
                    ->count();

                if ($existingDisabledDay > 0) {
                    $can = false;
                }

                $allowedDays = explode(',', $area['days']);
                $weekDay = date('w', strtotime($data->input('date')));

                if (!in_array($weekDay, $allowedDays)) {
                    $can = false;
                }

                if ($can) {
                    $start = strtotime($area['start_time']);
                    $end = strtotime($area['end_time']);
                    $times = [];

                    for (
                        $lastTime = $start;
                        $lastTime < $end;
                        $lastTime = strtotime('+1 hour', $lastTime)
                    ) {
                        $times[] = $lastTime;
                    }

                    foreach ($times as $time) {
                        $timeList[] = [
                            'id' => date('H:i:s', $time),
                            'title' => date('H:i', $time) . ' - ' . date('H:i:s', strtotime('+1 hour', $time))
                        ];
                    }

                    // removendo as reservas
                    $reservations = Reservation::where('id_area', $id_area)
                        ->whereBetween('reservation_date', [
                            $data->input('date') . ' 00:00:00',
                            $data->input('date') . ' 23:59:00'
                        ])
                        ->get();

                    foreach ($reservations as $reservation) {
                        $time = date('H:i:s', strtotime($reservation['reservation_date']));
                        $toRemove[] = $time;
                    }

                    foreach ($timeList as $timeItem) {
                        if (!in_array($timeItem['id'], $toRemove)) {
                            $listHours[] = $timeItem;
                        }
                    }

                }

            } else {
                return ['error' => 'Area inexistente'];
            }

        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => '', 'list' => $listHours];
    }

    public function allMyReservations($data): array
    {
        $list = [];
        if($data->input('property')) {

            $unit = Unit::find($data->input('property'));

            if($unit) {

                $reservations = Reservation::where('id_unit', $data->input('property'))
                    ->orderBy('reservation_date', 'DESC')
                ->get();

                foreach ($reservations as $reservation) {
                    $area = Area::find($reservation['id_area']);

                    $dateRev = Carbon::parse($reservation['reservation_date'])->format('d/m/Y H:i');
                    $afterTime = date('H:i', strtotime('+1 hour', strtotime($reservation['reservation_date'])));
                    $dateRev .= ' à '.$afterTime;

                    $list[] = [
                        'id' => $reservation['id'],
                        'id_area' => $reservation['id_area'],
                        'title' => $area['title'],
                        'cover' => asset('storage/'.$area['cover']),
                        'datereserved' => $dateRev
                    ];
                }

            } else {
                return ['error' => 'Esta propriedade não existe.'];
            }

        } else {
            return ['error' => 'Você Precisa informar uma propriedade'];
        }

        return ['error' => '', 'list' => $list];
    }

    public function removeReservation($id_reserve): array
    {
        $user = auth()->user();
        $reservation = Reservation::find($id_reserve);

        if($reservation) {

            $unit = Unit::where('id', $reservation['id_unit'])
                ->where('id_owner', $user['id'])
            ->count();

            if($unit > 0) {
                Reservation::find($id_reserve)->delete();
            } else {
                return ['error' => 'Está reserva não é sua.'];
            }

        } else {
            return ['error' => 'Está reserva não existe'];
        }

        return ['error' => ''];
    }
}
