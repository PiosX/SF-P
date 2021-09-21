<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Form\ProfileType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileController extends AbstractController
{
    #[Route('/profile/{login}', name: 'profile')]
    public function index(User $user, Request $request, $login, UserRepository $userRepository, FileUploader $fileUploader): Response
    {
        $users = new User();
        $posts = new Post();

        $userId = $user->getId();

        $userPosts = $userRepository->findUserWithPost($userId);

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
                $filename = $fileUploader->uploadFile($file, 'uploads_av');
                
                $users->setAvatar($filename);
                $em->flush();
            }
        }

        if($postForm->isSubmitted() && $postForm->isValid())
        {
            $em2 = $this->getDoctrine()->getManager();

            /** @var UploadedFile $file */
            $postFile = $postForm->get('image')->getData();
            if($postFile)
            {
                $postFileName = $fileUploader->uploadFile($postFile, 'uploads_posts');

                $posts->setImage($postFileName);
                $posts->setCategory($user);

                $em2->persist($posts);
                $em2->flush();
            }   
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'posts' => $posts,
            'userPosts' => $userPosts,
            'form' => $form->createView(),
            'post' => $postForm->createView(),
        ]);
    }

    #[Route('/show/{login}', name: 'show')]
    public function show(User $user, UserRepository $userRepository): Response
    {
        $userId = $user->getId();

        $users = $userRepository->findUserWithPost($userId);

        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'users' => $users
        ]);
    }
}
