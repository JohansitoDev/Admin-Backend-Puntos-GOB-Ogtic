<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Gate; 

/**
 * @OA\Tag(
 * name="SuperAdmin - Citas y Dashboard",
 * description="Consulta de Citas y Datos Agregados para el Dashboard de Puntos GOB"
 * )
 */
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

    /**
     * @OA\Get(
     * path="/api/superadmin/dashboard/appointments",
     * operationId="getAppointmentsForDashboard",
     * tags={"SuperAdmin - Citas y Dashboard"},
     * summary="Obtener lista de citas para el dashboard",
     * description="Consulta el backend de citas (API del ciudadano) para obtener una lista de citas. Permite filtrar por institución, punto GOB y fecha.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(name="institution_id", in="query", required=false, @OA\Schema(type="integer"), description="Filtrar citas por ID de institución."),
     * @OA\Parameter(name="punto_gob_id", in="query", required=false, @OA\Schema(type="integer"), description="Filtrar citas por ID de Punto GOB."),
     * @OA\Parameter(name="date", in="query", required=false, @OA\Schema(type="string", format="date", example="2025-07-14"), description="Filtrar citas por fecha (YYYY-MM-DD)."),
     * @OA\Response(
     * response=200,
     * description="Lista de citas obtenida exitosamente desde la API de citas.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="appointment_id", type="string", description="ID único de la cita en el sistema de citas."),
     * @OA\Property(property="citizen_name", type="string", description="Nombre del ciudadano."),
     * @OA\Property(property="date", type="string", format="date", description="Fecha de la cita."),
     * @OA\Property(property="time", type="string", format="time", description="Hora de la cita."),
     * @OA\Property(property="institution_name", type="string", description="Nombre de la institución de la cita."),
     * @OA\Property(property="punto_gob_name", type="string", description="Nombre del Punto GOB de la cita."),
     * @OA\Property(property="service_name", type="string", description="Nombre del servicio de la cita."),
     * @OA\Property(property="status", type="string", description="Estado actual de la cita (ej. 'pending', 'confirmed', 'cancelled', 'completed').")
     * )
     * )
     * ),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="Acceso denegado (el usuario no tiene el permiso 'view-dashboard-data')."),
     * @OA\Response(response=500, description="Error de configuración o comunicación con la API de citas.")
     * )
     */
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

            /*
            $transformedAppointments = collect($appointments)->map(function ($appointment) {
                return [
                    'appointment_id' => $appointment['uuid'] ?? $appointment['id'],
                    'citizen_name' => $appointment['user']['full_name'] ?? 'Ciudadano Desconocido',
                    'date' => $appointment['scheduled_date'],
                    'time' => $appointment['scheduled_time'],
                    'institution_name' => $appointment['service_provider']['institution']['name'] ?? 'N/A',
                    'punto_gob_name' => $appointment['service_provider']['punto_gob']['name'] ?? 'N/A',
                    'service_name' => $appointment['service']['name'] ?? 'Servicio Desconocido',
                    'status' => $appointment['status'],
                ];
            })->toArray();
            return response()->json($transformedAppointments);
            */

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

    /**
     * @OA\Get(
     * path="/api/superadmin/dashboard/summary",
     * operationId="getDashboardSummary",
     * tags={"SuperAdmin - Citas y Dashboard"},
     * summary="Obtener resumen para el dashboard",
     * description="Retorna datos agregados para el dashboard (ej. conteo de citas por estado, por institución, etc.).",
     * security={{"sanctum": {}}},
     * @OA\Parameter(name="period", in="query", required=false, @OA\Schema(type="string", enum={"day", "week", "month", "year"}, default="day"), description="Período de tiempo para el resumen (day, week, month, year)."),
     * @OA\Response(
     * response=200,
     * description="Resumen de datos del dashboard obtenido exitosamente.",
     * @OA\JsonContent(
     * @OA\Property(property="total_appointments", type="integer", example=150, description="Total de citas en el período."),
     * @OA\Property(property="pending_appointments", type="integer", example=50, description="Citas con estado pendiente."),
     * @OA\Property(property="completed_appointments", type="integer", example=80, description="Citas completadas."),
     * @OA\Property(property="cancelled_appointments", type="integer", example=20, description="Citas canceladas."),
     * @OA\Property(property="appointments_by_institution", type="object", example={"Ministerio A": 75, "Ministerio B": 75}, description="Conteo de citas por institución."),
     * @OA\Property(property="appointments_by_service", type="object", example={"Renovación Cédula": 60, "Pasaporte Nuevo": 40}, description="Conteo de citas por servicio."),
     * @OA\Property(property="appointments_by_punto_gob", type="object", example={"Punto GOB Centro": 80, "Punto GOB Sur": 70}, description="Conteo de citas por Punto GOB.")
     * )
     * ),
     * @OA\Response(response=401, description="No autenticado."),
     * @OA\Response(response=403, description="Acceso denegado."),
     * @OA\Response(response=500, description="Error al generar el resumen del dashboard.")
     * )
     */
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

        
        /*
        $period = $request->input('period', 'day'); // 'day', 'week', 'month', 'year'
        $startDate = null;
        

        $response = Http::withHeaders(['Accept' => 'application/json'])->get($this->citasApiUrl . '/appointments', [
            'start_date' => $startDate ? $startDate->toDateString() : null,
          
        ]);

        $appointments = $response->json();
        
        */

        return response()->json($summary);
    }
}


