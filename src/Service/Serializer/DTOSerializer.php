<?php

namespace App\Service\Serializer;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class DTOSerializer implements SerializerInterface
{

    public function __construct()
    {
        $this->serializer = new Serializer(
            [
                new ObjectNormalizer(
                    classMetadataFactory: new ClassMetadataFactory(new AttributeLoader()),
                    propertyTypeExtractor: new ReflectionExtractor()
                )
            ],
            [new JsonEncoder()],
        );
    }

    private SerializerInterface $serializer;

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        $dto = $this->serializer->deserialize($data, $type, $format, $context);

        //todo inject event dispatcher and create event to validate DTOs on attributes (assertions)

        return $dto;
    }

    public function toArray(object $object)
    {
        return $this->serializer->normalize($object);
    }

}