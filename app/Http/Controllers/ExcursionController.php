<?php
namespace App\Http\Controllers;

use App\Services\ExcursionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ExcursionController extends Controller
{
    protected $excursionService;

    public function __construct(ExcursionService $excursionService)
    {
        $this->excursionService = $excursionService;
    }
//-------------------------------------------------------------------------------------------------
   public function indexForPassenger(): JsonResponse
    {
        try {
           
            $result = $this->excursionService->getAllExcursionsForPassenger();

            

            return response()->json([
                'success' => true,
                'data' => $result,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener excursiones: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener excursiones',
            ], 500);
        }
    }
    //-------------------------------------------------------------------------------------------------
    public function showForPassenger(int $id): JsonResponse
    {
        try {
            $result = $this->excursionService->getExcursionDetailForPassenger($id);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'dates' => [],
                ], 404);
            }

        
            $excursionData = $result['data'];
            
            return response()->json([
                'success' => true,
                'id' => $excursionData['id'],
                'name' => $excursionData['name'],
                'description' => $excursionData['description'],
                'dates' => $excursionData['dates'] ?? [],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener detalle de excursión: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalle de excursión',
                'dates' => [],
            ], 500);
        }
    }
 
    //---------- Inscripción de pasajero a excursion ------------------------------
    public function registerToExcursion(): JsonResponse
    {
        try {
            $excursionDateId = request()->input('excursion_date_id');

            if (!$excursionDateId) {
                return response()->json([
                    'success' => false,
                    'perfil_completo' => false,
                    'message' => 'Falta la fecha de la excursión.'
                ], 422);
            }

            // Obtener usuario logueado
            $user = request()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'perfil_completo' => false,
                    'message' => 'Usuario no autenticado.'
                ], 401);
            }

            
            $profile = DB::table('profiles')->where('user_id', $user->id)->first();
            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'perfil_completo' => false,
                    'message' => 'Profile no encontrado.'
                ], 404);
            }

            
            $result = $this->excursionService->registerPassengerToExcursion($profile->id, $excursionDateId);

            return response()->json($result, $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            Log::error('Error al registrar pasajero a excursión: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'perfil_completo' => false,
                'message' => 'No se pudo completar la inscripción, intente nuevamente.'
            ], 500);
        }
    }


}
