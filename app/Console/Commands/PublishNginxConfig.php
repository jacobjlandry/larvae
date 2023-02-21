<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PublishNginxConfig extends Command
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
        // @TODO switch to conf
        // set up the copy command
        echo "Copying the .env file\r\n";
        $copyCommand = "sshpass -p {$site->password} scp {$site->env} {$site->user}@{$site->ip}:{$site->remote_location}/.env";
        echo "$copyCommand\r\n";
        system($copyCommand);
    }
}
