<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExcursionRepository
{
    //------------Obtener excursiones activas pasajero------------------------------


    public function getAllExcursionsForPassenger()
    {
        try {
            $query = DB::table('excursions')
                ->select('name', 'description')
                ->where('status', true)
                ->orderBy('name', 'asc');

            $result = $query->get();

            return $result;

        } catch (\Exception $e) {
            Log::error('Error en getAllExcursionsForPassenger: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; 
        }
    }
    

//-----------------Ver detalle excursiones para pasajeros -----------------------------
    public function getExcursionDetailForPassenger(int $id)
    {
        try {
            // Traemos la excursión activa
            $excursion = DB::table('excursions')
                ->select('id', 'name', 'description')
                ->where('id', $id)
                ->where('status', true)
                ->first();

            if (!$excursion) {
                return null;
            }

            // Traemos las fechas y horarios asociados
            $dates = DB::table('excursion_dates')
                ->select('id', 'date', 'time', 'capacity')
                ->where('excursion_id', $id)
                ->orderBy('date_time', 'asc')
                ->get();

          
            return [
                'id' => $excursion->id,
                'name' => $excursion->name,
                'description' => $excursion->description,
                'dates' => $dates
            ];

        } catch (\Exception $e) {
            Log::error('Error en getExcursionDetailForPassenger: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
