<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $baseUrl = 'https://graph.facebook.com/v23.0';
    protected $phoneNumberId;
    protected $token;

    public function __construct()
    {
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->token = config('services.whatsapp.token');
    }

    public function sendAppointmentConfirmation($to, $name, $date, $time, $doctor)
{
    $url = "{$this->baseUrl}/{$this->phoneNumberId}/messages";

    $payload = [
        "messaging_product" => "whatsapp",
        "to" => $to,
        "type" => "template",
        "template" => [
            "name" => "appointment_info", // <-- Your approved template name
            "language" => [ "code" => "en" ],
            "components" => [
                [
                    "type" => "body",
                    "parameters" => [
                        [ "type" => "text", "text" => $name ],
                        [ "type" => "text", "text" => $date ],
                        [ "type" => "text", "text" => $time ],
                        [ "type" => "text", "text" => $doctor ],
                    ],
                ]
            ]
        ]
    ];

    $response = Http::withToken($this->token)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post($url, $payload);

    return $response->json();
}

    public function sendAppointmentTemplate($to, $name, $doctor, $date, $startTime)
    {
        $url = "{$this->baseUrl}/{$this->phoneNumberId}/messages";

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => "appointment_scheduling_ ",
                "language" => [ "code" => "en_US" ],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            [ "type" => "text", "text" => $name ],
                            [ "type" => "text", "text" => $doctor ],
                            [ "type" => "text", "text" => $date ],
                            [ "type" => "text", "text" => $startTime ],
                        ],
                    ],
                    [
                        "type" => "button",
                        "sub_type" => "quick_reply",
                        "index" => "0",
                        "parameters" => [
                            [ "type" => "payload", "payload" => "CONFIRM_APPOINTMENT" ]
                        ]
                    ],
                    [
                        "type" => "button",
                        "sub_type" => "quick_reply",
                        "index" => "1",
                        "parameters" => [
                            [ "type" => "payload", "payload" => "RESCHEDULE_APPOINTMENT" ]
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withToken($this->token)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post($url, $payload);
        dd($response->json());
        return $response->json();
    }

    public function sendConfirmationMessage($to)
    {
        $url = "{$this->baseUrl}/{$this->phoneNumberId}/messages";

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => "appointment_confirmation",
                "language" => [
                    "code" => "en"
                ]
            ]
        ];

        $response = Http::withToken($this->token)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post($url, $payload);

        return $response->json();
    }

    public function sendRecheduleMessage($to)
    {
        $url = "{$this->baseUrl}/{$this->phoneNumberId}/messages";

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => "reschedule_accpointment",
                "language" => [
                    "code" => "en_US"
                ]
            ]
        ];

        $response = Http::withToken($this->token)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post($url, $payload);

        return $response->json();
    }
}
