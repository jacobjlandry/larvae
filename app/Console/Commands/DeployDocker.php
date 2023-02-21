<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployDocker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy {site : the site that is being deployed} {--E|environment=?} {--B|build=?} {--G|git=?} {--C|cleanup} {--D|dryrun}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy updates to a remote server';

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
        $remoteCommand = "sshpass -p {$site->password} ssh {$site->user}@{$site->ip} \"cd {$site->remote_location}; ";
        $logs = [];
        $commands = [];

        // set up the copy command
        $env = "{$storagePath}/environment/{$site->env}";
        $logs['env'] = "Copying {$env}...";
        $copyCommand = "sshpass -p {$site->password} scp {$env} {$site->user}@{$site->ip}:{$site->remote_location}/.env";
        $commands['env'] = $copyCommand;

        // git update
        $logs['remote'] = [];
        if($this->option('git')) {
            $branch = $this->option('git', 'master');
            $logs['remote'][] = "Checking out and updating git branch: {$branch}...";
            $remoteCommand .= "git fetch origin; git checkout {$branch}; git pull origin {$branch}; ";
        }

        // build
        if($this->option('build')) {
            $logs['remote'][] = "Running docker build...";
            $remoteCommand .= "docker-compose up -d --build {$this->option('build')}; ";
        }

        // cleanup
        if($this->option('cleanup')) {
            $logs['remote'][] = "Cleaning up copied files...";
            $remoteCommand .= "rm {$site->remote_location}/.env";
        }

        // close out the remote command and execute
        $remoteCommand .= "\"";
        $commands['remote'] = $remoteCommand;
        
        foreach($commands as $key => $command) {
            // generate logs for the commands we plan to run
            if(isset($logs[$key])) {
                if(is_array($logs[$key])) {
                    foreach($logs[$key] as $line) {
                        $this->info($line);
                    }
                } else {
                    $this->info($logs[$key]);
                }
            }
    
            // run the commands
            if(!$this->option('dryrun')) {
                system($command);
            } else {
                $this->info($command);
            }
        }
    }
}
