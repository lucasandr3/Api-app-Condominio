<?php

namespace App\Repositories;

use App\Interfaces\Repositories\DocRepositoryInterface;
use App\Models\Doc;

class DocRepository implements DocRepositoryInterface
{
    public function documents(): array
    {
        $docs = Doc::all()->toArray();

        $docs = array_map(function ($doc) {
            $doc['fileurl'] = asset('storage/' . $doc['fileurl']);
            return $doc;
        }, $docs);

        return ['error' => '', 'list' => $docs];
    }
}
