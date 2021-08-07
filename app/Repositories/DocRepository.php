<?php
namespace App\Repositories;

use App\Models\Doc;

class DocRepository
{
    public function documents(): array
    {
        $docs = Doc::all();

        foreach ($docs as $docKey => $docValue) {
            $docs[$docKey]['fileurl'] = asset('storage/'.$docValue['fileurl']);
        }

        return ['error' => '', 'list' => $docs];
    }
}
