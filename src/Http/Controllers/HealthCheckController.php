<?php

namespace Composite\ServerHealth\Http\Controllers;
use PragmaRX\Health\Http\Controllers\Health;

class HealthCheckController extends Health
{
    public function index()
    {
        return $this->check();
    }
}
