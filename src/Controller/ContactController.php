<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager){

    }
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $contact = new Contact();
        $contact->setStatus('non lu');
        $contact->setCreatedAt(new \DateTime());

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $this->addFlash(
                'success', 'Votre demande à été envoyé avec succés !'
            );

            return $this->redirectToRoute('app_contact');
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
