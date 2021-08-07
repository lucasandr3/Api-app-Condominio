<?php
namespace App\Repositories;


use App\Models\Unit;
use App\Models\UnitPeople;
use App\Models\UnitPet;
use App\Models\UnitVehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UnitRepository
{
    public function unitInfo($id): array
    {
        $unit = Unit::find($id);

        if($unit) {

            $peoples = UnitPeople::where('id_unit', $id)->get();

            foreach ($peoples as $peopleKey => $peopleValue) {
                $peoples[$peopleKey]['birthdate'] = Carbon::parse($peopleValue['birthdate'])->format('d/m/Y');
            }

            $vehicles = UnitVehicle::where('id_unit', $id)->get();
            $pets = UnitPet::where('id_unit', $id)->get();

        } else {
            return ['error' => 'Propriedade inexistente'];
        }

        return ['error' => '', 'peoples' => $peoples, 'vehicles' => $vehicles, 'pets' => $pets];
    }

    public function newPerson($id_unit, $data): array {
        $validator = Validator::make($data->all(), [
            'name' => 'required',
            'birthdate' => 'required|date'
        ]);

        if(!$validator->fails()) {

            $newPerson = new UnitPeople();
            $newPerson->id_unit = $id_unit;
            $newPerson->name = $data->input('name');
            $newPerson->birthdate = $data->input('birthdate');
            $newPerson->save();

        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => ''];
    }

    public function newVehicle($id_unit, $data): array {
        $validator = Validator::make($data->all(), [
            'title' => 'required',
            'color' => 'required',
            'plate' => 'required',
        ]);

        if(!$validator->fails()) {

            $newVehicle = new UnitVehicle();
            $newVehicle->id_unit = $id_unit;
            $newVehicle->title = $data->input('title');
            $newVehicle->color = $data->input('color');
            $newVehicle->plate = $data->input('plate');
            $newVehicle->save();

        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => ''];
    }

    public function newPet($id_unit, $data): array {
        $validator = Validator::make($data->all(), [
            'name' => 'required',
            'race' => 'required',
        ]);

        if(!$validator->fails()) {

            $newPet = new UnitPet();
            $newPet->id_unit = $id_unit;
            $newPet->name = $data->input('name');
            $newPet->race = $data->input('race');
            $newPet->save();

        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => ''];
    }

    public function delPerson($id_unit, $id_person): array
    {
        if ($id_person) {

            UnitPeople::where('id', $id_person)
                ->where('id_unit', $id_unit)
            ->delete();

        } else {
            return ['error' => 'ID Inexistente'];
        }

        return ['error' => ''];
    }

    public function delVehicle($id_unit, $id_vehicle): array
    {
        if ($id_vehicle) {

            UnitVehicle::where('id', $id_vehicle)
                ->where('id_unit', $id_unit)
            ->delete();

        } else {
            return ['error' => 'ID Inexistente'];
        }

        return ['error' => ''];
    }

    public function delPet($id_unit, $id_pet): array
    {
        if ($id_pet) {

            UnitPet::where('id', $id_pet)
                ->where('id_unit', $id_unit)
            ->delete();

        } else {
            return ['error' => 'ID Inexistente'];
        }

        return ['error' => ''];
    }
}
