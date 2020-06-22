<?php
namespace Packages\{PACKAGENAME}\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Packages\{PACKAGENAME}\Models\{MODELNAME};


/**
 * Class {PACKAGENAME}Controller
 *
 * @package Packages\{PACKAGENAME}\Http\Controllers
 */
class {PACKAGENAME}Controller extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['web', 'auth']);
    }

    /**
     * @Get("/{LOWER_PACKAGENAME}", as="{LOWER_PACKAGENAME}.main")
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('{LOWER_PACKAGENAME}::index');
    }

}
