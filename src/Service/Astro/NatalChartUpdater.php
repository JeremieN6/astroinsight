<?php
namespace App\Service\Astro;

use App\Entity\AstroProfile;
use App\Service\Astro\Prokerala\ProkeralaApiClient;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use DateTimeInterface;

class NatalChartUpdater
{
    public function __construct(private ProkeralaApiClient $client, private EntityManagerInterface $em) {}

    public function update(AstroProfile $profile): void
    {
        if ($profile->getLatitude() === null || $profile->getLongitude() === null || !$profile->getBirthDate()) {
            throw new \InvalidArgumentException('Profile incomplete');
        }
        $birthDate = $profile->getBirthDate();
        $time = $profile->getBirthTime();
        $dtString = $this->combineDateTime($birthDate, $time, $profile->getTimezone());
        $data = $this->client->getBirthDetails(
            $profile->getLatitude(),
            $profile->getLongitude(),
            $dtString,
            $profile->getTimezone() ?? 'UTC'
        );
        $profile->setNatalChartJson(json_encode($data))
            ->setLastComputedAt(new DateTimeImmutable());
        $this->em->persist($profile);
        $this->em->flush();
    }

    private function combineDateTime(DateTimeInterface $date, ?DateTimeInterface $time, ?string $tz): string
    {
        $datePart = $date->format('Y-m-d');
        $timePart = $time ? $time->format('H:i:s') : '12:00:00';
        return $datePart.'T'.$timePart.($tz ? $this->formatTz($tz) : 'Z');
    }

    private function formatTz(string $tz): string
    {
        // Prokerala expects timezone name, not offset; return as-is if looks like region
        if (str_contains($tz, '/')) { return $tz; }
        return $tz; // fallback
    }
}
