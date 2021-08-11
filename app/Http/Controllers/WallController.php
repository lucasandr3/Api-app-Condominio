<?php

namespace App\Http\Controllers;

use App\Repositories\WallRepository;
use Illuminate\Http\Request;

class WallController extends Controller
{
    private $wall;

    public function __construct(WallRepository $wall)
    {
        $this->wall = $wall;
    }

    public function getAll(): array
    {
        return $this->wall->walls();
    }

    public function like(Request $request): array
    {
        $data = $request;
        return $this->wall->likeWall($data);
    }
}
