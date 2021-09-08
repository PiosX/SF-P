<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{login}', name: 'profile')]
    public function index($login, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(array('login' => $login));

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/{login}', name: 'form')]
    public function addUserImage(Request $request)
    {
        $user = new User();

        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        //$em->persist($user);
        //$em->flush();

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
