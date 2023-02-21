<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Butterfly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:butterfly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply and test pipelines, endpoints, and deployment processes for a new site';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        //
    }
}
