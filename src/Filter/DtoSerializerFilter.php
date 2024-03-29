<?php

namespace App\Filter;

use App\Service\Serializer\DTOSerializer;

class DtoSerializerFilter
{

    public function __construct(private DTOSerializer $serializer)
    {
    }

    public function filter($responseData, string $dtoClassName, string $format = 'json'): string
    {
        $filteredResponseData = $this->serializer->deserialize(json_encode($responseData), $dtoClassName, $format);
        return $this->serializer->serialize($filteredResponseData, $format);
    }

}