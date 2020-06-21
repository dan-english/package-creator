<?php
namespace Packages\{PACKAGENAME}\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Packages\Logic\HasLogicPassThrough;
use Packages\{PACKAGENAME}\Models\Logic\{MODELNAME}Logic;

/**
 * Class {PACKAGENAME}
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

    public static function boot()
    {
        parent::boot();
    }

    public function logic(): {PACKAGENAME}Logic
    {
        return new {PACKAGENAME}Logic($this);
    }


}
