<?php
namespace App\Repositories;

use App\Models\FoundAndLost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FoundAndLostRepository
{
    public function foundAndLost(): array
    {
        $lost = FoundAndLost::where('status', 'LOST')
            ->orderBy('datecreated', 'DESC')
            ->orderBy('id', 'DESC')
        ->get();

        foreach ($lost as $lostKey => $lostValue) {
            $lost[$lostKey]['datecreated'] = Carbon::parse($lostValue['datecreated'])->format('d/m/Y');
            $lost[$lostKey]['photo'] = asset('storage/'.$lostValue['photo']);
        }

        $recovered = FoundAndLost::where('status', 'RECOVERED')
            ->orderBy('datecreated', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($recovered as $recoveredKey => $recoveredValue) {
            $recovered[$recoveredKey]['datecreated'] = Carbon::parse($recoveredValue['datecreated'])->format('d/m/Y');
            $recovered[$recoveredKey]['photo'] = asset('storage/'.$recoveredValue['photo']);
        }

        return ['error' => '', 'lost' => $lost, 'recovered' => $recovered];
    }

    public function newFoundAndLost($data): array
    {
        $validator = Validator::make($data->all(), [
            'description' => 'required',
            'where' => 'required',
            'photo' => 'required|file|mimes:jpg,png'
        ]);

        if(!$validator->fails()) {

            $file = $data->file('photo')->store('public');
            $file = explode('public/', $file);
            $photo = $file[1];

            $newLost = new FoundAndLost();
            $newLost->status = 'LOST';
            $newLost->photo = $photo;
            $newLost->description = $data->input('description');
            $newLost->where = $data->input('where');
            $newLost->datecreated = date('Y-m-d');
            $newLost->save();

        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => ''];
    }

    public function updateFoundAndLost($id, $data): array
    {
        $status = $data->input('status');
        if($status && in_array($status, ['LOST', 'RECOVERED'])) {

            $item = FoundAndLost::find($id);
            if($item) {
                $item->status = $status;
                $item->save();
            } else {
                return ['erro' => 'Produto inexistente'];
            }

        } else {
            return ['error' => 'Status não existe'];
        }

        return ['error' => ''];
    }
}
