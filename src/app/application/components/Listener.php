<?php

namespace App\Application\Components;

use Phalcon\Di\Injectable;
use GuzzleHttp\Client;

/**
 * listener class
 *
 * Handle the update events and returns the response to the given url
 */
class Listener extends Injectable
{
    public function productUpdate(): void
    {
        $this->logger->info('Event was fired!!');
        $webhookdata = $this->mongo->webhooks->find()->toArray();
        $products = $this->mongo->products->find()->toArray();
        $client = new Client();
        foreach ($webhookdata as $value) {
            if ($value['event'] === 'update') {
                // print_r($value['event'].'<br>');
                $client->request('POST', $value['url'], [
                    'form_params' => [
                        'data' => json_encode($products),
                    ]
                ]);
            }
        }
    }
    public function productAdd(): void
    {
        $options = [
            'limit' => 1,
            'sort' => ['_id' => -1]
        ];
        $product = $this->mongo->products->findOne([], $options);
        $webhookdata = $this->mongo->webhooks->find()->toArray();
        $client = new Client();
        foreach ($webhookdata as $value) {
            // print_r($value['url'].'<br>');
            if ($value['event'] === 'add') {
                $client->request('POST', $value['url'], [
                    'form_params' => [
                        'data' => json_encode($product),
                    ]
                ]);
            }
        }
    }
}
