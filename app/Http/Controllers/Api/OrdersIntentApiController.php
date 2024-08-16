<?php

namespace App\Http\Controllers\Api;

use App\Http\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Models\OrdersIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrdersIntentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
 * @OA\Post(
 *     path="/orders/intent",
 *     operationId="OrdersIntentApiController.store",
 *     summary="Create a new order intent",
 *     tags={"OrderIntents"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Order intent created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Order intent created successfully"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                     example=1
 *                 ),
 *                 @OA\Property(
 *                     property="price",
 *                     type="integer",
 *                     example=1000
 *                 ),
 *                 @OA\Property(
 *                     property="type",
 *                     type="string",
 *                     example="VIP"
 *                 ),
 *                 @OA\Property(
 *                     property="user_email",
 *                     type="string",
 *                     format="email",
 *                     example="user@example.com"
 *                 ),
 *                 @OA\Property(
 *                     property="user_phone",
 *                     type="string",
 *                     example="1234567890"
 *                 ),
 *                 @OA\Property(
 *                     property="expiration_date",
 *                     type="string",
 *                     format="date",
 *                     example="2024-12-31"
 *                 ),
 *                 @OA\Property(
 *                     property="created_at",
 *                     type="string",
 *                     format="date-time",
 *                     example="2024-08-16T00:00:00Z"
 *                 ),
 *                 @OA\Property(
 *                     property="updated_at",
 *                     type="string",
 *                     format="date-time",
 *                     example="2024-08-16T00:00:00Z"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation errors",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="errors",
 *                 type="object"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error - Exception occurred",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="errors",
 *                 type="string"
 *             )
 *         )
 *     )
 * )
 */
    public function store(Request $request)
    {
        // On crée un validateur pour le formulaire que nous reçevons.
        $validator_data= Validator::make(
            $request->all(), 
            [
                'price' => 'required|integer|max_digits:10',
                'type' => 'required|string|max:50|exists:tickets_types,name',
                'user_email' => 'required|string|max:100',
                'user_phone' => 'required|string|max:20',
                'expiration_date' => 'required|date',
            ]
        );

        // Si la validation du formulaire echou on renvoi un 400 avec les erreurs de valiation.
        if ($validator_data->fails()) {
            return response()->json([
                'errors' => $validator_data->errors(),
            ], HttpStatus::BAD_REQUEST);
        }

        // On récupére les données validé du formulaire
        $validated_data = $validator_data->valid();

        // On crée un transaction
        DB::beginTransaction();
        try {
            // On Créer une nouvelle instance d'intention de commande avec les données validées
            $ordersIntent = OrdersIntent::create($validated_data);
        
            // Si tout se passe bien, on valide la transaction
            DB::commit();
        
            // On retourner une réponse JSON de succès
            return response()->json([
                'message' => 'Order intent created successfully',
                'data' => $ordersIntent
            ], HttpStatus::CREATED);
        
        } catch (\Exception $e) {
            // En cas d'erreur, on annule la transaction
            DB::rollBack();
        
            // On retourner une réponse JSON avec le message d'erreur
            return response()->json([
                'errors' => $e->getMessage()
            ], HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdersIntent $ordersIntent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrdersIntent $ordersIntent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrdersIntent $ordersIntent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrdersIntent $ordersIntent)
    {
        //
    }
}
