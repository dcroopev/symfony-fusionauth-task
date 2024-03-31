<?php

namespace App\Filter;

use App\Service\Serializer\DTOSerializer;

class DtoSerializerFilter
{

    public function __construct(private DTOSerializer $serializer)
    {
    }

    public function filter(mixed $responseData, string $dtoClassName): string
    {
        $filteredResponseData = $this->serializer->deserialize(
            json_encode($responseData),
            $dtoClassName,
            format: 'json',
            validate: false
        );
        return $this->serializer->serialize($filteredResponseData, format: 'json');
    }

}