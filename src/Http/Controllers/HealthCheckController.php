<?php

namespace Composite\ServerHealth\Http\Controllers;

use Composite\ServerHealth\LogReader;
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
            $logs = (new LogReader())->get();
            return [
                "serverStatus" => $serverStatus,
                "appStatus" => $appStatus,
                "logs" => $logs,
            ];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
