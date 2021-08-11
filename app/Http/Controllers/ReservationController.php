<?php

namespace App\Http\Controllers;

use App\Repositories\ReservationRepository;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    private $reservations;

    public function __construct(ReservationRepository $reservations)
    {
        $this->reservations = $reservations;
    }

    public function getReservations(): array
    {
        return $$this->reservations->reservations();
    }

    public function getDisabledDates($id_area): array
    {
        return $$this->reservations->disabledDays($id_area);
    }

    public function setReservation($id_area, Request $request): array
    {
        $data = $request;
        return $$this->reservations->newReserve($id_area, $data);
    }

    public function getTimes($id_area, Request $request): array
    {
        $data = $request;
        return $$this->reservations->times($id_area, $data);
    }

    public function getMyReservations(Request $request): array
    {
        $data = $request;
        return $$this->reservations->allMyReservations($data);
    }

    public function delMyReservation($id_reserve): array
    {
        return $$this->reservations->removeReservation($id_reserve);
    }
}
