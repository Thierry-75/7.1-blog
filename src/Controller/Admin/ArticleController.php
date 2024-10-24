<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Photo;
use App\Form\ArticleFormType;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends AbstractController
{
    #[Route('/redactor/article/create', name: 'app_article_create',methods: ['GET','POST'])]
    public function createArticle(
        SluggerInterface $slugger,
        Request $request,
        ValidatorInterface $validatorInterface,
        EntityManagerInterface $em,
        PhotoService $photoService
        ): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_REDACTEUR')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        $article = new Article();
        $form_article_create = $this->createForm(ArticleFormType::class,$article);
        $form_article_create->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validatorInterface->validate($article);
            if(count($errors) >0){
                return $this->render('article/new.html.twig',['form_article_create'=>$form_article_create,'errors'=>$errors]);
            }
            if($form_article_create->isSubmitted() && $form_article_create->isValid()){
                $photos = $form_article_create->get('photos')->getData();
                foreach($photos as $photo){
                    $folder = 'articles';
                    $fichier = $photoService->add($photo,$folder,300,300);
                    $photo = new Photo();
                    $photo->setName($fichier);
                    $article->addPhoto($photo);
                }
                $article->setUser($this->getUser());
                $article->setSlug($slugger->slug($article->getTitle()));
                try{
                    $em->persist($article);
                    $em->flush();
                }catch(EntityNotFoundException $e){
                    return $this->redirectToRoute('app_error',['exception'=>$e]);
                }
                $this->addFlash('alert-success','Votre article a été enregistré .');
                return $this->redirectToRoute('app_article_all');
            }
        }
        return $this->render('article/new.html.twig', [
            'form_article_create'=>$form_article_create->createView()
        ]);
    }
    #[Route('/redactor/article/update/{id}', name: 'app_article_update',methods: ['GET','POST'])]
    public function updateArticle(
        Article $article, 
        Request $request, 
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        ValidatorInterface $validator,
        PhotoService $photoService
        ): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_REDACTEUR')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        $form_article_update = $this->createForm(ArticleFormType::class,$article);
        $form_article_update->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($article);
            if(count($errors) > 0){
                return $this->render('/redactor/update.html.twig',['form_article_update'=>$form_article_update->createView(),'errors'=>$errors,'article'=>$article]);
            }
            if($form_article_update->isSubmitted() && $form_article_update->isValid()){
                $photos = $form_article_update->get('photos')->getData();
                foreach($photos as $photo){
                    $folder = 'articles';
                    $fichier = $photoService->add($photo,$folder,300,300);
                    $photo = new Photo();
                    $photo->setName($fichier);
                    $article->addPhoto($photo);
                }
                $article->setUser($this->getUser());
                $article->setSlug($slugger->slug($article->getTitle()));
                try{
                    $em->persist($article);
                    $em->flush();
                }catch(EntityNotFoundException $e){
                    return $this->redirectToRoute('app_error',['exception'=>$e]);
                }
                $this->addFlash('alert-success','Votre article a été modifié .');
                return $this->redirectToRoute('app_article_all');
            }
        }
        return $this->render('article/update.html.twig', ['form_article_update'=>$form_article_update->createView(),'article'=>$article]);
    }
    #[Route('/redactor/article/delete{id}', name: 'app_article_delete')]
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
