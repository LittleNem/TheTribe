<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{

    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $encoder;

    /**
     * @param UserPasswordHasherInterface $encoder
     */
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @return array
     */
    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     */
    #[NoReturn]
    public function encodePassword(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($result instanceof User && $method === "POST") {
            $hashedPassword = $this->encoder->hashPassword($result, $result->getPassword());
            $result->setPassword($hashedPassword);
        }
    }
}