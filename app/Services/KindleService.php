<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Process;

class KindleService
{
    public function run($command)
    {
        $ip = config('kindle.ip');

        if (!$ip)
            throw new Exception('Kindle IP is not set. Please set an IP in the .env file before running Kindle commands');

        $command = base_path('scripts/run_on_kindle.sh') . ' ' . $ip . ' ' . $command;

        $result = Process::run($command)->throw();

        if ($result->successful())
            return $result->output();
    }
}
