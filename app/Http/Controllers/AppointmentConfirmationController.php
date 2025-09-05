<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Services\WhatsAppService;

class AppointmentConfirmationController extends Controller
{
    public function whatsapp_webhook_response(Request $request, WhatsAppService $whatsapp)
{
    if ($request->isMethod('get')) {
        $verifyToken = "my_custom_verify_token";
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode && $token === $verifyToken) {
            return response((string) $challenge, 200)
            ->header('Content-Type', 'text/plain');
        }
        return response('Invalid verification token', 403);
    }

    if ($request->isMethod('post')) {
        $data = $request->all();
        // \Log::info('WhatsApp Webhook Raw:', $data);

        $messages = $data['entry'][0]['changes'][0]['value']['messages'] ?? null;
        if (!$messages) {
            return response('No message found', 200);
        }

        $message = $messages[0];
        $from = $message['from'];
        $type = $message['type'];

        // \Log::info('WhatsApp Webhook Raw:', $message, $from, $type);
        try {
            $from = "+" . $from;
            $appointment = \App\Models\Appointment::where('mobile', $from)
                ->where(function ($query) {
                    $query->whereNull('is_confirmed')
                          ->orWhere('is_confirmed', false);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            if ($type === 'button') {
                $payload = $message['button']['payload'];
                if ($payload === 'CONFIRM_APPOINTMENT') {
                    if ($appointment) {
                        $appointment->is_confirmed = true;
                        $appointment->save();
                        return $whatsapp->sendConfirmationMessage("923179925836");
                        \Log::info("✅ Appointment confirmed for {$from}");
                    }
                }

                if ($payload === 'RESCHEDULE_APPOINTMENT') {
                    if ($appointment) {
                        $appointment->is_confirmed = false;
                        $appointment->save();
                        return $whatsapp->sendRecheduleMessage("923179925836");
                        \Log::info("🔄 Appointment reschedule requested by {$from}");
                    }
                }
            }
            if ($type === 'text') {
                $incoming = strtolower(trim($message['text']['body']));

                if (str_contains($incoming, 'confirm') || $incoming === 'y' || $incoming === 'yes') {
                    if ($appointment) {
                        $appointment->is_confirmed = true;
                        $appointment->save();
                        $whatsapp->sendConfirmationMessage("923179925836");
                        \Log::info("✅ Appointment confirmed (text) for {$from}");
                    }
                } elseif (str_contains($incoming, 'reschedule') || str_contains($incoming, 'change')) {
                    if ($appointment) {
                        $appointment->is_confirmed = false;
                        $appointment->save();
                        $whatsapp->sendRecheduleMessage("923179925836");
                        \Log::info("🔄 Appointment reschedule requested (text) by {$from}");
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error("Webhook error: " . $e->getMessage());
        }

        return response('EVENT_RECEIVED', 200);
    }

    return response('Invalid request', 404);
}

    function confirm_via_email(Request $request, $id){
        try {
            $appointment = Appointment::findOrFail($id);
            
            $appointment->is_confirmed = true;
            $appointment->save();
            
            return view('appointment_confirmation', [
                'appointment' => $appointment,
                'pageTitle' => 'Appointment Confirmed'
            ]);
            
        } catch (\Exception $e) {
            return view('appointment_confirmation_error', [
                'pageTitle' => 'Appointment Not Found',
                'error' => 'The appointment could not be found or has already been processed.'
            ]);
        }
    }

    function reschedule_via_email(Request $request, $id){
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->is_confirmed = false;
            $appointment->save();
            
            $doctorId = $appointment->doctor_id;
            return redirect()->route('doctors.booking', ['id' => $doctorId])
            ->with('success', 'Your appointment has been marked for rescheduling. Please select a new time slot.');
            
        } catch (\Exception $e) {
            return redirect()->route('home')
            ->with('error', 'The appointment could not be found or has already been processed.');
        }
    }
}
