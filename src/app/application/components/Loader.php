<?php

namespace App\Application\Components;

use Phalcon\Di\Injectable;
use Phalcon\Events\ManagerInterface;

class loader extends Injectable
{
    protected $eventsManager;

    public function getEventsManager()
    {
        return $this->eventsManager;
    }
    
    public function setEventsManager(ManagerInterface $eventsManager)
    {
        $this->eventsManager = $eventsManager;
    }

    public function update()
    {
        return $this->eventsManager->fire('notifications:productUpdate', $this);
    }

    public function add()
    {
        return $this->eventsManager->fire('notifications:productAdd', $this);
    }
}