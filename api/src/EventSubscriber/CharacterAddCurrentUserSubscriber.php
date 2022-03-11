<?php

namespace App\EventSubscriber;

use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class CharacterAddCurrentUserSubscriber implements EventSubscriberInterface
{

    private Security $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $result = $args->getObject();
        if ($result instanceof Character) {
            if (!$result->getOwnedBy()) {
                $result->setOwnedBy($this->security->getUser());
            }
        }
    }
}