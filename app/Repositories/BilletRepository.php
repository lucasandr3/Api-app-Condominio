<?php
namespace App\Repositories;

use App\Models\Billet;

class BilletRepository
{
    public function billets($data): array
    {
        if($data->input('property')) {

            $billets = Billet::where('id_unit', $data->input('property'))->get();

            foreach ($billets as $billetKey => $billetValue) {
                $billets[$billetKey]['fileurl'] = asset('storage/'.$billetValue['fileurl']);
            }

        } else {
            return ['error' => 'Você deve informar a propriedade.'];
        }

        return ['error' => '', 'list' => $billets];
    }
}
