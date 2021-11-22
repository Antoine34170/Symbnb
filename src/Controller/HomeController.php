<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/hello/{name}/age/{age}", name="hello_full")
     * @Route("/hello/{name}/{age}", name="hello_name_age") 
     * Montre la page qui dit bonjour
     * 
     * @return void
     */

    public function hello(string $name = "default_name", int $age = 18)
    {
        return $this->render('home/hello.html.twig', [
            'name' => $name,
            'age' => $age,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function index(AdRepository $adRepository, UserRepository $userRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'bestAds' => $adRepository->findBestAds(3),
            'bestUsers' => $userRepository->findBestUsers(2)
        ]);
    }
}
