<?php

namespace App\Handler;

use App\Entity\Comment;
use App\Manager\CommentManager;

use App\Manager\UserManager;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentHandler
{
    protected CommentManager $commentManager;
    protected UserManager $userManager;
    protected SerializerInterface $serializer;
    protected ValidatorInterface $validator;

    public function __construct(
        CommentManager $commentManager,
        UserManager $userManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
    ) {
        $this->commentManager = $commentManager;
        $this->userManager = $userManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    public function getCommentsList()
    {
        return $this->commentManager->findAll();
    }

    /**
     * @return CommentManager
     */
    public function getCommentManager(): CommentManager
    {
        return $this->commentManager;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    public function saveComment(Comment $comment, array $data)
    {
        $idUser = $data['user_id'] ?? null;
        $comment->setUser($this->userManager->getRepository()->find($idUser));
        if ($data['note']) {
            $comment->addNote($data['note']);
        }

        $this->commentManager->save($comment);

        return $comment;
    }

    public function updateComment(Comment $comment, array $data): void
    {
        $idUser = $data['user_id'] ?? null;
        $comment->setUser($this->userManager->getRepository()->find($idUser));
        if ($data['note']) {
            $comment->addNote($data['note']);
        }

        $this->commentManager->save($comment);
    }
}
