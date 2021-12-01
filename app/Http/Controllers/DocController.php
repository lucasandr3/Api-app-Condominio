<?php
namespace App\Http\Controllers;

use App\Interfaces\Services\DocServiceInterface;
use App\Repositories\DocRepository;

class DocController extends Controller
{
    private $service;

    public function __construct(DocServiceInterface $service)
    {
        $this->service = $service;
    }

    public function getAll(): array
    {
        return $this->service->getAllDocuments();
    }
}
