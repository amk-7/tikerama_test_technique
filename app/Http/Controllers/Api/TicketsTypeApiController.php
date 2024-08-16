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
    *     path="/events/ticket/types/{event_id}",
    *     operationId="TicketsTypeApiController.index",
    *     tags={"TicketsType By Event"},
    *     summary="Get List Of TicketsType By Event",
    *     description="Returns a paginated list of available tickets type by Event.",
    *     security={{"sanctum":{}}},
    *     @OA\Parameter(
    *         name="event_id",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="integer"),
    *         description="ID de l'événement"
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful retrieval of ticket types",
    *        @OA\MediaType(
     *           mediaType="application/json",
    *         )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Event not found",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(
    *                 property="errors",
    *                 type="string",
    *                 example="No query results for model [App\\Models\\Event] 1"
    *             )
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
