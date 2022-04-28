<?php

declare(strict_types=1);

namespace App\Application\Components;

use GuzzleHttp\Client;
use MongoDB\BSON\ObjectID;
use Phalcon\Di\Injectable;

/**
 * listener class
 *
 * Handle the update events and returns the response to the given url
 */
final class Listener extends Injectable
{
    public function productUpdate($data): void
    {
        print_r($data->getData());
        $this->logger->info('Event was fired!!');
        $webhookdata = $this->mongo->webhooks->find()->toArray();
        $products = $this->mongo->products->findOne(['_id' => new objectID($data->getData())]);
        $client = new Client();
        foreach ($webhookdata as $value) {
            if ($value['event'] === 'update') {
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
