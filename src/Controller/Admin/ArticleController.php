<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/redactor/article/create', name: 'app_article_create')]
    public function createArticle(): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_REDACTEUR')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('article/new.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    #[Route('/redactor/article/update', name: 'app_article_update')]
    public function updateArticle(): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_REDACTEUR')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('article/update.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    #[Route('/redactor/article/delete', name: 'app_article_delete')]
    public function deleteArticle(): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_ADMIN')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('article/delete.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

}
