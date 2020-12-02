<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaveMailController extends UtilityController
{
    public function __construct()
    {
        $this->defaultProps = ['dyComponent' => ['name' => config('contentenum.savemail.name'), 'selectedContent' => config('contentenum.savemail.selected')]];
    }

    public function index(Request $request)
    {
        return $this->render('Home/Index');
    }
}
