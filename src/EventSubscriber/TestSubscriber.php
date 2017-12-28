<?php
declare(strict_types = 1);
/**
 * /src/EventSubscriber/BodySubscriber.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventSubscriber;

use App\Utils\JSON;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class BodySubscriber
 *
 * @package App\EventSubscriber
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BodySubscriber
{
    /**
     * Implementation of BodySubscriber event. Purpose of this is to convert JSON request data to proper request
     * parameters.
     *
     * @param GetResponseEvent $event
     *
     * @throws \LogicException
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        // Get current request
        $request = $event->getRequest();

        // Request content is empty so assume that it's ok - probably DELETE or OPTION request
        if (empty($request->getContent())) {
            return;
        }

        // If request is JSON type convert it to request parameters
        if ($this->isJsonRequest($request)) {
            $this->transformJsonBody($request);
        }
    }

    /**
     * Method to determine if current Request is JSON type or not.
     *
     * @param Request $request
     *
     * @return bool
     */
    private function isJsonRequest(Request $request): bool
    {
        return \in_array($request->getContentType(), [null, 'json', 'txt'], true);
    }

    /**
     * Method to transform JSON type request to proper request parameters.
     *
     * @param Request $request
     *
     * @return void
     *
     * @throws \LogicException
     */
    private function transformJsonBody(Request $request): void
    {
        $data = JSON::decode((string)$request->getContent(), true);

        $request->request->replace($data);
    }
}
