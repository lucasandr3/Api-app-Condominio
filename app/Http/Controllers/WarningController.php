<?php

namespace App\Http\Controllers;

use App\Repositories\WarningRepository;
use Illuminate\Http\Request;

class WarningController extends Controller
{
    private $warning;

    public function __construct(WarningRepository $warning)
    {
        $this->warning = $warning;
    }

    public function getMyWarnings(Request $request): array
    {
        $property = $request->input('property');
        return $this->warning->warnings($property);
    }

    public function addWarningFile(Request $request): array
    {
        $data = $request;
        return $this->warning->warningFile($data);
    }

    public function setWarning(Request $request): array
    {
        $data = $request;
        return $this->warning->newWarning($data);
    }
}
