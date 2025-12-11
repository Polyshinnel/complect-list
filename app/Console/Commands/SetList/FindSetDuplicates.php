<?php

namespace App\Console\Commands\SetList;

use App\Models\SetList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FindSetDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-list:find-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Поиск дублей комплектов на разных сайтах';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Начинаю поиск дублей комплектов...');

        // Получаем все уникальные SKU комплектов
        $setSkus = SetList::distinct()->pluck('sku')->filter();

        if ($setSkus->isEmpty()) {
            $this->warn('Не найдено комплектов для проверки');
            return 1;
        }

        $this->info('Найдено комплектов для проверки: ' . $setSkus->count());

        $duplicates = [];
        $progressBar = $this->output->createProgressBar($setSkus->count());
        $progressBar->start();

        foreach ($setSkus as $sku) {
            $sites = $this->findSitesBySku($sku);
            
            if (count($sites) > 1) {
                // Если найден на нескольких сайтах - это дубль
                $duplicates[$sku] = $sites;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Сохраняем результат в JSON файл
        $filename = storage_path('app/set_duplicates_' . date('Y-m-d_H-i-s') . '.json');
        $json = json_encode($duplicates, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        file_put_contents($filename, $json);

        // Выводим информацию о результате
        $this->info('Найдено дублей: ' . count($duplicates));
        $this->info('Результат сохранен в файл: ' . $filename);

        return 0;
    }

    /**
     * Поиск сайтов, на которых найден комплект с указанным SKU
     *
     * @param string $sku
     * @return array
     */
    private function findSitesBySku(string $sku): array
    {
        $sites = [];

        $ch = curl_init('https://herlitzbags.ru/index.php?module=ProductSkuView');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['sku' => $sku]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::warning("Ошибка при запросе для SKU {$sku}: {$error}");
            return $sites;
        }

        if ($httpCode !== 200 || !$result) {
            Log::warning("Неверный ответ для SKU {$sku}: HTTP {$httpCode}");
            return $sites;
        }

        $data = json_decode($result, true);

        if (!is_array($data)) {
            Log::warning("Неверный формат ответа для SKU {$sku}");
            return $sites;
        }

        // Извлекаем названия сайтов из ответа
        // Ответ содержит ключи верхнего уровня - это названия сайтов
        // Каждый сайт содержит данные комплекта (ключ с ID) и set_items
        foreach ($data as $siteKey => $siteData) {
            if (is_string($siteKey) && is_array($siteData)) {
                // Ключ верхнего уровня - это название сайта
                $sites[] = $siteKey;
            }
        }

        // Убираем дубликаты
        return array_unique($sites);
    }
}

