<?php

namespace App\Service\Serializer;

use App\Event\ValidateDtoEvent;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DTOSerializer implements SerializerInterface
{

    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
        $this->serializer = new Serializer(
            normalizers: [
                new ArrayDenormalizer(),
                new ObjectNormalizer(
                    classMetadataFactory: new ClassMetadataFactory(new AttributeLoader()),
                    propertyTypeExtractor: new PropertyInfoExtractor([],
                        [new PhpDocExtractor(), new ReflectionExtractor()])
                )
            ],
            encoders: [new JsonEncoder()],
        );
    }

    private SerializerInterface $serializer;

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize(
        mixed $data,
        string $type,
        string $format,
        array $context = [],
        bool $validate = true,
        array|string|null $validationGroups = null
    ): mixed {
        $dto = $this->serializer->deserialize($data, $type, $format, $context);

        if ($validate) {
            $this->validateDto($dto, $validationGroups);
        }
        return $dto;
    }

    public function validateDto(object $dto, string|array|null $groups = null): object
    {
        $event = new ValidateDtoEvent($dto, $groups);
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $dto;
    }

    public function filter(mixed $responseData, string $dtoClassName): string
    {
        $filteredResponseData = $this->deserialize(
            json_encode($responseData),
            $dtoClassName,
            format: 'json',
            validate: false
        );
        return $this->serialize($filteredResponseData, format: 'json');
    }

    public function toArray(object $object, array $context = [])
    {
        return $this->serializer->normalize($object, context: $context);
    }


    public function fromData(mixed $data, string $type, ?string $format = null, ?array $context = [])
    {
        return $this->serializer->denormalize($data,$type, $format, $context);
    }

}