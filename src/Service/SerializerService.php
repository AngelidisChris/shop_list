<?php

namespace App\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class SerializerService
{
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize(mixed $items, array $groups = null, string $format = 'json'): object
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        if ($groups){
            $context->setGroups($groups);
        }

        return json_decode($this->serializer->serialize($items, $format, $context));
    }
}