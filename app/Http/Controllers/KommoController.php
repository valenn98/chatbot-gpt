<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KommoController extends Controller
{
    public function getContact($idLead)
    {
        $url = 'https://vittaderm.kommo.com/api/v4/leads?id=' . $idLead;

        $response = Http::withToken(config('services.kommo.apiAccessToken'))
            ->withHeaders([
                'Accept' => 'application/json',
                'Cookie' => 'session_id=98uc5dghdn68tfssdk0vuv2pj6; user_lang=en',
            ])
            ->get($url);

        $responseData = $response->json();

        $customFieldId = 1009266;
        $mensajeCliente = null;

        if (isset($responseData['_embedded']['leads'])) {

            foreach ($responseData['_embedded']['leads'] as $lead) {
                if (isset($lead['custom_fields_values'])) {
                    foreach ($lead['custom_fields_values'] as $customField) {
                        if ($customField['field_id'] === $customFieldId) {

                            $mensajeCliente = $customField['values'][0]['value'];
                            break 2;
                        }
                    }
                }
            }
        }

        return $mensajeCliente;
    }

    public function responseChatBot(int $idLead, $responseChatBot){

        $url = 'https://vittaderm.kommo.com/api/v4/leads';

        $response = Http::withToken(config('services.kommo.apiAccessToken'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Cookie' => 'session_id=98uc5dghdn68tfssdk0vuv2pj6; user_lang=en',
            ])
            ->patch($url, [
                [
                    "id" => $idLead,
                    "custom_fields_values" => [
                        [
                            "field_id" => 1009268,
                            "values" => [
                                [
                                    "value" => $responseChatBot
                                ]
                            ]
                        ],
                    ]
                ]
            ]);

        Log::alert($response->json());
        return $response->json();

    }
}
