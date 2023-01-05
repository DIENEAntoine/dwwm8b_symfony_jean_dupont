<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use DateTimeImmutable;
use App\Form\PostFormType;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/admin/post/list', name: 'admin.post.index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([], ['createdAt' => 'desc']);
        return $this->render('pages/admin/post/index.html.twig', compact('posts'));
    }


    #[Route('/admin/post/create', name: 'admin.post.create')]
    public function create(Request $request, 
        PostRepository $postRepository, 
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
        ): Response
    {

        if ( ! $categoryRepository->findAll() ) 
        {
            $this->addFlash("warning", "Vous devez créer au moins une catégorie avant de rédiger des articles.");
            return $this->redirectToRoute('admin.category.index');
        }

        $post = new Post(); 
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $post->setAuthor($this->getUser());
            $postRepository->save($post, true);
            $this->addFlash("success", "Votre article a été créé!");
            return $this->redirectToRoute("admin.post.index");
        }
        
        return $this->render('pages/admin/post/create.html.twig', [
            "form" => $form->createView(),
            "tags" => $tagRepository->findAll(),
        ]);
    }


    /**
     * Cette méthode permet de lire l'article
     * dont l'identifiant est passé en paramétre à la route
     */
    #[Route('/admin/post/{id<[0-9]+>}/show', name: 'admin.post.show')]
    public function show(Post $post): Response
    {
        // dd($post);
        return $this->render("pages/admin/post/show.html.twig", compact('post'));
    }

    #[Route('/admin/post/{id<[0-9]+>}/publish', name: 'admin.post.publish')]
    public function publish(Post $post, PostRepository $postRepository): Response
    {
        if ( $post->isIsPublished() == false ) 
        {
            $post->setIsPublished(true);
            $post->setPublishedAt(new \DateTimeImmutable('now'));
            $postRepository->save($post, true);
            $this->addFlash("success", "Votre article vient d'être publié");
        }
        else 
        {
            $post->setIsPublished(false);
            $post->setPublishedAt(null);
            $postRepository->save($post, true);
            $this->addFlash("success", "Votre article vient d'etre retiré de la liste de publication.");
        }

        return $this->redirectToRoute('admin.post.index');
    }


    #[Route('/admin/post/{id<[0-9]+>}/edit', name: 'admin.post.edit')]
    public function edit(
        Post $post, 
        Request $request, 
        PostRepository $postRepository, 
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        ): Response
    {

        if ( ! $categoryRepository->findAll() ) 
        {
            $this->addFlash("warning", "Vous devez créer au moins un tag avant .");
            return $this->redirectToRoute('admin.category.index');
        }

        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            $post->setAuthor($this->getUser());
            $postRepository->save($post, true);
            $this->addFlash("success", "L'article numéro " . $post->getId() . " a été modifié");
            return $this->redirectToRoute("admin.post.index");
        }

        return $this->render("pages/admin/post/edit.html.twig", [
            "form" => $form->createView(),
            "post" => $post,
            "tags" => $tagRepository->findAll(),
        ]);
    }


    #[Route('/admin/post/{id<[0-9]+>}/delete', name: 'admin.post.delete')]
    public function delete(Post $post, Request $request, PostRepository $postRepository) : Response
    {
        if ( $this->isCsrfTokenValid('post_' . $post->getId(), $request->request->get('_csrf_token')) ) 
        {
            $this->addFlash("success", "L'article numéro " . $post->getId() . " a été supprimé;");
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute("admin.post.index");
    }

    

}
