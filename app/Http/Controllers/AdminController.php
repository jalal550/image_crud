<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{

//    public function __construct()
//    {
//        $this->middleware('check.admin');
//    }

    public function index()
    {
        return view('backend.home.index');
    }


}
