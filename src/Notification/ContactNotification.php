<?php
namespace App\Notification;

use App\Entity\Contact;
use App\Entity\Department;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class ContactNotification {

    public $departmentRepository;
    private $mailer;
    private $renderer;
    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, Environment $renderer)
    {
        $this->departmentRepository = $em->getRepository(Department::class);
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact){
        $department = $this->departmentRepository->findOneBy(['id'=>$contact->getRecipient()]);

        $message = (new \Swift_Message('Nouvelle demande de contact'))
            ->setFrom('noreply@onboarding.fr')
            ->setTo($department->getMail())
            ->setReplyTo($contact->getMail())
            ->setBody($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contact
            ]), 'text/html');

        $this->mailer->send($message);
    }
}