<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Service
{
    abstract public function filter(Request $request): LengthAwarePaginator;

    abstract public function create(Request $request): bool;
}