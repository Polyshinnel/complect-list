<?php

use App\Models\Brand;
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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->string('name');
            $table->string('lowercase_name');
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });

        $dataItems = [
            [
                'site_id' => 1,
                'name' => 'Playmobil',
                'lowercase_name' => 'playmobil',
            ],
            [
                'site_id' => 2,
                'name' => 'Siku',
                'lowercase_name' => 'siku',
            ],
            [
                'site_id' => 3,
                'name' => 'Plan Toys',
                'lowercase_name' => 'plan toys',
            ],
            [
                'site_id' => 4,
                'name' => 'Larsen',
                'lowercase_name' => 'larsen',
            ],
            [
                'site_id' => 5,
                'name' => 'DJECO',
                'lowercase_name' => 'djeco',
            ],
            [
                'site_id' => 6,
                'name' => 'Brio',
                'lowercase_name' => 'brio',
            ],
            [
                'site_id' => 7,
                'name' => 'Isabelle Rose Home',
                'lowercase_name' => 'isabelle rose home',
            ],
            [
                'site_id' => 8,
                'name' => 'Keep Cup',
                'lowercase_name' => 'keep cup',
            ],
            [
                'site_id' => 9,
                'name' => 'Funko',
                'lowercase_name' => 'funko',
            ],
            [
                'site_id' => 10,
                'name' => 'Schleich',
                'lowercase_name' => 'schleich',
            ],
            [
                'site_id' => 11,
                'name' => 'Collecta',
                'lowercase_name' => 'collecta',
            ],
            [
                'site_id' => 7,
                'name' => 'Greengate',
                'lowercase_name' => 'greengate',
            ],
            [
                'site_id' => 7,
                'name' => 'Tala',
                'lowercase_name' => 'tala',
            ],
            [
                'site_id' => 12,
                'name' => 'Guzzini',
                'lowercase_name' => 'guzzini',
            ],
            [
                'site_id' => 13,
                'name' => 'Monbento',
                'lowercase_name' => 'monbento',
            ],
            [
                'site_id' => 14,
                'name' => 'Paola Reina',
                'lowercase_name' => 'paola reina',
            ],
            [
                'site_id' => 15,
                'name' => 'Ravensburger',
                'lowercase_name' => 'ravensburger',
            ],
            [
                'site_id' => 16,
                'name' => 'Herlitz',
                'lowercase_name' => 'herlitz',
            ],
            [
                'site_id' => 17,
                'name' => 'TYPHOON',
                'lowercase_name' => 'typhoon',
            ],
            [
                'site_id' => 18,
                'name' => 'Reisenthel',
                'lowercase_name' => 'reisenthel',
            ],
            [
                'site_id' => 20,
                'name' => 'Luigi Bormioli',
                'lowercase_name' => 'luigi bormioli',
            ],
            [
                'site_id' => 21,
                'name' => 'Qualy',
                'lowercase_name' => 'qualy',
            ],
            [
                'site_id' => 22,
                'name' => 'Der Die Das',
                'lowercase_name' => 'der die das',
            ],
            [
                'site_id' => 23,
                'name' => 'Papo',
                'lowercase_name' => 'papo',
            ],
            [
                'site_id' => 24,
                'name' => 'Lego',
                'lowercase_name' => 'lego',
            ],
            [
                'site_id' => 25,
                'name' => 'Umbra',
                'lowercase_name' => 'umbra',
            ],
            [
                'site_id' => 26,
                'name' => 'Bergenson Bjorn',
                'lowercase_name' => 'bergenson bjorn',
            ],
            [
                'site_id' => 21,
                'name' => 'Silikomart',
                'lowercase_name' => 'silikomart',
            ],
            [
                'site_id' => 27,
                'name' => 'Globber',
                'lowercase_name' => 'globber',
            ],
            [
                'site_id' => 28,
                'name' => 'Safari Ltd',
                'lowercase_name' => 'safari ltd',
            ],
            [
                'site_id' => 29,
                'name' => 'Smart Solutions',
                'lowercase_name' => 'smart solutions',
            ],
            [
                'site_id' => 21,
                'name' => 'Tkano',
                'lowercase_name' => 'tkano',
            ],
            [
                'site_id' => 10,
                'name' => 'KONIK',
                'lowercase_name' => 'konik',
            ],
            [
                'site_id' => 31,
                'name' => 'Liberty Jones',
                'lowercase_name' => 'liberty jones',
            ],
            [
                'site_id' => 32,
                'name' => 'Ergobag',
                'lowercase_name' => 'ergobag',
            ],
            [
                'site_id' => 33,
                'name' => 'Italtrike',
                'lowercase_name' => 'italtrike',
            ],
            [
                'site_id' => 21,
                'name' => 'Doiy',
                'lowercase_name' => 'doiy',
            ],
            [
                'site_id' => 7,
                'name' => 'Mason Cash',
                'lowercase_name' => 'mason cash',
            ],
            [
                'site_id' => 34,
                'name' => 'La Rochere',
                'lowercase_name' => 'la rochere',
            ],
            [
                'site_id' => 7,
                'name' => 'Ib Laursen',
                'lowercase_name' => 'ib laursen',
            ],
            [
                'site_id' => 21,
                'name' => 'HOUSEKULT',
                'lowercase_name' => 'housekult',
            ],
            [
                'site_id' => 35,
                'name' => 'LSA International',
                'lowercase_name' => 'lsa international',
            ],
            [
                'site_id' => 36,
                'name' => 'Gotz',
                'lowercase_name' => 'gotz',
            ],
            [
                'site_id' => 26,
                'name' => 'Bergenson Bjorn Bath',
                'lowercase_name' => 'bergenson bjorn bath',
            ],
            [
                'site_id' => 37,
                'name' => 'Kilner',
                'lowercase_name' => 'kilner',
            ],
            [
                'site_id' => 21,
                'name' => 'Koziol',
                'lowercase_name' => 'koziol',
            ],
            [
                'site_id' => 5,
                'name' => 'Crocodile Creek',
                'lowercase_name' => 'crocodile creek',
            ],
            [
                'site_id' => 38,
                'name' => 'Scout',
                'lowercase_name' => 'ccout',
            ],
            [
                'site_id' => 25,
                'name' => 'Ambientair',
                'lowercase_name' => 'ambientair',
            ],
            [
                'site_id' => 42,
                'name' => 'Yvolution',
                'lowercase_name' => 'yvolution',
            ],
            [
                'site_id' => 5,
                'name' => 'Sycomore',
                'lowercase_name' => 'sycomore',
            ],
            [
                'site_id' => 39,
                'name' => 'TRUNKI',
                'lowercase_name' => 'trunki',
            ],
            [
                'site_id' => 40,
                'name' => 'Vtech',
                'lowercase_name' => 'vtech',
            ],
            [
                'site_id' => 41,
                'name' => 'SentoSphere',
                'lowercase_name' => 'sentosphere',
            ],
            [
                'site_id' => 21,
                'name' => 'Nordic Stream',
                'lowercase_name' => 'nordic stream',
            ],
            [
                'site_id' => 5,
                'name' => 'LUDIC',
                'lowercase_name' => 'ludic',
            ],
            [
                'site_id' => 5,
                'name' => 'KONIK Science',
                'lowercase_name' => 'konik science',
            ],
            [
                'site_id' => 21,
                'name' => 'Tassen',
                'lowercase_name' => 'tassen',
            ],
            [
                'site_id' => 5,
                'name' => 'Konik Games',
                'lowercase_name' => 'konik games',
            ],
            [
                'site_id' => 32,
                'name' => 'Hama',
                'lowercase_name' => 'hama',
            ],
            [
                'site_id' => 21,
                'name' => 'Giftup',
                'lowercase_name' => 'giftup',
            ],

            [
                'site_id' => 43,
                'name' => 'Porland',
                'lowercase_name' => 'porland',
            ],
            [
                'site_id' => 27,
                'name' => 'Micro',
                'lowercase_name' => 'micro',
            ],
            [
                'site_id' => 14,
                'name' => 'Reina del Norte',
                'lowercase_name' => 'reina del norte',
            ],
        ];

        foreach ($dataItems as $dataItem) {
            Brand::create($dataItem);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
