<?php

namespace App\Manager;

use App\Entity\Comment;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CommentManager extends AbstractManager
{
    protected EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);

        $this->repository = $this->getEntityManager()->getRepository(Comment::class);
    }

    public function findAll(): ?array
    {
        return $this->repository->findBy([], ['id' => 'DESC']);
    }
}
