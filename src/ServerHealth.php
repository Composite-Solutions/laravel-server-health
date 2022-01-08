<?php

namespace Composite\ServerHealth;

class ServerHealth
{


    public function checkHealth()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return 'This is a Windows server, this package only works on Linux.';
        } else {
            $services = $this->checkServices();
            $serverUptime = $this->getServerUptime();
            $memoryUsage = $this->getMemoryUsage();
            $diskSpace = $this->getDiskSpace();


            return response($this->healthService->health());

            return [
                "services" => $services,
                "serverUptime" => $serverUptime,
                "memoryUsage" => $memoryUsage,
                "diskSpace" => $diskSpace
            ];
        }
    }

    private function checkServices()
    {
        $timeout = "1";

        //set service checks
        /*
        The script will open a socket to the following service to test for connection.
        Does not test the functionality, just the ability to connect
        Each service can have a name, port and the Unix domain it run on (default to localhost)
        */
        $services = array();
        $services[] = array("port" => "80", "service" => "Web server", "ip" => "", "status" => "");
        $services[] = array("port" => "21", "service" => "FTP", "ip" => "", "status" => "");
        $services[] = array("port" => "3306", "service" => "MYSQL", "ip" => "", "status" => "");
        $services[] = array("port" => "22", "service" => "Open SSH", "ip" => "", "status" => "");
        $services[] = array("port" => "80", "service" => "Internet Connection", "ip" => "google.com", "status" => "");

        //begin table for status
        foreach ($services as $service) {
            if ($service['ip'] == "") {
                $service['ip'] = "localhost";
            }
            $fp = @fsockopen($service['ip'], $service['port'], $errno, $errstr, $timeout);
            if (!$fp) {
                $service['status'] = "Offline";
            } else {
                $service['status'] = "Online";
                fclose($fp);
            }
        }
        return $services;
    }


    private function getServerUptime()
    {
        //GET SERVER LOADS
        $loadresult = @exec('uptime');
        preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/", $loadresult, $avgs);

        //GET SERVER UPTIME
        $uptime = explode(' up ', $loadresult);
        $uptime = explode(',', $uptime[1]);
        return $uptime[0] . ', ' . $uptime[1];
    }

    private function getMemoryUsage()
    {
        //Get ram usage
        $total_mem = preg_split('/ +/', @exec('grep MemTotal /proc/meminfo'));
        $total_mem = $total_mem[1];
        $free_mem = preg_split('/ +/', @exec('grep MemFree /proc/meminfo'));
        $cache_mem = preg_split('/ +/', @exec('grep ^Cached /proc/meminfo'));
        $free_mem = $free_mem[1] + $cache_mem[1];

        return [
            "total_memory" => $this->getSymbolByQuantity($total_mem),
            "free_memory" => $this->getSymbolByQuantity($free_mem)
        ];
    }

    private function getDiskSpace()
    {
        $disks = array();
        /*
        * The disks array list all mountpoint you wan to check freespace
        * Display name and path to the moutpoint have to be provide, you can
        */
        $disks[] = array("name" => "local", "path" => getcwd());
        $disk_space = 0;
        $disk_free = 0;
        $max = 5;
        foreach ($disks as $disk) {
            if (strlen($disk["name"]) > $max)
                $max = strlen($disk["name"]);
        }

        foreach ($disks as $disk) {
            $disk_space += disk_total_space($disk["path"]);
            $disk_free += disk_free_space($disk["path"]);
        }

        return [
            "disk_space" => $this->getSymbolByQuantity($disk_space),
            "disk_free" => $this->getSymbolByQuantity($disk_free)
        ];
    }

    private function getSymbolByQuantity($bytes)
    {
        $symbol = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
        $exp = floor(log($bytes) / log(1024));

        return sprintf('%.2f' . $symbol[$exp] . '', ($bytes / pow(1024, floor($exp))));
    }
}
