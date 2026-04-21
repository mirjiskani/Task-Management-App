<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Find user by email address
     */
    public function findByEmail(string $email): ?Users
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Find all users
     */
    public function findAll(): array
    {
        return parent::findAll();  // Uses Doctrine's built-in findAll()
    }
    
    /**
     * Find active users only
     */
    public function findActiveUsers(): array
    {
        return $this->findBy(['status' => 1]);
    }

    /**
     * Find users by role
     */
    public function findByRole(string $role): array
    {
        return $this->findBy(['role' => $role]);
    }

    /**
     * Save user entity
     */
    public function save(Users $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete user entity
     */
    public function delete(Users $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Count total users
     */
    public function countUsers(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find users created in date range
     */
    public function findByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.createdAt >= :startDate')
            ->andWhere('u.createdAt <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
