<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserManager extends AbstractManager
{
    protected EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);

        $this->repository = $this->getEntityManager()->getRepository(User::class);
    }

    public function getRepository(): EntityRepository
    {
        return $this->repository;
    }
}
