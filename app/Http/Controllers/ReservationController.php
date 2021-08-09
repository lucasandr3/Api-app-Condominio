<?php

namespace App\Http\Controllers;

use App\Repositories\ReservationRepository;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function getReservations(ReservationRepository $reservations): array
    {
        return $reservations->reservations();
    }

    public function getDisabledDates($id_area, ReservationRepository $reservations): array
    {
        return $reservations->disabledDays($id_area);
    }

    public function setReservation($id_area, Request $request, ReservationRepository $reservations): array
    {
        $data = $request;
        return $reservations->newReserve($id_area, $data);
    }

    public function getTimes($id_area, Request $request, ReservationRepository $reservations): array
    {
        $data = $request;
        return $reservations->times($id_area, $data);
    }

    public function getMyReservations(Request $request, ReservationRepository $reservations): array
    {
        $data = $request;
        return $reservations->allMyReservations($data);
    }

    public function delMyReservation($id_reserve, ReservationRepository $reservations): array
    {
        return $reservations->removeReservation($id_reserve);
    }
}
