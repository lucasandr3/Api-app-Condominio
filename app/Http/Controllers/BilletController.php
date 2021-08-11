<?php

namespace App\Http\Controllers;

use App\Repositories\BilletRepository;
use Illuminate\Http\Request;

class BilletController extends Controller
{
    private $billet;

    public function __construct(BilletRepository $billet)
    {
        $this->billet = $billet;
    }

    public function getAll(Request $request)
    {
        $property = $request->input('property');
        return $this->billet->billets($property);
    }
}
