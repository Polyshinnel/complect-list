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
            $sitesData = $this->findSitesBySku($sku);
            
            if (count($sitesData) > 1) {
                // Проверяем, есть ли различия в set_items между сайтами
                if ($this->hasSetItemsDifferences($sitesData)) {
                    // Формируем структуру для сохранения: сайт -> массив сайтов
                    $duplicates[$sku] = array_keys($sitesData);
                }
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
     * Возвращает массив: [название_сайта => set_items]
     *
     * @param string $sku
     * @return array
     */
    private function findSitesBySku(string $sku): array
    {
        $sitesData = [];

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
            return $sitesData;
        }

        if ($httpCode !== 200 || !$result) {
            Log::warning("Неверный ответ для SKU {$sku}: HTTP {$httpCode}");
            return $sitesData;
        }

        $data = json_decode($result, true);

        if (!is_array($data)) {
            Log::warning("Неверный формат ответа для SKU {$sku}");
            return $sitesData;
        }

        // Извлекаем set_items для каждого сайта
        // Ответ содержит ключи верхнего уровня - это названия сайтов
        foreach ($data as $siteKey => $siteData) {
            if (is_string($siteKey) && is_array($siteData) && isset($siteData['set_items'])) {
                $sitesData[$siteKey] = $siteData['set_items'];
            }
        }

        return $sitesData;
    }

    /**
     * Проверяет, есть ли различия в set_items между сайтами
     * Сравнивает по: количеству элементов, SKU каждого элемента, set_count каждого элемента
     *
     * @param array $sitesData Массив [название_сайта => set_items]
     * @return bool
     */
    private function hasSetItemsDifferences(array $sitesData): bool
    {
        if (count($sitesData) < 2) {
            return false;
        }

        // Нормализуем set_items для каждого сайта
        $normalizedSets = [];
        foreach ($sitesData as $siteName => $setItems) {
            $normalizedSets[$siteName] = $this->normalizeSetItems($setItems);
        }

        // Сравниваем первый набор с остальными
        $firstSite = array_key_first($normalizedSets);
        $firstSet = $normalizedSets[$firstSite];

        foreach ($normalizedSets as $siteName => $set) {
            if ($siteName === $firstSite) {
                continue;
            }

            // Сравниваем количество элементов
            if (count($firstSet) !== count($set)) {
                return true;
            }

            // Сравниваем каждый элемент по SKU и set_count
            foreach ($firstSet as $key => $item) {
                if (!isset($set[$key])) {
                    return true;
                }

                // Сравниваем SKU
                if ($item['sku'] !== $set[$key]['sku']) {
                    return true;
                }

                // Сравниваем set_count
                if ($item['set_count'] !== $set[$key]['set_count']) {
                    return true;
                }
            }

            // Проверяем, нет ли лишних элементов во втором наборе
            foreach ($set as $key => $item) {
                if (!isset($firstSet[$key])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Нормализует set_items для сравнения
     * Создает массив с ключами по SKU и значениями {sku, set_count}
     *
     * @param array $setItems
     * @return array
     */
    private function normalizeSetItems(array $setItems): array
    {
        $normalized = [];

        foreach ($setItems as $item) {
            if (is_array($item) && isset($item['sku'])) {
                $sku = $item['sku'];
                $setCount = isset($item['set_count']) ? (string)$item['set_count'] : '0';
                
                // Используем SKU как ключ для уникальности
                $normalized[$sku] = [
                    'sku' => $sku,
                    'set_count' => $setCount,
                ];
            }
        }

        // Сортируем по SKU для консистентного сравнения
        ksort($normalized);

        return $normalized;
    }
}

