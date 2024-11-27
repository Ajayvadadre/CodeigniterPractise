<?php

namespace App\Controllers;

use App\Models\UserModel;
// error_reporting(E_ALL);
// ini_set('display_erros',1);


class Home extends BaseController
{
    public $user;

    public function __construct()
    {
        helper(['url']);
        $this->user = new UserModel();

        if (!session()->get('username')) {
            return redirect()->to('login');
        }
    }
    public function index()
    {
        echo view('inc/header');
        if ($this->request->getGet('search')) {
            $q = $this->request->getGet('search');
            $data['users'] = $this->user->where('Name LIKE', $q . '%')->orderby('name', "ASC")->paginate(10, 'group1');
        } else if ($this->request->getPost('filter')) {
            print_r($data["filterData"] = $this->request->getVar("filter"));
            $data["username"] = $_POST['nameFilter'];
            $data["email"] = $_POST['emailFilter'];
            $data["id"] = $_POST['idFilter'];
            echo view('inc/header');
            if ($data["username"] || $data["email"] || $data["id"]) {
                $filterData = $this->user;
                if ($data["id"]) {
                    $filterData = $this->user->Where("Id", $data["id"]);
                }
                if ($data["username"]) {
                    $filterData = $this->user->orWhere("Name", $data["username"]);
                }
                if ($data["email"]) {
                    $filterData = $this->user->orWhere("email", $data["email"]);
                }
                $data['pager'] = $this->user->pager;
                print_r($data["users"] = $filterData->findAll());
            }
        } else {
            print_r($data['users'] = $this->user->orderby('id', "DESC")->paginate(10, 'group1'));
        }
        $data['pager'] = $this->user->pager;
        echo view('home', $data);
        echo view('inc/footer');
    }


    public function saveUser()
    {
        $username = $this->request->getVar('name');
        $email = $this->request->getVar('email');
        $this->user->save(["name" => $username, "email" => $email]);
        $mongoId = $this->user->insertID();

        // CURL api to create user
        $ch = curl_init();
        $newdata = [
            "_id" =>   $mongoId,
            "name" => $username,
            "email" => $email
        ];
        $url = "http://localhost:5000/users/create";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($newdata));

        $response = curl_exec($ch);
        curl_close($ch);

        session()->setFlashData("sucess", "Data added Sucessfully");
        return redirect()->to(base_url("/dashboard"));
    }

    public function getSingleUser($id)
    {
        $data = $this->user->where('id', $id)->first();
        echo json_encode($data);
    }

    public function updateUser()
    {
        $id = $this->request->getVar('updateId');
        $username = $this->request->getVar('name');
        $email = $this->request->getVar('email');
        $data['name'] = $username;
        $data['email'] = $email;
        $this->user->update($id, $data);

        //Curl update user
        $ch = curl_init();
        $url = "http://localhost:5000/users/update/" . $id;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);

        return redirect()->to(base_url());
    }

    public function deleteUser()
    {
        $id = $this->request->getVar('id');
        $this->user->delete($id);
        echo 1;
        return redirect()->to(base_url("/"));
    }


    public function deleteAllUser()
    {
        // echo "connected";    
        //var_dump($ids);
        //print_r($ids);
        //$this->user->delete($ids);
        //redirect()->to(base_url("/"));
        // echo "deleted";

        $ids = $this->request->getVar('ids');
        for ($i = 0; $i < count($ids); $i++) {
            $this->user->delete($ids[$i]);
        }
    }

    public function filterUser()
    {
        // Filter for all 3 inputs should be same
        // if($data['users'] = $this->user->where('Name LIKE',$data["username"])->where("email LIKE" ,$data['email'])->orderby('name',"ASC")->paginate(10,'group1')){
        //     // var_dump( $data['users'] = $this->user->where('Name LIKE',$data["username"])->where("email LIKE" ,$data['email'])->orderby('name',"ASC")->paginate(10,'group1'));
        //     $data['users'] = $this->user->where('Name LIKE',$data["username"])->where("email LIKE" ,$data['email'])->orderby('name',"ASC")->paginate(10,'group1');
        // }
        //Filter for finding the data of the inputs inside input fields
        $data["filterData"] = $this->request->getVar("filter");
        $data["username"] = $_POST['nameFilter'];
        $data["email"] = $_POST['emailFilter'];
        $data["id"] = $_POST['idFilter'];

        echo view('inc/header');
        if ($data["username"] || $data["email"] || $data["id"]) {
            $filterData = $this->user;
            if ($data["id"]) {
                $filterData = $this->user->Where("Id", $data["id"]);
            }
            if ($data["username"]) {
                $filterData = $this->user->orWhere("Name", $data["username"]);
            }
            if ($data["email"]) {
                $filterData = $this->user->orWhere("email", $data["email"]);
            }
            $data['pager'] = $this->user->pager;
            print_r($data["users"] = $filterData->findAll());
        }
        echo view('home', $data);
        echo view('inc/footer');
    }
}
