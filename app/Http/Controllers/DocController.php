<?php
namespace App\Http\Controllers;

use App\Repositories\DocRepository;

class DocController extends Controller
{
    public function getAll(DocRepository $documents): array
    {
        return $documents->documents();
    }
}
