<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserCredsModel;

class LoginController extends Controller
{
    public $userModel;
  public function __construct()
  {
    helper(['url']);
    $this->userModel = new UserCredsModel();

  }

  public function index()
  {
    return view('inc/login');
  }

  public function authenticate()
  {
      $username = $this->request->getVar('username');
      $password = $this->request->getVar('password');
      $userModel = new UserCredsModel();
      $user = $userModel->where('username', $username)->first();
      if ($user) {
         $enc_password  = $user['password'];
         $dec_password  = password_verify($password, $enc_password);
          if($dec_password) {
            $session = session();
            $session->set('username',$username);
              return redirect()->to(base_url('/dashboard'));
          } else {
            session()->setFlashdata("passwordError","wrong password");
            return redirect()->to(base_url('/'));
          }
      } else {  
          session()->setFlashdata("usernameError","User not found");
          return redirect()->to(base_url('/'));
          
      }
  }

  public function logout()
  {  
    return redirect()->to('login');
  }
}