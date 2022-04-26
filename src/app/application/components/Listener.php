<?php

namespace App\Application\Components;

use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use GuzzleHttp\Client;

/**
 * listener class
 *
 * Handle the update events and returns the response to the given url
 */
class listener extends Injectable
{
    public function product(
        Event $event,
        loader $component
    ) {
        $this->logger->info("Event was fired!!");
        $webhookdata = $this->mongo->webhooks->find()->toArray();
        $products = $this->mongo->products->find()->toArray();
        $client = new Client();
        foreach ($webhookdata as $value) {
            // print_r($value["url"]."<br>");
            $client->request('POST', $value["url"], [
                'form-params' => [
                    'data' => json_encode($products),
                ]
            ]);
        }
    }
}
