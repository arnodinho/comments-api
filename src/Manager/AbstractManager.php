<?php

namespace App\Manager;

use App\Entity\StorableEntityInterface;
use Doctrine\ORM\EntityManagerInterface;

class AbstractManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }

    public function save(StorableEntityInterface $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function delete(StorableEntityInterface $entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
