<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;


use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;


class SecurityController extends controller
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPassportInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // pour encoder le mdp
            $hash= $encoder->encodePassword($user,$user->getPassword());
            // pour modifier le mdp par le mdp hashé
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
