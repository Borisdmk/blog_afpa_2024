<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comments;
use App\Form\ArticleType;
use App\Form\CommentsType;
use App\Repository\ArticleRepository;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }



    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ImageService $imageService): Response
    {

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileName =$imageService->copyImage("picture", $this->getParameter("article_picture_directory") ,$form);
            $article->setPicture($fileName);
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre article a bien été ajouté'
            );

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}', name: 'app_article_show', methods: ['GET', 'POST'], requirements:['id' => '\d+'])]
    public function show(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comments;
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment->setIdUser($this->getUser());
            $comment->setIdArticle($article);
            $comment->setDate(new \DateTime);
            $comment->setIsVerified(false);
            
            $entityManager->persist($comment);
            $entityManager->flush();


            $this->addFlash(
                'success',
                'Votre commentaire a bien ete ajouté'
            );

        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm' => $form
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, ImageService $imageService): Response
    {
         // Crée un formulaire en utilisant ArticleType pour l'article spécifié
        $form = $this->createForm(ArticleType::class, $article);
        // Gère la requête HTTP
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Copie l'image et récupère le nom du nouveau fichier
            $fileName = $imageService->copyImage("picture", $this->getParameter("article_picture_directory") ,$form);
            // Met à jour le nom de l'image de l'article
            $article->setPicture($fileName);
            // Persiste les changements dans la base de données
            $entityManager->flush();
            // Ajoute un message flash pour informer de la réussite de la modification
            $this->addFlash(
                'success',
                'Votre article a bien été modifié'
            );

            // Redirige vers la page d'index des articles après la modification
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

         // Rend la vue de modification d'article avec le formulaire et les détails de l'article
        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre article a bien été ajouté'
            );
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/category/{id_category}', name: 'app_get_article_by_category', methods: ['GET'])]
    public function getArticleByCategory(EntityManagerInterface $entityManager, int $id_category): Response
    {
        //findBy methode prédefini, permet de recuperer des donées en filtrant,
        $articles = $entityManager->getRepository(Article::class)->findBy(array("category" => $id_category));
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    

}
