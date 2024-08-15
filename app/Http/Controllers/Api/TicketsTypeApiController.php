<?php

namespace App\Http\Controllers\Api;

use App\Http\Constants\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\TicketTypeResource;
use App\Models\Event;
use App\Models\TicketsType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TicketsTypeApiController extends Controller
{
      /**
     * @OA\Get(
     *     path="/events/ticket/types/:event_id",
     *     operationId="index",
     *     tags={"TicketsType By Event"},
     *     summary="Get List Of TicketsType By Event",
     *     description="Returns a paginated list of available tickets type by Event.",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number to retrieve.",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="The number of events per page.",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=10
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="VIP"),
     *                     @OA\Property(property="price", type="integer", example=15000),
     *                     @OA\Property(property="quantity", type="integer", example=1000),
     *                     @OA\Property(property="real_quantity", type="integer", example=950),
     *                     @OA\Property(property="total_quantity", type="integer", example=1350),
     *                     @OA\Property(property="description", type="string", example="Dolore atque consectetur ut numquam..."),
     *                     @OA\Property(property="event_id", type="integer", example=3),
     *                )
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", format="url", example="http://localhost:8000/api/events/ticket/types/:event_id?page=1"),
     *                 @OA\Property(property="last", type="string", format="url", example="http://localhost:8000/api/events/ticket/types/:event_id?page=50"),
     *                 @OA\Property(property="prev", type="string", format="url", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", format="url", example="http://localhost:8000/api/events/ticket/types/:event_id?page=2")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=50),
     *                 @OA\Property(property="links", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", format="url", nullable=true, example=null),
     *                         @OA\Property(property="label", type="string", example="&laquo; Previous"),
     *                         @OA\Property(property="active", type="boolean", example=false)
     *                     )
     *                 ),
     *                 @OA\Property(property="path", type="string", format="url", example="http://localhost:8000/api/events/ticket/types/:event_id"),
     *                 @OA\Property(property="per_page", type="integer", example=2),
     *                 @OA\Property(property="to", type="integer", example=2),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No events found")
     *         )
     *     )
     * )
    **/

    public function index(int $event_id)
    {
        // On essai de trouver l'évenement par son id. On leve une exception 404 Not Found si evenement n'existe pas
        try {
            $event = Event::findOrFail($event_id);
        } catch (ModelNotFoundException $e) {
            // En cas d'erreur, on retourner une réponse JSON avec le message d'erreur
            return response()->json([
                'errors' => $e->getMessage()
            ], HttpStatus::NOT_FOUND);
        }

        // Si l'événement existe, on récupère les types de tickets avec une quantité réelle > 0
        $perPage = 10;
        $tickets_types = $event->typeTickets()
                                ->where('real_quantity', '>', 0)
                                ->paginate($perPage);
        
        // On retourne les types de tickets en utilisant une ressource
        return TicketTypeResource::collection($tickets_types);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketsType $ticketsType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketsType $TicketsType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketsType $TicketsType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketsType $TicketsType)
    {
        //
    }
}
