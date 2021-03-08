<?php

namespace App\Console\Commands;

use App\Jobs\ApiGetJob;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GetSendboxUsers extends Command
{
    private $url;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getsandbox:get-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Getsandbox Get Users';

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
        $data = Http::get($this->url);

        foreach ($data->object() as $user){

            $newUser = [
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ];

            ApiGetJob::dispatch($newUser);
        }

        $this->info("Add queue");

        return 0;
    }
}
