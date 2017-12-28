<?php
declare(strict_types = 1);

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class TestSubscriber
{
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $session = new Session();

        $session->set('bar', 'foobar' . time());
    }
}
