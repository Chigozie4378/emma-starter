<?php

require_once app_path('Core/Controller.php');
require_once app_path('Core/Request.php');
require_once app_path('Core/Validator.php');
require_once app_path('Core/Auth.php');
require_once app_path('Models/User.php');

class AuthController extends Controller
{
    public function showLogin()
    {
        $this->view('auth.login', [
            'title' => 'Login',
            'error' => Session::getFlash('error')
        ], 'guest');
    }

    public function login()
    {
        $data = Request::only(['email', 'password']);

        $validator = Validator::make($data)
            ->required('email', 'Email')
            ->email('email', 'Email')
            ->required('password', 'Password');

        if ($validator->fails()) {
            Session::flash('error', $validator->firstError());
            $this->redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail(trim($data['email']));

        if (!$user || !password_verify($data['password'], $user['password'])) {
            Session::flash('error', 'Invalid login credentials.');
            $this->redirect('/login');
        }

        Auth::login([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]);

        $this->redirect('/');
    }

    public function logout()
    {
        MacAccess::clear();
        Auth::logout();
        $this->redirect('/login');
    }
}