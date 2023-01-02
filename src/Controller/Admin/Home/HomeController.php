<?php

namespace App\Controller\Admin\Home;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[IsGranted('ROLE_ADMIN')]
class HomeController extends AbstractController
{
    #[Route('/admin/home/home', name: 'admin.home.index')]
    public function index(): Response
    {
        return $this->render('pages/admin/home/index.html.twig');
    }
}
