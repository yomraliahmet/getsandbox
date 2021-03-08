<?php

namespace App\Console\Commands;

use App\Jobs\ApiSetJob;
use Faker\Factory;
use Illuminate\Console\Command;

class CreateSandboxUser extends Command
{
    private $url;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getsandbox:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Getsandbox Create Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = config("api.getsandbox_url")."/users";
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $faker = Factory::create();

        for ($i=0;$i<10;$i++){

            $data = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
            ];

            ApiSetJob::dispatch($this->url, $data);
        }

        $this->info("Add queue");

        return 1;
    }
}
