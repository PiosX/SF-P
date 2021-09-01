<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Doctrine\DBAL\Types\TimeType;
use Doctrine\ORM\Query\AST\Functions\CurrentTimestampFunction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType as TypeTimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'register')]
class RegistrationController extends AbstractController
{
    #[Route('/emailsignup', name: 'emailsignup')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Email',
                    'class' => 'email-field'
                )
            ))
            ->add('full_name', null, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Full Name'
                )
            ))
            ->add('login', null, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Username'
                )
            ))
            ->add('password', PasswordType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Password'
                )
            ))
            ->add('Register', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-rg btn-primary'
                )
            ))

            ->getForm()
        ;

        $form->handleRequest($request);
        
        if($form->isSubmitted())
        {
            $data = $form->getData();

            $user =new User();
            $user->setEmail($data['email']);
            $user->setFullname($data['full_name']);
            $user->setLogin($data['login']);
            $user->setPassword(
                $userPasswordHasherInterface->hashPassword($user, $data['password'])
            );
            dump($user);
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
