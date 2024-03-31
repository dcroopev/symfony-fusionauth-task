<?php

namespace App\EventSubscriber;

use App\Event\ValidateDtoEvent;
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
            ValidateDtoEvent::NAME => 'validate'
        ];
    }

    public function validate(ValidateDtoEvent $event): void
    {
        $dto = $event->getDto();
        $groups = $event->getGroups();

        $errors = $this->validator->validate($dto, groups: $groups);

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