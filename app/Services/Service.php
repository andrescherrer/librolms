<?php

namespace App\Services;

use Illuminate\Http\Request;

abstract class Service
{
    abstract public function filter(Request $request);
}