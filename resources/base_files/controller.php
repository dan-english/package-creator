<?php
namespace Packages\{PACKAGENAME}\Http\Controllers;
use Inertia\Inertia;

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
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('{PACKAGENAME}/Index');
    }

}
