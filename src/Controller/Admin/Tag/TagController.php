<?php

namespace App\Controller\Admin\Tag;

use App\Entity\Tag;
use App\Form\TagFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    #[Route('/admin/tag/list', name: 'admin.tag.index')]
    public function index(): Response
    {
        return $this->render('pages/admin/tag/index.html.twig');
    }


    #[Route('/admin/tag/create', name: 'admin.tag.create')]
    public function create(): Response
    {
        $tag = new Tag();

        $form = $this->createForm(TagFormType::class, $tag);

        return $this->render('pages/admin/tag/create.html.twig', [
            "form" => $form->createView(),
        ]);
        
    }


}
