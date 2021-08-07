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
}
