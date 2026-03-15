<?php

require_once app_path('Core/Controller.php');

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home.index', [
            'title' => config('app.name'),
            'terminalUser' => Auth::user(),
            'csrfToken' => Csrf::token(),
            'pageScripts' => [
                asset('assets/js/app.js')
            ]
        ]);
    }
}