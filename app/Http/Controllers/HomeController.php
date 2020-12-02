<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HomeController extends UtilityController
{
    public function __construct()
    {
        $this->defaultProps = ['dyComponent' =>
        [
            'name' => config('contentenum.dashboard.name'),
            'selectedContent' => config('contentenum.dashboard.selected')
        ]];
    }

    public function index(Request $request)
    {
        return $this->render('Home/Index');
    }
}
