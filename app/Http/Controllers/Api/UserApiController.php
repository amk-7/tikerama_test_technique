<?php

namespace App\Http\Controllers\Api;

use App\Http\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserRessource;
use App\Mail\SendTickeramaApiIdentifiers;
use App\Models\User;
use App\Services\AppServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserApiController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retourner la page web d'inscription à l'Api.
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // On valide le formulaire que nous reçevons.
        $validated_data = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'business' => 'required|string',
            'town' => 'required|string',
            'email' => 'required|email|unique:users',
            'address' => 'required|string',
        ]);
        
        // On crée un transaction
        DB::beginTransaction();
        try {
            // On Créer une nouvelle instance  de user avec les données validées
            $password = AppServices::generateUserPassword();
            $validated_data['password'] = Hash::make($password);
            $user = User::create($validated_data);
        
            // Si tout se passe bien, on valide la transaction
            DB::commit();

            // On envoi le mail content les identifiants de connexion à l'API.
            $data = [
                'full_name' => $user->fullName,
                'email' => $user->email,
                'password' => $password,
            ];
            
            Mail::to($user->email)->send(new SendTickeramaApiIdentifiers($data));
                    
            // On retourner sur la page web
            return redirect()->route('register')->with("success", "Your ccount was successfuly registrate. Go to consult your email.");
        
        } catch (\Exception $e) {
            // En cas d'erreur, on annule la transaction
            DB::rollBack();
            //return redirect()->route('register')->with("errors", "");
        }

    }

    /**
     * Display the specified resource.
     */
    public function login(Request $request)
    {
        // On valide le formulaire que nous reçevons.
        $validator_data = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Si la validation du formulaire echou on renvoi un 400 avec les erreurs de valiation.
        if ($validator_data->fails()) {
            return response()->json([
                'errors' => $validator_data->errors(),
            ], HttpStatus::BAD_REQUEST);
        }
     
        // On récupére les données validé du formulaire
        $validated_data = $validator_data->valid();

        $user = User::where('email', $validated_data['email'])->first();
     
        // Si la validation du mot de passe echou on renvoi un 400 avec les erreurs de valiation.
        if (! $user || ! Hash::check($validated_data['password'], $user->password)) {
            return response()->json([
                'errors' => 'The provided credentials are incorrect.',
            ], HttpStatus::BAD_REQUEST);
        }
     
        // On crée le token de connexion à l'Api qu'on retourne à l'Utilisateur via un JSON
        return response()->json([
            'token' => $user->createToken($request->email)->plainTextToken,
        ], HttpStatus::OK);    
    }

    public function logout(Request $request)
    {
        // On détruit le token
        $request->user()->tokens()->delete();

        // On retourn un JSON 
        return response()->json([
            'message' => 'Logged out successfully',
        ], HttpStatus::OK);
    }

}
