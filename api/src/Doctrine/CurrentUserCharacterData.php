<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Character;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class CurrentUserCharacterData implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private Security $security;
    private AuthorizationCheckerInterface $auth;

    public function __construct(Security $security, AuthorizationCheckerInterface $checker)
    {
        $this->security = $security;
        $this->auth = $checker;
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
        $user = $this->security->getUser();
        if (Character::class === $resourceClass
            && !$this->auth->isGranted('ROLE_ADMIN')
        ) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere("$rootAlias.ownedBy = :user");
            $queryBuilder->setParameter('user', $user);
        }
    }

    /**
     * @return mixed
     */
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     * @return mixed
     */
    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        string $operationName = null,
        array $context = []
    ) {
        $this->addWhere($queryBuilder, $resourceClass);
    }
}
