<?php

namespace Composite\ServerHealth\Http\Controllers;

use Composite\ServerHealth\ServerHealth;
use Exception;
use PragmaRX\Health\Http\Controllers\Health;

class HealthCheckController extends Health
{
    public function index()
    {
        try {
            $serverStatus = (new ServerHealth())->checkHealth();
            $appStatus = $this->check();
            return [
                "serverStatus" => $serverStatus,
                "appStatus" => $appStatus
            ];
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }
}
