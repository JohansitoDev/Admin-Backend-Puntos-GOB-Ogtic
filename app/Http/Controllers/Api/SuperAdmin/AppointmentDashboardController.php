<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Gate; 

class AppointmentDashboardController extends Controller
{
    protected $citasApiUrl;
    protected $citasApiKey; 

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        
        $this->middleware('can:view-dashboard-data');        
        $this->citasApiUrl = env('CITAS_API_URL');
        $this->citasApiKey = env('CITAS_API_KEY'); 

        if (empty($this->citasApiUrl)) {
           
            Log::error('CITAS_API_URL no está configurada en el archivo .env para AppointmentDashboardController.');
          
        }
    }

  
    public function getAppointments(Request $request)
    {
      
        if (empty($this->citasApiUrl)) {
            return response()->json(['message' => 'Error de configuración: La URL de la API de citas no está definida.'], 500);
        }

    
        $queryParams = $request->only(['institution_id', 'punto_gob_id', 'date']);

        try {
           
            $response = Http::withHeaders([
                'Accept' => 'application/json',
          
            ])->get($this->citasApiUrl . '/appointments', $queryParams); 

            
            $response->throw();

            
            $appointments = $response->json();


            return response()->json($appointments); 

        } catch (\Illuminate\Http\Client\RequestException $e) {
            
            $statusCode = $e->response?->status() ?? 500;
            $errorMessage = $e->response?->json('message') ?? 'Error al comunicarse con la API de citas.';
            Log::error("Error HTTP al consultar API de citas: {$e->getMessage()}", ['response' => $e->response?->json()]);
            return response()->json(['message' => 'Error al obtener citas: ' . $errorMessage], $statusCode);
        } catch (\Exception $e) {
            
            Log::error("Error inesperado en AppointmentDashboardController: {$e->getMessage()}", ['exception' => $e]);
            return response()->json(['message' => 'Ocurrió un error interno al procesar las citas.'], 500);
        }
    }


    public function getSummary(Request $request)
    {
      
        $summary = [
            'total_appointments' => 150,
            'pending_appointments' => 50,
            'completed_appointments' => 80,
            'cancelled_appointments' => 20,
            'appointments_by_institution' => [
                'Ministerio de Interior y Policía' => 75,
                'Dirección General de Pasaportes' => 75,
            ],
            'appointments_by_service' => [
                'Renovación Cédula' => 60,
                'Pasaporte Nuevo' => 40,
                'Licencia de Conducir' => 30,
                'Acta de Nacimiento' => 20,
            ],
            'appointments_by_punto_gob' => [
                'Punto GOB Centro' => 80,
                'Punto GOB Sur' => 70,
            ],
            
        ];

        
    

        return response()->json($summary);
    }
}


