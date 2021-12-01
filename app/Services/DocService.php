<?php

namespace App\Services;

use App\Interfaces\Repositories\DocRepositoryInterface;
use App\Interfaces\Services\DocServiceInterface;

class DocService implements DocServiceInterface
{
    private $repository;

    public function __construct(DocRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllDocuments()
    {
       return $this->repository->documents();
    }
}
