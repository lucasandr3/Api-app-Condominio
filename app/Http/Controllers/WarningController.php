<?php

namespace App\Http\Controllers;

use App\Repositories\WarningRepository;
use Illuminate\Http\Request;

class WarningController extends Controller
{
    public function getMyWarnings(Request $request, WarningRepository $warning): array
    {
        $property = $request->input('property');
        return $warning->warnings($property);
    }

    public function addWarningFile(Request $request, WarningRepository $warning): array
    {
        $data = $request;
        return $warning->warningFile($data);
    }

    public function setWarning(Request $request, WarningRepository $warning): array
    {
        $data = $request;
        return $warning->newWarning($data);
    }
}
