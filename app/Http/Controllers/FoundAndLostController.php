<?php

namespace App\Http\Controllers;

use App\Repositories\FoundAndLostRepository;
use Illuminate\Http\Request;

class FoundAndLostController extends Controller
{
    private $foundAndLost;

    public function __construct(FoundAndLostRepository $foundAndLost)
    {
        $this->foundAndLost = $foundAndLost;
    }

    public function getAll(): array
    {
        return $this->foundAndLost->foundAndLost();
    }

    public function insert(Request $request): array
    {
        $data = $request;
        return $this->foundAndLost->newFoundAndLost($data);
    }

    public function update($id, Request $request): array
    {
        $data = $request;
        return $this->foundAndLost->updateFoundAndLost($id, $data);
    }
}
