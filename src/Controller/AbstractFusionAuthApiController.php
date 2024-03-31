<?php

namespace App\Controller;

use App\Filter\DtoSerializerFilter;
use App\Service\FusionAuthResponseHandler;
use App\Service\Serializer\DTOSerializer;
use FusionAuth\FusionAuthClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractFusionAuthApiController extends AbstractController
{
    public function __construct(
        protected DTOSerializer $dtoSerializer,
        protected FusionAuthClient $client,
        protected FusionAuthResponseHandler $fusionAuthResponseHandler,
        protected DtoSerializerFilter $dtoSerializerFilter,
    ) {
    }
}