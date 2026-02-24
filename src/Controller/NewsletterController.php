<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe', methods: ['POST'])]
    public function subscribe(Request $request, MailerInterface $mailer, LoggerInterface $logger, \App\Service\Newsletter\NewsletterService $newsletter): Response
    {
        $this->isCsrfTokenValid('newsletter', $request->request->get('_token')) || $this->denyAccessUnlessGranted('PUBLIC_ACCESS');

        $email = trim((string)$request->request->get('email', ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash('error', 'Email invalide');
            return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('app_home'));
        }

        // MVP: stocker en local et notifier l’admin par email.
        try {
            $dir = $this->getParameter('kernel.project_dir') . '/var/newsletter';
            if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
            $file = $dir . '/subscribers.txt';
            @file_put_contents($file, $email."\n", FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {
            $logger->warning('Newsletter: file store failed', ['err' => $e->getMessage()]);
        }

        // Provider (Brevo) subscription if configured
        try { $newsletter->subscribe($email); } catch (\Throwable $e) { $logger->warning('Newsletter: provider subscribe failed', ['err' => $e->getMessage()]); }

        // Envoi d’un email de notification à l’admin (configurer MAILER_DSN)
        try {
            $admin = $this->getParameter('app.newsletter_admin_email') ?? null;
            if ($admin) {
                $mailer->send(
                    (new Email())
                        ->from($admin)
                        ->to($admin)
                        ->subject('[AstroInsight] Nouvelle inscription newsletter')
                        ->text("Nouvelle inscription: $email")
                );
            }
        } catch (\Throwable $e) {
            $logger->warning('Newsletter: admin email failed', ['err' => $e->getMessage()]);
        }

        $this->addFlash('success', 'Merci, inscription enregistrée.');
        return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('app_home'));
    }
}
