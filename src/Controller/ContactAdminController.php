<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[Route('/admin/contact')]
// #[IsGranted('ROLE_ADMIN')]
class ContactAdminController extends AbstractController
{
    #[Route('/', name: 'app_contact_admin')]
    public function index(ContactRepository $contact): Response
    {

        $stock = $contact->findBy(['status' => 'non lu']);


        return $this->render('contact_admin/index.html.twig', [
            'controller_name' => 'ContactAdminController',
            'form' => $stock
            
        ]);
    }


}