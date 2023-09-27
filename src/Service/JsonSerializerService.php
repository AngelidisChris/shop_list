<?php

namespace App\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class JsonSerializerService
{
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize(object $items, array $groups = null): object
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        if ($groups){
            $context->setGroups($groups);
        }

        return json_decode($this->serializer->serialize($items, JsonEncoder::FORMAT, $context));
    }
}