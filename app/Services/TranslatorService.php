<?php

namespace App\Services;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Support\Facades\Cache;
class TranslatorService
{
    private string $keyFilePath;
    private TranslateClient $client;
    public function __construct()
    {
        $this->keyFilePath ="D:\\SecureFiles\\translatorKey.json";
        $this->client = new TranslateClient([
            'keyFilePath' => "D:\\SecureFiles\\translatorKey.json"
        ]);
    }

    public function translate(string $text, string $to): string {

        $cacheKey = sha1(json_encode([$to, $text]));

        return Cache::rememberForever($cacheKey, function () use ($text, $to) {
            $result = $this->client->translate($text, [
                //'source' => 'en',
                'target' => $to,
            ]);

            return $result['text'];
        });
    }

}
