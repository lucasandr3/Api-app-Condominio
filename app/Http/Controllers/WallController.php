<?php

namespace App\Http\Controllers;

use App\Repositories\WallRepository;
use Illuminate\Http\Request;

class WallController extends Controller
{
    public function getAll(WallRepository $wall): array
    {
        return $wall->walls();
    }

    public function like(Request $request, WallRepository $wall): array
    {
        return $wall->likeWall($request);
    }
}
