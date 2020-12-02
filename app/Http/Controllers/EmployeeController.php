<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class EmployeeController extends UtilityController
{
    //protected $defaultPropss;

    public function __construct() {
        $this->defaultProps = ['dyComponent' => ['name' => config('contentenum.employee.name'), 'selectedContent' => config('contentenum.employee.selected')]];
    }

    public function index(Request $request)
    {
        return $this->render('Home/Index');
    }
}
