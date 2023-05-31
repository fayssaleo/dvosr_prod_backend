<?php

namespace App\Modules\ActionDetail\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActionDetailController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("ActionDetail::welcome");
    }
}
