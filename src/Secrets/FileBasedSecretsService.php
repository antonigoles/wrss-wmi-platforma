<?php

namespace App\Secrets;

class FileBasedSecretsService implements SecretsServiceInterface
{
    private array $secrets;

    public function __construct()
    {
        $secrets_file_content = file_get_contents(__DIR__ .'/../../.secrets.json');
        $this->secrets = json_decode(
            $secrets_file_content,
            true
        ) ?? [];
    }

    private function get_secret_to_file_key(Secret $secret): string {
        return match ($secret) {
            Secret::VERBOSE_MODE => 'verbose_mode',
            Secret::DEVELOPER_MODE => 'developer_mode',
            Secret::APP_URL => 'app_url',
            Secret::USOS_CONSUMER_KEY => 'consumer_key',
            Secret::USOS_CONSUMER_SECRET => 'consumer_secret',
        };
    }

    public function get(Secret $secret): int|string|bool|null
    {
        $file_key = $this->get_secret_to_file_key($secret);
        return $this->secrets[$file_key] ?? null;
    }
}