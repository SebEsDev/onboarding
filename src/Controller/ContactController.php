<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Department;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function index(Request $request, ContactNotification $notification): Response
    {
        $contact = New Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($contact);
            $this->em->flush();
            $notification->notify($contact);
            $this->addFlash('success', 'Demande de contact envoyée');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'current_menu' => 'contact',
            'form' => $form->createView()
        ]);
    }
}