<?php

namespace App\Service;

class ResponseService
{
    private $serializer;
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
 /*   public function noCircResp($data,$controller)
    {
        return $controller->json($data, Response::HTTP_OK, [], [
            ObjectNormalizer::SKIP_NULL_VALUES => true,
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }*/
}
$containerBuilder = new ContainerBuilder();
$containerBuilder->register('ResponseService','ResponseService');