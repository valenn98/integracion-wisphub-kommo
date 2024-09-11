<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WisphubController extends Controller
{
    public function getStatus(Request $request)
    {
        $idLead = $request['leads']['add'][0]['id'];

        $kommo = new KommoController();
        $identificacion = $kommo->getContact($idLead);

        $url = config('services.wisphub.clientes_url');
        $headers = [
            'Authorization' => 'Api-Key ' . config('services.wisphub.api_key'),
        ];

        $params = [
            'cedula' => $identificacion,
        ];

        $response = Http::withHeaders($headers)->get($url, $params);
        $data = $response->json();
        
        if ($data['count'] == 0) {
            $kommo->noExiste($idLead);
        } else {
            $estado = $data['results'][0]['estado'];
            $totalFactura = null;

            if ($estado == 'Activo') {
                $kommo->activo($idLead);
            } elseif ($estado == 'Suspendido') {
                $totalFactura = $this->obtenerFactura($identificacion);
                $kommo->suspendido($idLead, (string) $totalFactura);
            } elseif ($estado == 'Cancelado') {
                $kommo->cancelado($idLead);
            } elseif ($estado == 'Gratis') {
                $kommo->activo($idLead);
            }
        }
    }

    public function getStatusFactura(Request $request)
    {
        $idLead = $request['leads']['add'][0]['id'];
        $kommo = new KommoController();
        $identificacion = $kommo->getContact($idLead);

        $url = config('services.wisphub.clientes_url');
        $headers = [
            'Authorization' => 'Api-Key ' . config('services.wisphub.api_key'),
        ];

        $params = [
            'cedula' => $identificacion,
        ];

        $response = Http::withHeaders($headers)->get($url, $params);
        $data = $response->json();

        if ($data['count'] == 0) {
            $kommo->noExiste($idLead);
        } else {
            $estado = $data['results'][0]['estado'];
            $totalFactura = null;

            if ($estado == 'Activo') {
                $totalFactura = $this->obtenerFactura($identificacion);
                $kommo->activoFactura($idLead, (string) $totalFactura);
            } elseif ($estado == 'Suspendido') {
                $totalFactura = $this->obtenerFactura($identificacion);
                $kommo->suspendidoFactura($idLead, (string) $totalFactura);
            } elseif ($estado == 'Gratis') {
                $totalFactura = $this->obtenerFactura($identificacion);
                $kommo->activoFactura($idLead, (string) $totalFactura);
            }
        }
    }

    public function obtenerFactura($cedula)
    {
        $identificacion = $cedula;
        $url = config('services.wisphub.facturas_url');
        $headers = [
            'Authorization' => 'Api-Key ' . config('services.wisphub.api_key'),
        ];

        $response = Http::withHeaders($headers)->get($url);
        $data = $response->json();

        $totalFactura = null;
        foreach ($data['results'] as $factura) {
            if ($factura['cliente']['cedula'] == $identificacion) {
                $totalFactura = $factura['total'];
                break;
            }
        }

        return $totalFactura;
    }
}
