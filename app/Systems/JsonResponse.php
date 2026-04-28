<?php

declare(strict_types=1);

namespace App\Systems;

final class JsonResponse extends Response
{
    public function __construct(array $data, int $status = 200)
    {
        parent::__construct(json_encode($data) ?: '', $status);

        $this->header('Content-Type', 'application/json');
    }
}
