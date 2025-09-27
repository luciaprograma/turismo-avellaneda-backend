<?php
namespace App\Http\Controllers;

use App\Services\ExcursionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
    
}
