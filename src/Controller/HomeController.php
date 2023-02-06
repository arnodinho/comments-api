<?php

namespace App\Controller;

use App\Handler\CallApiHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/comments/list', name: 'comments_list')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour consulter les commentaires')]
    public function list(CallApiHandler $CommentApi): Response
    {
        $list = $this->forward('App\Controller\CommentController::getCommentList');

        // récupération depuis une api externe via httpClient
//        $list = $CommentApi->getCommentsList();

        return $this->render('home/list.html.twig', [
          'list' => json_decode($list->getContent(), true)
        ]);
    }

    #[Route('/comments/detail/{id}', name: 'comments_detail')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour consulter les commentaires')]
    public function detailComment(int $id): Response
    {
        $comment = $this->forward('App\Controller\CommentController::getDetailComment', ['id' => $id]);

        return $this->render('home/detail.html.twig', [
            'comment' => json_decode($comment->getContent(), true)
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
