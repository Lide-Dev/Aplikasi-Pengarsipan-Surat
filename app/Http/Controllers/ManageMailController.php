<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageMailController extends UtilityController
{

    public function __construct()
    {
        $this->defaultProps = ['dyComponent' => ['name' => config('contentenum.managemailinbox.name')]];
    }

    public function indexInbox(Request $request)
    {
        $this->setDynamicComponent(config('contentenum.managemailinbox.name'), config('contentenum.managemailinbox.selected'));
        return $this->render('Home/Index');
    }
    public function indexSent(Request $request)
    {
        $this->setDynamicComponent(config('contentenum.managemailsent.name'), config('contentenum.managemailsent.selected'));
        return $this->render('Home/Index');
    }
}
