<?php

namespace App\EventSubscriber;

use App\Event\CreateDTOEvent;
use App\Service\Exception\ServiceException;
use App\Service\Exception\ValidationExceptionData;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoSubscriber implements EventSubscriberInterface
{

    public function __construct(private ValidatorInterface $validator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateDTOEvent::NAME => 'validate'
        ];
    }

    public function validate(CreateDTOEvent $event): void
    {
        $dto = $event->getDto();

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $validationExceptionData = new ValidationExceptionData(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'ConstraintViolationList',
                $errors
            );

            throw new ServiceException($validationExceptionData);
        }
    }


}