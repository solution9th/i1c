<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($any = null)
    {
        return response(file_get_contents(base_path('../i1c_front/views/index.html')));
    }
}
