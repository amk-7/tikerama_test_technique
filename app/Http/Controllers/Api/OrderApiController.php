<?php

namespace App\Http\Controllers\Api;

use App\Http\Constants\HttpStatus;
use App\Http\Controllers\Controller;
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
     * @OA\Get(
     *     path="/events/upcoming",
     *     operationId="indexUpcoming",
     *     tags={"Events"},
     *     summary="Récupérer tous les évenement en cours",
     *     description="Retourne une liste d'évenements en cours ordonnée par date dans l'order  décroissant.",
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *     )
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
                'tickets.*.price' => 'required|numeric',
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
            ]);

            
            $base_path = storage_path('app/public/pdf');
            if (!file_exists($base_path)) {
                mkdir($base_path, 0755, true);
            }
            $urls = [];
            // On crée tous les tickets de la commande
            foreach ($validated_data['tickets'] as $ticket_data) {
                $id = Ticket::count('id')+1;
                $type = TicketsType::firstWhere('name', $ordersIntent->type);
                $ticket = Ticket::create([
                    'email' => $ticket_data['email'],
                    'phone' => $ticket_data['phone'],
                    'price' => $ticket_data['price'],
                    'key' => AppServices::generateTypeKey($id),
                    'status' => 'validated',
                    'type_id' => $type->id,
                    'event_id' => $event->id,
                    'order_id' => $order->id,
                ]);
                $name = "/$ticket->key.pdf";
                $path = $base_path.$name;
                (new LaraTeX('latex.ticket'))->with([
                    'event' => $event,
                    'ticket' => $ticket,
                    'ticket_type' => $type,
                    'order' => $order,
                ])->savePdf($path);
                array_push($urls, "/storage/pdf".$name);
            }

            // Si tout se passe bien, on valide la transaction
            DB::commit();
        
            // On retourner une réponse JSON de succès
            return response()->json([
                'message' => 'Order created successfully',
                'urls' => $urls
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
