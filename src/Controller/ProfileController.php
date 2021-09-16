<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileController extends AbstractController
{
    #[Route('/profile/{login}', name: 'profile')]
    public function index(User $user, Request $request, $login): Response
    {
        $users = new User();
        $posts = new Post();

        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['login' => $login]);

        $form = $this->createForm(ProfileType::class, $users);
        $postForm = $this->createForm(PostType::class, $posts);

        $postForm->handleRequest($request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();

            /** @var UploadedFile $file */
            $file = $form->get('avatar')->getData();
            if($file){
                $filename = md5(uniqid()).'.'.$file->guessClientExtension();

                $file->move(
                    $this->getParameter('uploads_av'),
                    $filename
                );
                
                $users->setAvatar($filename);
                $em->flush();
            }
        }

        if($postForm->isSubmitted() && $postForm->isValid())
        {
            $em2 = $this->getDoctrine()->getManager();

            $posts->setCategory($user);

            $em2->persist($posts);
            $em2->flush();
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'post' => $postForm->createView(),
        ]);
    }
}
