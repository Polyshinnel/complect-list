<?php

use App\Models\Site;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_url');
            $table->string('site_db');
            $table->string('site_prefix');
            $table->timestamps();
        });

        $dataItems = [
            [
                'site_url' => 'https://myplaymobil.ru',
                'site_db' => 'myplaymobil',
                'site_prefix' => 'mpm',
            ],
            [
                'site_url' => 'https://sikutoys.ru',
                'site_db' => 'sikutoys',
                'site_prefix' => 'sk',
            ],
            [
                'site_url' => 'https://myplantoys.ru',
                'site_db' => 'myplantoys',
                'site_prefix' => 'myp',
            ],
            [
                'site_url' => 'https://larsenshop.ru',
                'site_db' => 'larsenshop',
                'site_prefix' => 'lss',
            ],
            [
                'site_url' => 'https://djecoshop.ru',
                'site_db' => 'djecoshop',
                'site_prefix' => 'dj',
            ],
            [
                'site_url' => 'https://briorails.ru',
                'site_db' => 'briorails',
                'site_prefix' => 'br',
            ],
            [
                'site_url' => 'https://masoncash.me',
                'site_db' => 'masoncash',
                'site_prefix' => 'msc',
            ],
            [
                'site_url' => 'https://likelunch.ru',
                'site_db' => 'likelunch',
                'site_prefix' => 'like',
            ],
            [
                'site_url' => 'https://funko-pop.ru',
                'site_db' => 'funko-pop.ru',
                'site_prefix' => 'fun',
            ],
            [
                'site_url' => 'https://schleichtoys.ru',
                'site_db' => 'schleichtoys',
                'site_prefix' => 'st',
            ],
            [
                'site_url' => 'https://collecta-toys.ru',
                'site_db' => 'collecta-toys',
                'site_prefix' => 'clt',
            ],
            [
                'site_url' => 'https://guzzini.me',
                'site_db' => 'guzzini.me',
                'site_prefix' => 'guz',
            ],
            [
                'site_url' => 'https://monbento.me',
                'site_db' => 'monbento',
                'site_prefix' => 'mb',
            ],
            [
                'site_url' => 'https://paolareinas.ru',
                'site_db' => 'paolareinas',
                'site_prefix' => 'pr',
            ],
            [
                'site_url' => 'https://ravensburgers.ru',
                'site_db' => 'ravensburgers',
                'site_prefix' => 'rb',
            ],
            [
                'site_url' => 'https://herlitzbags.ru',
                'site_db' => 'herlitzbags',
                'site_prefix' => 'hb',
            ],
            [
                'site_url' => 'https://typhoonstore.ru',
                'site_db' => 'typhoon-store',
                'site_prefix' => 'tps',
            ],
            [
                'site_url' => 'https://reisenthelshop.ru',
                'site_db' => 'reisenthelshop',
                'site_prefix' => 'rs',
            ],
            [
                'site_url' => 'https://kanteen.ru',
                'site_db' => 'kanteen.ru',
                'site_prefix' => 'kan',
            ],
            [
                'site_url' => 'https://luigibormioli.ru',
                'site_db' => 'luigibormioli.ru',
                'site_prefix' => 'lui',
            ],
            [
                'site_url' => 'https://karmakiss.ru',
                'site_db' => 'karmakiss',
                'site_prefix' => 'kks',
            ],
            [
                'site_url' => 'https://der-die-das.ru',
                'site_db' => 'derdiedas',
                'site_prefix' => 'ddd',
            ],
            [
                'site_url' => 'https://paposhop.ru',
                'site_db' => 'paposhop',
                'site_prefix' => 'pps',
            ],
            [
                'site_url' => 'https://legbags.ru',
                'site_db' => 'legobags',
                'site_prefix' => 'lb',
            ],
            [
                'site_url' => 'https://umbrashop.ru',
                'site_db' => 'umbrashop',
                'site_prefix' => 'us',
            ],
            [
                'site_url' => 'https://bergensons.ru',
                'site_db' => 'bergensons.ru',
                'site_prefix' => 'ber',
            ],
            [
                'site_url' => 'https://globber.me',
                'site_db' => 'globber',
                'site_prefix' => 'glb',
            ],
            [
                'site_url' => 'https://safaritoys.ru',
                'site_db' => 'safaritoys',
                'site_prefix' => 'sft',
            ],
            [
                'site_url' => 'https://smart-solution.me',
                'site_db' => 'smart-solution.me',
                'site_prefix' => 'sma',
            ],
            [
                'site_url' => 'https://belmilbags.ru',
                'site_db' => 'belmilbags',
                'site_prefix' => 'bm',
            ],
            [
                'site_url' => 'https://liberty-jones.ru',
                'site_db' => 'liberty-jones.ru',
                'site_prefix' => 'lib',
            ],
            [
                'site_url' => 'https://ergobags.ru',
                'site_db' => 'ergobags',
                'site_prefix' => 'eg',
            ],
            [
                'site_url' => 'https://italtrike.ru',
                'site_db' => 'italtrike',
                'site_prefix' => 'itt',
            ],
            [
                'site_url' => 'https://larochere-france.ru',
                'site_db' => 'larochere-france.ru',
                'site_prefix' => 'lar',
            ],
            [
                'site_url' => 'https://lsa-shop.ru',
                'site_db' => 'lsa-shop.ru',
                'site_prefix' => 'lsa',
            ],
            [
                'site_url' => 'https://gotzdolls.ru',
                'site_db' => 'gotzdolls',
                'site_prefix' => 'gotz',
            ],
            [
                'site_url' => 'https://kilner-russia.ru',
                'site_db' => 'kilner-russia',
                'site_prefix' => 'kr',
            ],
            [
                'site_url' => 'https://scoutstore.ru',
                'site_db' => 'scoutstore',
                'site_prefix' => 'sst',
            ],
            [
                'site_url' => 'https://trunkibags.ru',
                'site_db' => 'trunkibags',
                'site_prefix' => 'tr',
            ],
            [
                'site_url' => 'https://vtechtoys.ru',
                'site_db' => 'vtechtoys',
                'site_prefix' => 'vt',
            ],
            [
                'site_url' => 'https://sento-sphere.ru',
                'site_db' => 'sento-sphere',
                'site_prefix' => 'ssp',
            ],
            [
                'site_url' => 'http://porlandshop.ru',
                'site_db' => 'porlandshop.ru',
                'site_prefix' => 'por',
            ],
            [
                'site_url' => 'https://josephkitchen.ru',
                'site_db' => 'josephkitchen',
                'site_prefix' => 'jk',
            ]
        ];

        foreach ($dataItems as $dataItem) {
            Site::create($dataItem);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
