<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KommoController extends Controller
{
    public function getContact($idLead)
    {
        $url = config('services.kommo.leads_url') . '/' . $idLead;

        $response = Http::withToken(config('services.kommo.apiAccessToken'))
            ->withHeaders([
                'Accept' => 'application/json',
                'Cookie' => config('services.kommo.cookie'),
            ])
            ->get($url);

        $responseData = $response->json();
        $customFieldId = 2071420;
        $identificacion = null;

        if (isset($responseData['custom_fields_values'])) {
            foreach ($responseData['custom_fields_values'] as $customField) {
                if ($customField['field_id'] === $customFieldId) {
                    $identificacion = $customField['values'][0]['value'];
                    break;
                }
            }
        }

        return $identificacion;
    }

    public function activo(int $idLead)
    {
        $url = config('services.kommo.leads_url');

        $response = Http::withToken(config('services.kommo.apiAccessToken'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Cookie' => config('services.kommo.cookie'),
            ])
            ->patch($url, [
                [
                    "id" => $idLead,
                    "pipeline_id" => 8276951,
                    "status_id" => 66840995
                ]
            ]);

        return $response->json();
    }

    public function suspendido(int $idLead, string $totalFactura)
    {
        $url = config('services.kommo.leads_url');

        $response = Http::withToken(config('services.kommo.apiAccessToken'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Cookie' => config('services.kommo.cookie'),
            ])
            ->patch($url, [
                [
                    "id" => $idLead,
                    "pipeline_id" => 8276951,
                    "status_id" => 66841047,
                    "custom_fields_values" => [
                        [
                            "field_id" => 2072770,
                            "values" => [
                                [
                                    "value" => $totalFactura
                                ]
                            ]
                        ],
                    ]
                ]
            ]);

        return $response->json();
    }

    public function cancelado(int $idLead)
    {
        $url = config('services.kommo.leads_url');

        $response = Http::withToken(config('services.kommo.apiAccessToken'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Cookie' => config('services.kommo.cookie'),
            ])
            ->patch($url, [
                [
                    "id" => $idLead,
                    "pipeline_id" => 8276951,
                    "status_id" => 66841051
                ]
            ]);

        return $response->json();
    }

    public function activoFactura(int $idLead, string $totalFactura)
    {
        $url = config('services.kommo.leads_url');

        $response = Http::withToken(config('services.kommo.apiAccessToken'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Cookie' => config('services.kommo.cookie'),
            ])
            ->patch($url, [
                [
                    "id" => $idLead,
                    "pipeline_id" => 8452275,
                    "status_id" => 66950471,
                    "custom_fields_values" => [
                        [
                            "field_id" => 2072770,
                            "values" => [
                                [
                                    "value" => $totalFactura
                                ]
                            ]
                        ],
                    ]
                ]
            ]);

        return $response->json();
    }

    public function suspendidoFactura(int $idLead, string $totalFactura)
    {
        $url = config('services.kommo.leads_url');

        $response = Http::withToken(config('services.kommo.apiAccessToken'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Cookie' => config('services.kommo.cookie'),
            ])
            ->patch($url, [
                [
                    "id" => $idLead,
                    "pipeline_id" => 8452275,
                    "status_id" => 66950475,
                    "custom_fields_values" => [
                        [
                            "field_id" => 2072770,
                            "values" => [
                                [
                                    "value" => $totalFactura
                                ]
                            ]
                        ],
                    ]
                ]
            ]);

        return $response->json();
    }

    public function noExiste(int $idLead)
    {
        $url = config('services.kommo.leads_url');

        $response = Http::withToken(config('services.kommo.apiAccessToken'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Cookie' => config('services.kommo.cookie'),
            ])
            ->patch($url, [
                [
                    "id" => $idLead,
                    "pipeline_id" => 8276951,
                    "status_id" => 67556695,
                ]
            ]);

        return $response->json();
    }
}
