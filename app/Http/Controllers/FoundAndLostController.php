<?php

namespace App\Http\Controllers;

use App\Repositories\FoundAndLostRepository;
use Illuminate\Http\Request;

class FoundAndLostController extends Controller
{
    public function getAll(FoundAndLostRepository $foundAndLost): array
    {
        return $foundAndLost->foundAndLost();
    }

    public function insert(Request $request, FoundAndLostRepository $foundAndLost): array
    {
        $data = $request;
        return $foundAndLost->newFoundAndLost($data);
    }

    public function update($id, Request $request, FoundAndLostRepository $foundAndLostRepository): array
    {
        $data = $request;
        return $foundAndLostRepository->updateFoundAndLost($id, $data);
    }
}
