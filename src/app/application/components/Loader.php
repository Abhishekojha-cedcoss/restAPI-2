<?php

declare(strict_types=1);

namespace App\Application\Components;

use Phalcon\Di\Injectable;
use Phalcon\Events\ManagerInterface;

final class Loader extends Injectable
{
    /**
     * eventsManager variable
     *
     * @var object
     */
    protected $eventsManager;

    public function getEventsManager()
    {
        return $this->eventsManager;
    }
    public function setEventsManager(ManagerInterface $eventsManager): void
    {
        $this->eventsManager = $eventsManager;
    }

    public function update(): object
    {
        return $this->eventsManager->fire('notifications:productUpdate', $this);
    }

    public function add(): object
    {
        return $this->eventsManager->fire('notifications:productAdd', $this);
    }
}