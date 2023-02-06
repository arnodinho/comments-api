<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Handler\CommentHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CommentController extends AbstractController
{
    #[Route('/api/comments', name: 'app_comments', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour consulter les commentaires')]
    public function getCommentList(CommentHandler $commentHandler): JsonResponse
    {
        $commentList = $commentHandler->getCommentsList();
        $commentList = $commentHandler->getSerializer()->serialize($commentList, 'json', ['groups' => 'list']);

        if (!empty($commentList)) {
            return  new JsonResponse($commentList, Response::HTTP_OK, [], true);
        }

        return  new JsonResponse(null, Response::HTTP_NOT_FOUND, [], true);
    }


    #[Route('/api/comment/{id}', name: 'app_detail_comments', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour consulter un commentaire')]
    public function getDetailComment(Comment $comments, CommentHandler $commentHandler): JsonResponse
    {
        $comments = $commentHandler->getSerializer()->serialize($comments, 'json', ['groups' => 'detail']);

        return new JsonResponse($comments, Response::HTTP_OK, [], true);
    }

    #[Route('/api/comment/{id}', name: 'app_delete_comments', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un commentaire')]
    public function deleteComment(Comment $comment, CommentHandler $commentHandler): JsonResponse
    {
        $commentHandler->getCommentManager()->delete($comment);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    #[Route('/api/comment', name:"app_create_comments", methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour consulter les commentaires')]
    public function createComment(Request $request, CommentHandler $commentHandler): JsonResponse
    {
        $comment = $commentHandler->getSerializer()->deserialize($request->getContent(), Comment::class, 'json');
        $errors = $commentHandler->getValidator()->validate($comment);

        if ($errors->count() > 0) {
            return new JsonResponse($commentHandler->getSerializer()->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        $comment = $commentHandler->saveComment($comment, $request->toArray());
        $jsonComment =  $commentHandler->getSerializer()->serialize($comment, 'json', ['groups' => 'getComments']);

        return new JsonResponse($jsonComment, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/comment/{id}', name:"app_update_comment", methods:['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour modifier un commentaire')]
    public function updateComment(Request $request, Comment $currentComment, CommentHandler $commentHandler): JsonResponse
    {
        $updatedComment = $commentHandler->getSerializer()->deserialize(
            $request->getContent(),
            Comment::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentComment]
        );

        $commentHandler->updateComment($updatedComment, $request->toArray());

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
