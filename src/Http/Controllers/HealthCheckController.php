<?php

namespace Composite\ServerHealth\Http\Controllers;

use Composite\ServerHealth\LogReader;
use Composite\ServerHealth\ServerHealth;
use Exception;
use Illuminate\Support\Facades\Request;
use PragmaRX\Health\Http\Controllers\Health;

class HealthCheckController extends Health
{
    public function index(Request $request)
    {
        $content = Request::all();
        try {
            $serverStatus = (new ServerHealth())->checkHealth();
            $appStatus = $this->check();

            if (isset($content['date'])) {
                $logs =  (new LogReader(['date' => $content['date']]))->get();
            } else {
                $logs =  (new LogReader())->get();
            }
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
