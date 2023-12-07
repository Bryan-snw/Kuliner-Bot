<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RestaurantService
{
    public function getLocationId($query)
    {
        try {
            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'tripadvisor16.p.rapidapi.com',
                'X-RapidAPI-Key' => env('TRIP_API_KEY'),
            ])
                ->get(
                    'https://tripadvisor16.p.rapidapi.com/api/v1/restaurant/searchLocation',
                    ['query' => $query]
                );

            if ($response->successful()) {
                $res = $response->json();
                return $res['data'][0]['locationId'];
            } else {

                return null;
            }
        } catch (\Exception $e) {

            return null;
        }
    }
    public function searchRestaurant($locationId)
    {
        try {
            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'tripadvisor16.p.rapidapi.com',
                'X-RapidAPI-Key' => env('TRIP_API_KEY'),
            ])
                ->get(
                    'https://tripadvisor16.p.rapidapi.com/api/v1/restaurant/searchRestaurants',
                    ['locationId' => $locationId]
                );


            if ($response->successful()) {
                $res = $response->json();
                return $res['data']['data'][0];
            } else {

                return null;
            }
        } catch (\Exception $e) {

            return null;
        }
    }

    public function getRestaurantDetail($restaurantId)
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Host' => 'tripadvisor16.p.rapidapi.com',
            'X-RapidAPI-Key' => env('TRIP_API_KEY'),
        ])
            ->get(
                'https://tripadvisor16.p.rapidapi.com/api/v1/restaurant/getRestaurantDetails',
                ['restaurantsId' => $restaurantId]
            );

        return $response->json();
    }
}
