<?php
namespace App\Controller;

use App\Entity\AstroProfile;
use App\Form\AstroProfileType;
use App\Repository\AstroProfileRepository;
use App\Service\Astro\NatalChartUpdater;
use App\Service\Astro\GeoTimeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mon-espace/astro-profile')]
class AstroProfileController extends AbstractController
{
    #[Route('', name: 'astro_profile_edit')]
    public function edit(Request $request, AstroProfileRepository $repo, EntityManagerInterface $em, NatalChartUpdater $updater, GeoTimeService $geo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->getUser();
        $profile = $repo->findOneByUser($user) ?? (new AstroProfile())->setUser($user);

        $form = $this->createForm(AstroProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Auto geocode if user supplied a place but missing coords
            if ($profile->getBirthPlace() && ($profile->getLatitude() === null || $profile->getLongitude() === null || !$profile->getTimezone())) {
                $geoData = $geo->geocode($profile->getBirthPlace());
                $profile->setLatitude($geoData['lat'])->setLongitude($geoData['lon'])->setTimezone($geoData['tz']);
            }
            $dbOk = true;
            try {
                $em->persist($profile);
                $em->flush();
            } catch (\Throwable $e) {
                $dbOk = false;
                $this->addFlash('warning', 'Enregistrement base impossible (mode dev hors connexion DB): '.$e->getMessage());
            }
            // Try compute natal chart only if coords/timezone ok
            if ($profile->getLatitude() !== null && $profile->getLongitude() !== null && $profile->getBirthDate()) {
                try { $updater->update($profile); $this->addFlash('success', 'Thème natal recalculé (ou tentative).'); }
                catch (\Throwable $e) { $this->addFlash('warning', 'Échec calcul thème: '.$e->getMessage()); }
            } else {
                $this->addFlash('info', 'Profil partiellement enregistré (local). Ajoute date + lieu pour le thème.');
            }
            if ($dbOk) { return $this->redirectToRoute('astro_profile_edit'); }
        }

        return $this->render('astro/profile_edit.html.twig', [
            'form' => $form->createView(),
            'profile' => $profile,
        ]);
    }
}
