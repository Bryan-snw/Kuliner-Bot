<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\RestaurantService;
use App\Services\MovieService;

class TelegramController extends Controller
{
    private $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    public function test()
    {
        $response = Telegram::getMe();
        return $response;
    }
    public function getUpdate()
    {
        $response = Telegram::getUpdates();
        return $response;
    }
    public function webhook()
    {
        // Handle incoming updates
        $updates = Telegram::commandsHandler(true);

        // Check if the update contains a message
        if (isset($updates['message'])) {

            // Extract chat ID
            $chatId = $updates['message']['chat']['id'];
            // Extract Message
            $messageText = $updates['message']['text'];

            $locationId = $this->restaurantService->getLocationId($messageText);

            $restaurant = $this->restaurantService->searchRestaurant($locationId);

            $name = $restaurant['name'];
            $rating = $restaurant['averageRating'];
            $reviewCount = $restaurant['userReviewCount'];
            $replyMessage = "Restaurant Name: $name\nRating: $rating\nReview Count: $reviewCount";

            Telegram::sendMessage(['chat_id' => $chatId, 'text' => $replyMessage]);

            // This code below supposed to be given but somehow the api is slow and it always give and error

            // $restaurantId = $restaurant['restaurantsId'];
            // $restaurantId = $this->restaurantService->searchRestaurant($locationId);
            // $restaurant = $this->restaurantService->getRestaurantDetail($restaurantId);

            // $name = $restaurant['location']['name'];
            // $ranking = $restaurant['location']['ranking'];
            // $rating = $restaurant['location']['rating'];
            // $url = $restaurant['location']['web_url'];
            // $address = $restaurant['overview']['contact']['address'];
            // $phone = $restaurant['overview']['contact']['phone'];
            // $replyMessage = "Restaurant Name: $name\n$ranking\nRating: $rating\nAddress: $address\nPhone: $phone\n$url";

            // Telegram::sendMessage(['chat_id' => $chatId, 'text' => $replyMessage]);
        }

        return response()->json(['success' => true]);
    }
    public function setWebhook()
    {
        $ngrok_url = "https://45e1-2001-448a-7030-1047-94e3-144a-8f22-d554.ngrok-free.app";
        $webhook_url = "$ngrok_url/api/webhook";
        Telegram::setWebhook(['url' => $webhook_url]);
    }
    public function delWebhook()
    {
        return Telegram::deleteWebhook();
    }
}
