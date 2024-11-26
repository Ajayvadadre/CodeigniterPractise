<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserCredsModel;

class RegistarController extends Controller{

    public $user;
    public function __construct(){
        helper(['url']);
        $this->user = new UserCredsModel();
    }   
    function index(){
            
            return view('inc/register');
        
    }

    public function saveData(){
        $name=$this->request->getVar('name');
        $email=$this->request->getVar('email');
        $password = $this->request->getVar('password');
        $hashPassword =  password_hash($password,PASSWORD_DEFAULT);
        $this->user->save(["username"=>$name,"email"=>$email,"password"=>$hashPassword]);
        return redirect()->to("/");
    }
}