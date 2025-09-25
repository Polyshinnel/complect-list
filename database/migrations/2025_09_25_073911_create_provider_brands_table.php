<?php

use App\Models\ProviderBrands;
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
        Schema::create('provider_brands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('name');
            $table->string('normalized_name');
            $table->timestamps();
        });

        $dataItems = [
            [
                'provider_id' => 1,
                'name' => 'UMBRA',
                'normalized_name' => 'umbra',
            ],
            [
                'provider_id' => 1,
                'name' => 'QUALY',
                'normalized_name' => 'qualy',
            ],
            [
                'provider_id' => 1,
                'name' => 'Monbento',
                'normalized_name' => 'monbento',
            ],
            [
                'provider_id' => 1,
                'name' => 'Reisenthel',
                'normalized_name' => 'reisenthel',
            ],
            [
                'provider_id' => 1,
                'name' => 'JOSEPH JOSEPH',
                'normalized_name' => 'joseph joseph',
            ],
            [
                'provider_id' => 1,
                'name' => 'Eva Solo',
                'normalized_name' => 'eva solo',
            ],
            [
                'provider_id' => 1,
                'name' => 'Remember',
                'normalized_name' => 'remember',
            ],
            [
                'provider_id' => 1,
                'name' => 'KEEP CUP',
                'normalized_name' => 'keep cup',
            ],
            [
                'provider_id' => 1,
                'name' => 'Guzzini',
                'normalized_name' => 'guzzini',
            ],
            [
                'provider_id' => 1,
                'name' => 'Ambientair',
                'normalized_name' => 'ambientair',
            ],
            [
                'provider_id' => 1,
                'name' => 'DOIY',
                'normalized_name' => 'doiy',
            ],
            [
                'provider_id' => 1,
                'name' => 'LSA International',
                'normalized_name' => 'lsa international',
            ],
            [
                'provider_id' => 1,
                'name' => 'BigMouth',
                'normalized_name' => 'bigmouth',
            ],
            [
                'provider_id' => 1,
                'name' => 'Viners',
                'normalized_name' => 'viners',
            ],
            [
                'provider_id' => 1,
                'name' => 'Kilner',
                'normalized_name' => 'kilner',
            ],
            [
                'provider_id' => 1,
                'name' => 'Tkano',
                'normalized_name' => 'tkano',
            ],
            [
                'provider_id' => 1,
                'name' => 'Enjoyme',
                'normalized_name' => 'enjoyme',
            ],
            [
                'provider_id' => 1,
                'name' => 'ZOKU',
                'normalized_name' => 'zoku',
            ],
            [
                'provider_id' => 1,
                'name' => 'Typhoon',
                'normalized_name' => 'typhoon',
            ],
            [
                'provider_id' => 1,
                'name' => 'Mason Cash',
                'normalized_name' => 'mason cash',
            ],
            [
                'provider_id' => 1,
                'name' => 'Nordic Stream',
                'normalized_name' => 'nordic stream',
            ],
            [
                'provider_id' => 1,
                'name' => 'Frandsen',
                'normalized_name' => 'frandsen',
            ],
            [
                'provider_id' => 1,
                'name' => 'Smart Solutions',
                'normalized_name' => 'smart solutions',
            ],
            [
                'provider_id' => 1,
                'name' => 'Silikomart',
                'normalized_name' => 'silikomart',
            ],
            [
                'provider_id' => 1,
                'name' => 'Bergenson Bjorn',
                'normalized_name' => 'bergenson bjorn',
            ],
            [
                'provider_id' => 1,
                'name' => 'Tassen',
                'normalized_name' => 'tassen',
            ],
            [
                'provider_id' => 1,
                'name' => 'Liberty Jones',
                'normalized_name' => 'liberty jones',
            ],
            [
                'provider_id' => 1,
                'name' => 'Bergenson Bjorn Bath',
                'normalized_name' => 'bergenson bjorn bath',
            ],
            [
                'provider_id' => 1,
                'name' => 'LATITUDE',
                'normalized_name' => 'latitude',
            ],
            [
                'provider_id' => 1,
                'name' => 'Designboom',
                'normalized_name' => 'designboom',
            ],
            [
                'provider_id' => 1,
                'name' => 'Price&Kensington',
                'normalized_name' => 'price&kensington',
            ],
            [
                'provider_id' => 1,
                'name' => 'Fred & Friends',
                'normalized_name' => 'fred & friends',
            ],
            [
                'provider_id' => 1,
                'name' => 'Monkey Business',
                'normalized_name' => 'monkey business',
            ],
            [
                'provider_id' => 1,
                'name' => 'QDO',
                'normalized_name' => 'qdo',
            ],
            [
                'provider_id' => 1,
                'name' => 'Black+Blum',
                'normalized_name' => 'black+blum',
            ],
            [
                'provider_id' => 1,
                'name' => "Chilly's Bottles",
                'normalized_name' => "chilly's bottles",
            ],
            [
                'provider_id' => 1,
                'name' => 'SUCK UK',
                'normalized_name' => 'suck uk',
            ],
            [
                'provider_id' => 1,
                'name' => 'Klean Kanteen',
                'normalized_name' => 'klean kanteen',
            ],
            [
                'provider_id' => 2,
                'name' => 'CHURCHILL',
                'normalized_name' => 'сurchill',
            ],
            [
                'provider_id' => 2,
                'name' => 'Ariane',
                'normalized_name' => 'ariane',
            ],
            [
                'provider_id' => 2,
                'name' => 'Porland',
                'normalized_name' => 'porland',
            ],
            [
                'provider_id' => 2,
                'name' => 'COMAS',
                'normalized_name' => 'comas',
            ],
            [
                'provider_id' => 2,
                'name' => 'CASA DI FORTUNA',
                'normalized_name' => 'casa di fortuna',
            ],
            [
                'provider_id' => 2,
                'name' => 'PIOLI',
                'normalized_name' => 'pioli',
            ],
            [
                'provider_id' => 2,
                'name' => 'Luigi Bormioli',
                'normalized_name' => 'luigi bormioli',
            ],
            [
                'provider_id' => 2,
                'name' => 'Hisar',
                'normalized_name' => 'hisar',
            ],
            [
                'provider_id' => 2,
                'name' => 'La Rochere',
                'normalized_name' => 'la rochere',
            ],
            [
                'provider_id' => 2,
                'name' => 'Cosy & Trendy',
                'normalized_name' => 'cosy & trendy',
            ],
            [
                'provider_id' => 2,
                'name' => 'SCHOTT ZWIESEL',
                'normalized_name' => 'schott zwiesel',
            ],
            [
                'provider_id' => 2,
                'name' => 'Robert Welch',
                'normalized_name' => 'robert welch',
            ],
            [
                'provider_id' => 2,
                'name' => 'Bisetti',
                'normalized_name' => 'bisetti',
            ],
            [
                'provider_id' => 2,
                'name' => 'ArdaCam',
                'normalized_name' => 'ardacam',
            ],
            [
                'provider_id' => 2,
                'name' => 'Arcoroc (ОСЗ)',
                'normalized_name' => 'arcoroc (осз)',
            ],
            [
                'provider_id' => 2,
                'name' => 'Style Point / RAW',
                'normalized_name' => 'style point / raw',
            ],
            [
                'provider_id' => 2,
                'name' => 'Lava',
                'normalized_name' => 'lava',
            ],
            [
                'provider_id' => 2,
                'name' => 'SAMURA',
                'normalized_name' => 'samura',
            ],
            [
                'provider_id' => 2,
                'name' => 'Serax',
                'normalized_name' => 'serax',
            ],
            [
                'provider_id' => 2,
                'name' => 'Katie Alice',
                'normalized_name' => 'katie alice',
            ],
            [
                'provider_id' => 2,
                'name' => 'IVV',
                'normalized_name' => 'ivv',
            ],
            [
                'provider_id' => 2,
                'name' => 'Bitossi',
                'normalized_name' => 'bitossi',
            ],
            [
                'provider_id' => 2,
                'name' => 'Lacor',
                'normalized_name' => 'lacor',
            ],
            [
                'provider_id' => 2,
                'name' => 'Arthur Price',
                'normalized_name' => 'arthur price',
            ],
        ];

        foreach ($dataItems as $item) {
            ProviderBrands::create($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_brands');
    }
};
