<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentsType;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentsController extends AbstractController
{
    #[Route('/comments', name: 'app_comments')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        MailerInterface $mailer
    ): Response {
    

        $comment = new Comments();
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request); // permet d'intercepter la requête lancé par la soumission du formulaire

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                $comment->setDate(new \DateTime);
                $entityManager->persist($comment); // insérer en base
                $entityManager->flush(); // fermer la transaction executée par la bdd

                $this->addFlash(
                    'success',
                    'Votre message a bien été envoyé'
                );

                // $email = (new TemplatedEmail())
                // ->from($this->getParameter('app.mailAddress'))
                // ->to('you@example.com')
                // ->cc($contact->getEmail())
                // //->bcc('bvv@exemple.com')
                // //replyTo('fabien@exemple.com)
                // //->priory(Email::PRIORITY_HIGH)
                // ->subject($contact->getObject())
                // ->text('Sending emails in fun again!')
                // // ->html('<p> '  . $contact->getMessage() .  ' </p>');
                // ->htmlTemplate('emails/contact.html.twig')
                // ->context([
                //     'contact' => $contact,
                //     ]);

                // $mailer->send($email);

                // rediriger vers une autre page
                // return $this->redirectToRoute(/* ... */);
            }
             //else {
            //     $errors = $validator->validate($contact);
            // }

        }

        return $this->render('comments/index.html.twig', [
            'commentForm' => 'CommentsController',
        ]);
    }
}
