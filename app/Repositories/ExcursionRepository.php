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
            ->select('id','name','description')
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
                
                ->first();

          

            if (!$excursion) {
                Log::info('No excursion found for id: ' . $id);
                return null;
            }

            
            $dates = DB::table('excursion_dates')
                ->select('id', 'date', 'time', 'capacity', 'price')
                ->where('excursion_id', $id)
                ->where('status', true)
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
//--------------Inscripcion a excursiones pasajero------------------------------
   public function registerPassengerToExcursion(int $profileId, int $excursionDateId)
{
    try {
        return DB::transaction(function () use ($profileId, $excursionDateId) {

            $profile = DB::table('profiles')
                ->select('id', 'first_name', 'last_name', 'dni', 'birth_date', 'address', 'phone_number')
                ->where('id', $profileId)
                ->first();

            if (!$profile) {
                throw new \Exception('Profile no encontrado.');
            }

            $requiredFields = ['first_name', 'last_name', 'dni', 'birth_date', 'address', 'phone_number'];
            foreach ($requiredFields as $field) {
                if (empty($profile->$field)) {
                    throw new \Exception('Debe completar sus datos personales antes de inscribirse a la excursión.');
                }
            }

           
            $alreadyRegistered = DB::table('excursion_registrations')
                ->where('profile_id', $profile->id)
                ->where('excursion_date_id', $excursionDateId)
                ->exists();

            if ($alreadyRegistered) {
                throw new \Exception('Ya está inscripto en esa fecha y horario.');
            }

            DB::table('excursion_registrations')->insert([
                'profile_id' => $profile->id,
                'excursion_date_id' => $excursionDateId,
                'status' => 'registered',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return [
                'success' => true,
                'message' => 'Inscripción realizada correctamente.',
                'profile' => true
            ];
        });
    } catch (\Exception $e) {
        Log::error('Error en registerPassengerToExcursion: '.$e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return [
            'success' => false,
            'profile' => false,
            'message' => $e->getMessage() 
        ];
    }
}


}
