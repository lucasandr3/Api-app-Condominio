<?php

namespace App\Http\Controllers;

use App\Repositories\UnitRepository;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function getInfo($id, UnitRepository $unit): array
    {
        return $unit->unitInfo($id);
    }

    public function addPerson($id_unit, Request $request, UnitRepository $unit): array
    {
        $data = $request;
        return $unit->newPerson($id_unit, $data);
    }

    public function addVehicle($id_unit, Request $request, UnitRepository $unit): array
    {
        $data = $request;
        return $unit->newVehicle($id_unit, $data);
    }

    public function addpet($id_unit, Request $request, UnitRepository $unit): array
    {
        $data = $request;
        return $unit->newPet($id_unit, $data);
    }

    public function removeperson($id_unit, Request $request, UnitRepository $unit): array
    {
        $id_person = $request->input('id');
        return $unit->delPerson($id_unit, $id_person);
    }

    public function removevehicle($id_unit, Request $request, UnitRepository $unit): array
    {
        $id_vehicle = $request->input('id');;
        return $unit->delVehicle($id_unit, $id_vehicle);
    }

    public function removepet($id_unit, Request $request, UnitRepository $unit): array
    {
        $id_pet = $request->input('id');
        return $unit->delPet($id_unit, $id_pet);
    }
}
