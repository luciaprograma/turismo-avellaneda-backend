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
                ->select('id','name', 'description')
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
            
            $excursion = DB::table('excursions')
                ->select('id', 'name', 'description')
                ->where('id', $id)
                ->where('status', true)
                ->first();

          

            if (!$excursion) {
                Log::info('No excursion found for id: ' . $id);
                return null;
            }

            
            $dates = DB::table('excursion_dates')
                ->select('id', 'date', 'time', 'capacity', 'price')
                ->where('excursion_id', $id)
                ->orderBy('date', 'asc')
                ->get();

          
           
            $result = [
                'id' => $excursion->id,
                'name' => $excursion->name,
                'description' => $excursion->description,
                'dates' => $dates
            ];

          
       

            return $result;

        } catch (\Exception $e) {
            Log::error('Error en getExcursionDetailForPassenger: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
