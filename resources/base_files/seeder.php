<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Packages\{PACKAGENAME}\Models\{MODELNAME};

class {PACKAGENAME}Seeder extends Seeder
{
    /**
     * php artisan db:seed --class={PACKAGENAME}Seeder
     */
    public function run()
    {
        $this->command->info('Seeder For {PACKAGENAME}');

    }
}
