<?php
namespace App\Http\Controllers;

use App\Repositories\DocRepository;

class DocController extends Controller
{
    private $documents;

    public function __construct(DocRepository $documents)
    {
        $this->documents = $documents;
    }

    public function getAll(): array
    {
        return $this->documents->documents();
    }
}
