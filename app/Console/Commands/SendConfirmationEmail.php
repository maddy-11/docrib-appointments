<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twilio\Rest\Client;
use App\Models\Appointment;
use Carbon\Carbon;
class SendConfirmationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can pass the recipient email as an argument if you want.
     */
    protected $signature = 'email:test';

    /**
     * The console command description.
     */
    protected $description = 'Send the test email notification (same as /test1 route)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = '';

        $tomorrow = Carbon::now('Asia/Karachi')->addDay()->toDateString();

        $appointments = Appointment::whereIn('id', function ($query) use ($tomorrow) {
            $query->selectRaw('MAX(id)')
            ->from('appointments')
            ->whereDate('booking_date', $tomorrow)
            ->whereNull('is_confirmation_sent')
            ->groupBy('mobile');
        })->get();
        $domain = env('APP_URL');
        foreach($appointments as $appointment){
            $receiverName = $appointment->name;

            try {

                notify($this->notifyUser($appointment), 'APPOINTMENT_CONFIRMATION_BEFORE_24_HOURS', [
                    'booking_date' => $appointment->booking_date,
                    'time_serial'  => $appointment->time_serial,
                    'doctor_name'  => $appointment->doctor->name,
                    'yes_link' => '<a href="' . route('appointment.confirmation.email_confirm', ['id' => $appointment->id]) . '" style="display: inline-block; padding: 12px 24px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px; font-family: Arial, sans-serif; font-size: 16px; font-weight: bold; margin: 5px;">Confirm Appointment</a>',
                    'reschedule_link' => '<a href="' . route('appointment.confirmation.email_reschedule', ['id' => $appointment->id]) . '" style="display: inline-block; padding: 12px 24px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 4px; font-family: Arial, sans-serif; font-size: 16px; font-weight: bold; margin: 5px;">Reschedule</a>',
                ]);
            } catch (\Throwable $e) {
                $notify[] = ['error', $e->getMessage()];
                foreach ($notify as $n) {
                    $this->error("{$n[0]}: {$n[1]}");
                }
                return 1;
            }

            if (session('mail_error')) {
                $notify[] = ['error', session('mail_error')];
                foreach ($notify as $n) {
                    $this->error("{$n[0]}: {$n[1]}");
                }
                return 1;
            } else {
                $notify[] = ['success', "Email sent to {$receiverName} successfully"];
                foreach ($notify as $n) {
                    $this->info("{$n[0]}: {$n[1]}");
                }
            }

        // ---
            $general = gs();
            $sid    = $general->sms_config->twilio->account_sid;
            $token  = $general->sms_config->twilio->auth_token;
            $twilio = new Client($sid, $token);
            $number = $general->sms_config->twilio->from;
            $contentVariables = json_encode([
                "date" => "{$appointment->booking_date}",
                "time" => "{$appointment->time_serial}",
                'first_name' => $appointment->name
            ]);
            $recipient = $appointment->mobile;
            $message = $twilio->messages->create(
                "whatsapp:{$recipient}",
                [
                    "from" => "whatsapp:{$number}",
                    "contentSid" => "HX69d1e5581213120e68087fe7c16217cd",
                    "contentVariables" => $contentVariables,
                    "body" => "Your Message"
                ]
            );
            $this->info("SMS sent");
            $appointment->is_confirmation_sent = true;
            $appointment->save();
        }
        return 0;
    }
    protected  function notifyUser($appointment)
    {
        $user = [
            'name'     => $appointment->name,
            'username' => $appointment->email,
            'fullname' => $appointment->name,
            'email'    => $appointment->email,
            'mobile'   => $appointment->mobile,
            'yes_link ' => 'http://localhost:8000/confirm-email',
            'reschedule_link  ' => 'http://localhost:8000/reschedule'
        ];
        return $user;
    }
}
