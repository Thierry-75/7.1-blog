<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_article_all')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    #[Route('/articles', name: 'app_article')]

    #[Route('/article/view/', name: 'app_article_view')]
    public function viewArticle(): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_USER')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('article/view.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

}
