<?php

namespace App\Actors;

use App\Actors\Providers\MojaloopProvider;
use App\Contracts\ActorFactory;
use Illuminate\Support\Manager;

class ActorManager extends Manager implements ActorFactory
{
    /**
     * @return MojaloopProvider
     */
    protected function createMojaloopDriver()
    {
        $config = $this->container->get('config')->get('services.mojaloop');
        return new MojaloopProvider();
    }

    public function getDefaultDriver()
    {
        return 'mojaloop';
    }
}
