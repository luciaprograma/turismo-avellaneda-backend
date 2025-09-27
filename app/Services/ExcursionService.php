<?php
namespace App\Services;

use App\Repositories\ExcursionRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class ExcursionService
{
    protected $excursionRepository;

    public function __construct(ExcursionRepository $excursionRepository)
    {
        $this->excursionRepository = $excursionRepository;
    }
//-----------------Obtener excursiones activas para pasajeros -----------------------------
    public function getAllExcursionsForPassenger(): array
    {
        try {
            $excursions = $this->excursionRepository->getAllExcursionsForPassenger();

            if ($excursions->isEmpty()) {
                return [
                    'success' => true,
                    'message' => 'No hay excursiones disponibles.',
                    'data'    => []
                ];
            }

            return [
                'success' => true,
                'message' => 'Excursiones obtenidas correctamente.',
                'data'    => $excursions
            ];

        } catch (Exception $e) {
            Log::error('Error obteniendo excursiones para pasajero: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Error interno al obtener excursiones.',
                'data'    => []
            ];
        }
    }
    //-----------------Ver detalle excursiones para pasajeros -----------------------------
    
}
