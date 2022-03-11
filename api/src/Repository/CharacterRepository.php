<?php

namespace App\Repository;

use App\Entity\Character;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;
    private Security $security;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        Security $security
    ) {
        $this->manager = $manager;
        parent::__construct($registry, Character::class);
        $this->security = $security;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Character $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Character $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws \Exception
     */
    public function getNextFight(Character $character)
    {
        /** @var User $user */
        $result = $this->getPotentialOpponents($character, 1);
        $characterSameRank = array_filter($result, function ($v, $k) use ($character) {
            return $v['rank'] == $character->getRank();
        }, ARRAY_FILTER_USE_BOTH);

        $charactersSelected = empty($characterSameRank) ? $result : $characterSameRank;

        $dealersMin = min(array_column($charactersSelected, 'count'));

        $charactersSelectedMinCountFight = array_filter($charactersSelected, function ($v, $k) use ($dealersMin) {
            return $v['count'] == $dealersMin;
        }, ARRAY_FILTER_USE_BOTH);

        $nbCharactersSelected = sizeof($charactersSelectedMinCountFight);
        if ($nbCharactersSelected > 1) {
            $charactersKey = array_keys($charactersSelected);
            $selectedOpponent[] = $charactersSelected[$charactersKey[random_int(0, $nbCharactersSelected - 1)]];
        } else {
            $selectedOpponent = $charactersSelected;
        }
        return $selectedOpponent;
    }

    private function getPotentialOpponents(Character $character, $rankDiff): array
    {
        $user = $this->security->getUser();
        $query = 'SELECT c.*, count(cf.fight_id) FROM character c 
            LEFT JOIN character_fight cf ON c.id = cf.character_id 
                AND fight_id in 
                    (SELECT fight_id from character_fight WHERE character_id IN 
                        (SELECT id FROM character WHERE owned_by_id = :userId)
                    ) 
            WHERE (rank = :rank OR rank = :rank - :rankDiff OR rank = :rank + :rankDiff)
                AND owned_by_id != :userId
                AND (delay IS NULL OR delay < NOW()) 
            GROUP BY c.id
            ORDER BY count ASC;';
        $connection = $this->manager->getConnection();
        $result = $connection->prepare($query)
            ->executeQuery(['userId' => $user->getId(), 'rank' => $character->getRank(), 'rankDiff' => $rankDiff])
            ->fetchAllAssociative();

        if (empty($result) && $rankDiff < 50) {
            $this->getPotentialOpponents($character, $rankDiff++);
        }

        if ($rankDiff == 50) {
            return ['error' => 'Aucun adversaire trouvé pour ce personnage'];
        }

        return $result;
    }

}
