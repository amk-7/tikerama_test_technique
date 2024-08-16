<?php

namespace App\Http\Controllers\Api;

use App\Http\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrdersIntent;
use App\Models\Ticket;
use App\Models\TicketsType;
use App\Services\AppServices;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ismaelw\LaraTeX\LaraTeX;
use Mockery\Matcher\Type;

class OrderApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/orders",
     *     operationId="OrderApiController.index",
     *     summary="Get list of current user's orders",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of user's orders",
     *         @OA\MediaType(
     *           mediaType="application/json",
     *       )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     * )
    **/
    public function index(Request $request)
    {
        $perPage = 10;

        // On récupère les commande
        $current_user_orders = $request->user()->orders()->paginate($perPage);

        // On retourne les commande en utilisant une ressource
        return OrderResource::collection($current_user_orders);
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
         *     path="/orders/validate/{order_intent_id}/{event_id}",
         *     operationId="OrderApiController.store",
         *     summary="Validate an intent order",
         *     tags={"Validate an intent order"},
         *     security={{"sanctum":{}}},
         *     @OA\Parameter(
         *         name="order_intent_id",
         *         in="path",
         *         description="ID of the order intent",
         *         required=true,
         *         @OA\Schema(type="integer")
         *     ),
         *     @OA\Parameter(
         *         name="event_id",
         *         in="path",
         *         description="ID of the event",
         *         required=true,
         *         @OA\Schema(type="integer")
         *     ),
         *     @OA\RequestBody(
         *         required=true,
         *      ),
         *     @OA\Response(
         *         response=201,
         *         description="Order created successfully",
         *         @OA\JsonContent(
         *             type="object",
         *             @OA\Property(property="message", type="string", example="Order created successfully"),
         *             @OA\Property(property="tickets_url", type="string", example="/storage/public/pdf/12345.pdf")
         *         )
         *     ),
         *     @OA\Response(
         *         response=400,
         *         description="Bad Request - Validation errors",
         *         @OA\JsonContent(
         *             type="object",
         *             @OA\Property(property="errors", type="object")
         *         )
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Not Found - Order intent or event not found",
         *         @OA\JsonContent(
         *             type="object",
         *             @OA\Property(property="errors", type="string")
         *         )
         *     ),
         *     @OA\Response(
         *         response=500,
         *         description="Internal Server Error - Exception occurred",
         *         @OA\JsonContent(
         *             type="object",
         *             @OA\Property(property="errors", type="string")
         *         )
         *     ),
         * )
    **/

    public function store(Request $request, int $order_intent_id, int $event_id)
    {
        
        // On essai de trouver l'intention de commande par son id. On leve une exception 404 Not Found si l'intention n'existe pas
        try {
            $ordersIntent = OrdersIntent::findOrFail($order_intent_id);
        } catch (ModelNotFoundException $e) {
            // En cas d'erreur, on retourner une réponse JSON avec le message d'erreur
            return response()->json([
                'errors' => $e->getMessage()
            ], HttpStatus::NOT_FOUND);
        }

        // On essai de trouver l'évenement par son id. On leve une exception 404 Not Found si evenement n'existe pas
        try {
            $event = Event::findOrFail($event_id);
        } catch (ModelNotFoundException $e) {
            // En cas d'erreur, on retourner une réponse JSON avec le message d'erreur
            return response()->json([
                'errors' => $e->getMessage()
            ], HttpStatus::NOT_FOUND);
        }

        // On crée un validateur pour le formulaire que nous reçevons.
        $validator_data= Validator::make(
            $request->all(), 
            [
                'payment' => 'required|string|max:100',
                'info' => 'required|string',
                'tickets' => 'required|array',
                'tickets.*.email' => 'required|email|max:255',
                'tickets.*.phone' => 'required|string|max:20',
                'tickets.*.ticket_type_id' => 'required|integer|exists:tickets_types,id',
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
            // On Créer une nouvelle instance de commande avec les données validées
            $id = Order::count('id')+1;
            $order = Order::create([
                'price' => $ordersIntent->price,
                'type' => $ordersIntent->type,
                'payment' => $validated_data['payment'],
                'info' => $validated_data['info'],
                'event_id' => $event->id,
                'number' => AppServices::generateOrdreNumber($id),
                'user_id' => $request->user()->id,
            ]);

            
            $base_path = storage_path('app/public/pdf');
            if (!file_exists($base_path)) {
                mkdir($base_path, 0755, true);
            }

            // On crée tous les tickets de la commande
            foreach ($validated_data['tickets'] as $ticket_data) {
                $id = Ticket::count('id')+1;
                $ticket_type = TicketsType::find($ticket_data['ticket_type_id']);
                Ticket::create([
                    'email' => $ticket_data['email'],
                    'phone' => $ticket_data['phone'],
                    'price' => $ticket_type->price,
                    'key' => AppServices::generateTypeKey($id),
                    'status' => 'validated',
                    'type_id' => $ticket_type->id,
                    'event_id' => $event->id,
                    'order_id' => $order->id,
                ]); 
            }

            // Créer le chemin de stockage du pdf
            $name = "/$order->number.pdf";
            $path = $base_path.$name;

            // Créer les lien d'accès au pdf
            $tickets_url = "/storage/public/pdf".$name;

            // Sauvegarder le pdf contenant les tickets
            (new LaraTeX('latex.ticket'))->with([
                'order' => $order,
                'event' => $event,
                'tickets' => $order->tickets,
            ])->savePdf($path);

            // Si tout se passe bien, on valide la transaction
            DB::commit();
        
            // On retourner une réponse JSON de succès
            return response()->json([
                'message' => 'Order created successfully',
                'tickets_url' => $tickets_url,
            ], HttpStatus::CREATED);
        
        } catch (\Exception $e) {

            // En cas d'erreur, on annule la transaction
            DB::rollBack();
        
            // On retourner une réponse JSON avec le message d'erreur
            return response()->json([
                'errors' => $e,
            ], HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
