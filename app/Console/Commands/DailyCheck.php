<?php

namespace App\Console\Commands;

use App\Models\Ban_user;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkban:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to check banned users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // php artisan checkban:daily <- to run this command
        // php artisan schedule:work <- to run the scheduler
        $users = Ban_user::all();
        foreach ($users as $user) {
            // $date = date('d-m-Y');
            $date_today = Carbon::createFromFormat('d-m-Y', date('d-m-Y'));
            $ban_till = Carbon::createFromFormat('d-m-Y', $user->ban_till);

            if ($ban_till->eq($date_today)) {
                Ban_user::where('user_id', $user->user_id)->delete();
                echo Carbon::now() . ' => The ban is lifted to user ' . $user->user_id . "\n";
            }
        }
    }
}
