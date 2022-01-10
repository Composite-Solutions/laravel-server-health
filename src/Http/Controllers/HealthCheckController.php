<?php

namespace Composite\ServerHealth\Http\Controllers;

use Composite\ServerHealth\ServerHealth;
use PragmaRX\Health\Http\Controllers\Health;

class HealthCheckController extends Health
{
    public function index()
    {
        $serverStatus = (new ServerHealth())->checkHealth();
        $appStatus = $this->check();
        return [
            "serverStatus" => $serverStatus,
            "appStatus" => $appStatus
        ];
    }
}
