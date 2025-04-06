<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_update_profile')]
    public function profile(
        Request $request,
        ResetPasswordRequestRepository $repository,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $originalEmail = $user->getEmail();
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, [
                'label' => 'Emailadres',
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Opslaan',
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($originalEmail !== $user->getEmail()) {
                $repository->removeResetPasswordRequests($user);
                $this->addFlash('success', 'Email succesvol gewijzigd! Let op: je moet je opnieuw aanmelden met je nieuwe email.');
            } else {
                $this->addFlash('success', 'Profiel bijgewerkt!');
            }

            $em->flush();
            return $this->redirectToRoute('app_update_profile');
        }

        return $this->render('dashboard/profile/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}