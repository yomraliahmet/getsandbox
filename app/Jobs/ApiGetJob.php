<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ApiGetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::query()->where("email", $this->data["email"])->first();

        if(!$user){
            $newUser = new User();
            $newUser->fill($this->data);
            $newUser->save();

            Log::info("Created. Model: ".User::class." ID: ".$newUser->id);
        }else{
            Log::warning("Error. Model: ".User::class." Email: ".$this->data["email"]);
        }
    }

    public function fail($exception = null)
    {
        Log::error("Error: ".$exception);
    }
}
