<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\UserAddedEvent;
use App\Event\AppDomainEvents;

class UserController extends AbstractController
{
    #[Route('/register', name: 'user_register')]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, EventDispatcherInterface $eventDispatcher): Response
    {
        $user = new User();
        $user->setFirstName('Elon');
        $user->setLastName('MUSK');
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $manager->flush();

            $eventDispatcher->dispatch(new UserAddedEvent($user), AppDomainEvents::USER_ADDED);
            dump($user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form,
        ]);
    }
}
