<?php

namespace App\Http\Controllers;

use App\Repositories\UnitRepository;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    private $unit;

    public function __construct(UnitRepository $unit)
    {
        $this->unit = $unit;
    }

    public function getInfo($id): array
    {
        return $this->unit->unitInfo($id);
    }

    public function addPerson($id_unit, Request $request): array
    {
        $data = $request;
        return $this->unit->newPerson($id_unit, $data);
    }

    public function addVehicle($id_unit, Request $request): array
    {
        $data = $request;
        return $this->unit->newVehicle($id_unit, $data);
    }

    public function addpet($id_unit, Request $request): array
    {
        $data = $request;
        return $this->unit->newPet($id_unit, $data);
    }

    public function removeperson($id_unit, Request $request): array
    {
        $id_person = $request->input('id');
        return $this->unit->delPerson($id_unit, $id_person);
    }

    public function removevehicle($id_unit, Request $request): array
    {
        $id_vehicle = $request->input('id');;
        return $this->unit->delVehicle($id_unit, $id_vehicle);
    }

    public function removepet($id_unit, Request $request): array
    {
        $id_pet = $request->input('id');
        return $this->unit->delPet($id_unit, $id_pet);
    }
}
