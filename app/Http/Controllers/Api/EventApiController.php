<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    
    /**
     * @OA\Get(
     *     path="/events",
     *     operationId="EventApiController.index",
     *     tags={"Events"},
     *     summary="Get List Of Events",
     *     description="Returns a paginated list of events, ordered by date in descending order.",
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
     *                     @OA\Property(property="category", type="string", example="Formation"),
     *                     @OA\Property(property="title", type="string", example="consequatur expedita totam"),
     *                     @OA\Property(property="description", type="string", example="Dolore atque consectetur ut numquam..."),
     *                     @OA\Property(property="date", type="string", format="date-time", example="2025-08-12 23:52:26"),
     *                     @OA\Property(property="image", type="string", format="url", example="https://via.placeholder.com/640x480.png/008822?text=events+Faker+ut"),
     *                     @OA\Property(property="city", type="string", example="West Weldonside"),
     *                     @OA\Property(property="address", type="string", example="522 Walsh Ramp Apt. 639\nHumbertoport, IL 40577"),
     *                     @OA\Property(property="status", type="string", example="upcoming")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", format="url", example="http://localhost:8000/api/events?page=1"),
     *                 @OA\Property(property="last", type="string", format="url", example="http://localhost:8000/api/events?page=50"),
     *                 @OA\Property(property="prev", type="string", format="url", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", format="url", example="http://localhost:8000/api/events?page=2")
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
     *                 @OA\Property(property="path", type="string", format="url", example="http://localhost:8000/api/events"),
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

    public function index(Request $request)
    {
        $perPage = 10;

        // On récupère les évenments ordonnée par date décroissant
        $events = Event::orderBy('date','desc')
                        ->with('typeTickets')
                        ->paginate($perPage);

        // On retourne les évenments en utilisant une ressource
        return EventResource::collection($events);
    }

    /**
     * @OA\Get(
     *     path="/events/upcoming",
     *     operationId="indexUpcoming",
     *     tags={"Upcoming Events"},
     *     summary="Get List Of Upcoming Events",
     *     description="Returns a paginated list of events, ordered by date in descending order.",
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
     *                     @OA\Property(property="category", type="string", example="Formation"),
     *                     @OA\Property(property="title", type="string", example="consequatur expedita totam"),
     *                     @OA\Property(property="description", type="string", example="Dolore atque consectetur ut numquam..."),
     *                     @OA\Property(property="date", type="string", format="date-time", example="2025-08-12 23:52:26"),
     *                     @OA\Property(property="image", type="string", format="url", example="https://via.placeholder.com/640x480.png/008822?text=events+Faker+ut"),
     *                     @OA\Property(property="city", type="string", example="West Weldonside"),
     *                     @OA\Property(property="address", type="string", example="522 Walsh Ramp Apt. 639\nHumbertoport, IL 40577"),
     *                     @OA\Property(property="status", type="string", example="upcoming")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", format="url", example="http://localhost:8000/api/events?page=1"),
     *                 @OA\Property(property="last", type="string", format="url", example="http://localhost:8000/api/events?page=50"),
     *                 @OA\Property(property="prev", type="string", format="url", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", format="url", example="http://localhost:8000/api/events?page=2")
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
     *                 @OA\Property(property="path", type="string", format="url", example="http://localhost:8000/api/events"),
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
    public function indexUpcoming(Request $request)
    {
        $perPage = 10;

        // On récupère les évenments en cours (dont le status est upcoming ) ordonnée par date décroissant
        $events = Event::where('status', 'upcoming')
                        ->orderBy('date','asc')
                        ->paginate($perPage);
        
        // On retourne les évenments en utilisant une ressource
        return EventResource::collection($events);
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
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
