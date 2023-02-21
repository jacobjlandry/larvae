<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SSH extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssh:login {site : the site that we are logging into} {-E|--environment=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the already-available ssh info from the config to make a quick login';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if(!$this->argument('site')) {
            throw new \Exception("A site must be provided");
        }
        $storagePath = storage_path("app/sites/{$this->argument('site')}");

        // load config
        $siteConfig = "{$storagePath}/deploy/deploy.conf";
        $config = json_decode(file_get_contents($siteConfig));
        if(!$config) {
            throw new Exception("No valid configuration file found");
        }

        // grab site params
        $site = $config->{$this->option('environment', 'prod')};

        // start remote command chain
        $remoteCommand = "sshpass -p {$site->password} ssh {$site->user}@{$site->ip} \"cd {$site->remote_location};\"";
        $this->info($remoteCommand);
    }
}
