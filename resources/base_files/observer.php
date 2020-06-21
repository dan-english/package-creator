<?php
namespace Packages\{PACKAGENAME}\Observers;

use Illuminate\Support\Facades\Log;
use Packages\{PACKAGENAME}\Models\{MODELNAME};

class {MODELNAME}Observer
{
    /**
     * @param {MODELNAME} ${LOWER_MODELNAME}
     */
    public function created({MODELNAME} ${LOWER_MODELNAME})
    {
        Log::info('{MODELNAME} Created!', [
            'id' => ${LOWER_MODELNAME}->id
        ]);
    }
}
