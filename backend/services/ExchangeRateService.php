<?php
class ExchangeRateService {
    private string $apiKey;
    private string $apiUrl;

    public function __construct() {
        $this->apiKey = APP_EXCHANGE_KEY;
        $this->apiUrl = 'https://v6.exchangerate-api.com/v6';
    }

    public function getRate(string $from, string $to): float {
        if ($from === $to) return 1.0;

        $cacheFile = sys_get_temp_dir() . "/exchange_{$from}_{$to}.json";

        // Cache de 1 hora
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 3600) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            return (float) $cached['rate'];
        }

        try {
            $url  = "{$this->apiUrl}/{$this->apiKey}/pair/{$from}/{$to}";
            $data = json_decode(file_get_contents($url), true);

            if ($data['result'] === 'success') {
                $rate = (float) $data['conversion_rate'];
                file_put_contents($cacheFile, json_encode(['rate' => $rate]));
                return $rate;
            }
        } catch (Exception $e) {
            // fallback
        }

        return 1.0;
    }

    public function getSupportedCurrencies(): array {
        return ['USD', 'EUR', 'AOA', 'GBP', 'BRL'];
    }
}