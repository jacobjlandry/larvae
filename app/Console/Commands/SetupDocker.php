<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupDocker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // TODO set up copy of default compose configs and yaml
    }
}
