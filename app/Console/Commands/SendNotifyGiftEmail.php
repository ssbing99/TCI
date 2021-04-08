<?php

namespace App\Console\Commands;

use App\Mail\Frontend\GiftNotifyMail;
use App\Models\Email;
use App\Models\GiftUser;
use Illuminate\Console\Command;

class SendNotifyGiftEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:gift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify the user that have gift purchased';

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
        ini_set('memory_limit', -1);
        \Log::info('Send Notify START');

        $emails = Email::query()->where('status','=','0')->get();

        foreach ($emails as $email) {
            \Log::info('EMAIL: '.json_encode($email));
            $giftUser = GiftUser::query()->where('receiver_email','=',$email->receiver_email)
                ->where('notify_at','=',$email->notify_at)
            ->get();
            if($giftUser->count() > 0) {
                $giftUser = $giftUser->first();

                $this->notifyMail($giftUser->gift, $email, $giftUser);
            }
        }
        \Log::info('Send Notify Successfully');

        return 0;
    }

    private function notifyMail($gift, $email, $giftUser)
    {
        try {
            $content['receiver_name'] = $email->receiver_name;
            $content['code'] = $giftUser->code;

            \Mail::to($email->receiver_email)->send(new GiftNotifyMail($content, $giftUser->user, $gift));

            $email->status = 1;
            $email->save();
        }catch (\Exception $e){
            \Log::info('SendNotifyEmail FAILED');
            \Log::info($e);
        }
    }
}
