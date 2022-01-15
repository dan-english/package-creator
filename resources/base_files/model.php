<?php
namespace Packages\{PACKAGENAME}\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Packages\Logic\HasLogicPassThrough;
use Packages\{PACKAGENAME}\Models\Logic\{MODELNAME}Logic;

/**
 * Class {MODELNAME}
 *
 * @package Packages\{PACKAGENAME}\Models
 */
class {MODELNAME} extends Model
{
    use SoftDeletes, HasLogicPassThrough, Notifiable;

    /**
     * @var array
     */
    protected $fillable = [];

    protected $hidden = [];

    protected $table = '{TABLENAME}';

    public static function boot()
    {
        parent::boot();
    }

    public function logic(): {MODELNAME}Logic
    {
        return new {MODELNAME}Logic($this);
    }


}
