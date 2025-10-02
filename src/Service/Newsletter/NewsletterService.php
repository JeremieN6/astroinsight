<?php
namespace App\Service\Newsletter;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsletterService
{
    public function __construct(private HttpClientInterface $httpClient, private ?string $brevoApiKey = null, private ?string $brevoListId = null)
    {
    }

    public function subscribe(string $email): void
    {
        if (!$this->brevoApiKey || !$this->brevoListId) {
            // Provider not configured; silently no-op.
            return;
        }
        $endpoint = 'https://api.sendinblue.com/v3/contacts';
        // Create or update contact and add to list
        $payload = [
            'email' => $email,
            'listIds' => [ (int)$this->brevoListId ],
            'updateEnabled' => true,
        ];
        $this->httpClient->request('POST', $endpoint, [
            'headers' => [
                'api-key' => $this->brevoApiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
            'json' => $payload,
            'timeout' => 8,
        ]);
    }
}
