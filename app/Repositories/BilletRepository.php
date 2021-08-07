<?php
namespace App\Repositories;

use App\Models\Billet;
use App\Models\Unit;

class BilletRepository
{
    public function billets($property): array
    {
        if($property) {

            $user = auth()->user();

            $unit = Unit::where('id', $property)
                ->where('id_owner', $user['id'])
                ->count();

            if($unit > 0) {
                $billets = Billet::where('id_unit', $property)->get();

                foreach ($billets as $billetKey => $billetValue) {
                    $billets[$billetKey]['fileurl'] = asset('storage/'.$billetValue['fileurl']);
                }

            } else {
                return ['error' => 'Essa Unidade não é sua.'];
            }

        } else {
            return ['error' => 'Você deve informar a propriedade.'];
        }

        return ['error' => '', 'list' => $billets];
    }
}
