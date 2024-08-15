<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AppServices
{
    /**
     * 
     */
    public static function generateUserPassword() : string 
    {
        $length = 8;
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.;,?&!';
        
        $password = substr(str_shuffle($characters), 0, $length);
        $hash_password = Hash::make($password);
        $user = User::firstWhere('password',$hash_password);
        while ($user!=null) {
            $password = substr(str_shuffle($characters), 0, $length);
            $hash_password = Hash::make($password);
            $user = User::firstWhere('password',$hash_password);
        }
        return $password;
    } 

    /**
     * 
     */
    public static function generateOrdreNumber(int $id) : string 
    {
        $date_now = Carbon::now()->format("Ymd");
        $length = 11;
        $formatted_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $ordre_number = "ORD".$date_now.$formatted_id;
        return $ordre_number;
    }

    public static function generateTypeKey(int $id) : string 
    {
        $date_now = Carbon::now()->format("Ymd");
        $length = 11;
        $formatted_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $type_key = "KEY-".$date_now.$formatted_id;
        return $type_key;
    } 
}
