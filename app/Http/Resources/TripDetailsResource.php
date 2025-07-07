<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class TripDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape(['id' => "mixed", 'type' => "mixed", 'regiment_name' => "mixed", 'dates' => "array", 'price' => "mixed", 'hotels' => "mixed", 'transports' => "mixed", 'guides' => "mixed"])] public function toArray($request)
{
    return [
        'id' => $this->id,
        'type' => $this->type,
        'regiment_name' => $this->regiment_name,
        'dates' => [
            'start' => $this->start_date,
            'end' => $this->end_date,
        ],
        'price' => $this->price,
        'hotels' => $this->hotel_trips->map(function($hotelTrip) {
            return [
                'place' => $hotelTrip->place,
                'hotel_name' => $hotelTrip->hotel->hotel_name,
                'rate' => $hotelTrip->hotel->rate,
                'address' => $hotelTrip->hotel->address,
            ];
        }),
        'transports' => $this->transport_trips->map(function($transportTrip) {
            return [
                'company_name' => $transportTrip->transport->company_name,
                'type' => $transportTrip->transport->transport_type,
                'description' => $transportTrip->transport->description,
                'seats' => optional($transportTrip->transport)->seats
                    ? $transportTrip->transport->seats->map(function($seat) {
                        return [
                            'seat' => $seat->seat,
                            'price' => $seat->price,
                        ];
                    }) : [],
            ];
        }),
        'guides' => $this->guides->map(function($guide) {
            return [
                'name' => $guide->user->name,
                // other guide info
            ];
        }),
        // Add more fields as needed
    ];
}
}
