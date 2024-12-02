<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Database;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Home extends BaseController
{
    public $user;
    public $load;
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
        $data["all_users"]  =  $this->user->orderby('id', "ASC")->findAll();
        if ($this->request->getGet('search')) {
            $q = $this->request->getGet('search');
            $data['users'] = $this->user->where('Name LIKE', $q . '%')->orderby('name', "ASC")->paginate(10, 'group1');
        } else if ($this->request->getPost()) {
            $data["filterData"] = $this->request->getVar("filter");
            $data["username"] = $_POST['nameFilter'];
            $data["email"] = $_POST['emailFilter'];
            $data["id"] = $_POST['idFilter'];
            $data["age"] = $_POST['ageFilter'];
            echo view('inc/header');
            if ($data["username"] || $data["email"] || $data["id"] || $data["age"]) {
                $filterData = $this->user;


                if ($data["id"]) {
                    $filterData->Where("Id", $data["id"]);
                }
                if ($data["username"]) {
                    $filterData->orWhere("Name", $data["username"]);
                }
                if ($data["email"]) {
                    $filterData->orWhere("email", $data["email"]);
                }
                if ($data["age"]) {
                    $filterData->orWhere("age", $data["age"]);
                }
                // $userData =  $filterData->findAll();

                $data['users'] = $filterData->paginate(10, 'group1');
                $data['pager'] = $this->user->pager;
            }
        } else {
            $data['users'] = $this->user->orderby('id', "ASC")->paginate(10, 'group1');
        }
        $data['pager'] = $this->user->pager;
        echo view('home', $data);
        echo view('inc/footer');
    }


    public function saveUser()
    {
        $username = $this->request->getVar('name');
        $email = $this->request->getVar('email');
        $age = $this->request->getVar('age');
        $this->user->save(["name" => $username, "email" => $email, "age" => $age]);
        $mongoId = $this->user->insertID();

        // CURL api to create user
        $ch = curl_init();
        $newdata = [
            "_id" =>   $mongoId,
            "name" => $username,
            "email" => $email,
            "age" => $age
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
        $age = $this->request->getVar('age');
        $data['name'] = $username;
        $data['email'] = $email;
        $data['age'] = $age;
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
        $data["age"] = $_POST['ageFilter'];
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
            if ($data["age"]) {
                $filterData = $this->user->orWhere("age", $data["age"]);
            }
            $data['pager'] = $this->user->pager;
            print_r($data["users"] = $filterData->findAll());
        }
        echo view('home', $data);
        echo view('inc/footer');
    }

    public function exportData()
    {
        $filename = 'users_data' . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // get data 
        $users = new UserModel();
        $usersData = $users->select('*')->findAll();

        // file creation 
        $file = fopen('php://output', 'w');
        $header = array("ID", "Name", "Email","Age");
        fputcsv($file, $header);
        foreach ($usersData as $key => $line) {
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
    }

    // public function uploadData(): void
    // {

    //     $fileName = $this->request->getFile("uploadFile");
    //     // $fileName = $_FILES['uploadFile']['name'];
    //     $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    //     $allowed_ext = ['xls', 'csv', 'xlsx'];

    //     if (in_array($file_ext, $allowed_ext)) {
    //         $inputFileNamePath = $this->request->getFile("uploadFile");
    //         $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
    //         $data = $spreadsheet->getActiveSheet()->toArray();
    //         $db  = new Database();
    //         $count = "0";
    //         foreach ($data as $row) {
    //             if ($count > 0) {
    //                 $fullname = $row['0'];
    //                 $email = $row['1'];

    //                 $this->$db->query("INSERT INTO users (username,email) VALUES ('$fullname','$email')");
    //                 $msg = true;
    //             } else {
    //                 $count = "1";
    //             }
    //         }

    //         if (isset($msg)) {
    //             $session = session();
    //             $session->set('username', "something");
    //             $_SESSION['message'] = "Successfully Imported";
    //             session()->setFlashdata("message", "wrong 1");
    //             header('Location: /dashboard');
    //             exit(0);
    //         } else {
    //             $session = session();
    //             $session->set('username', "something");
    //             $_SESSION['message'] = "Not Imported";
    //             header('Location: /dashboard');
    //             session()->setFlashdata("message", "wrong 2");

    //             exit(0);
    //         }
    //     } else {
    //         $session = session();
    //         $session->set('username', "something");
    //         $_SESSION['message'] = "Invalid File";
    //         header('Location: /dashboard');
    //         session()->setFlashdata("message", "wrong 3");

    //         exit(0);
    //     }
    // }

    public function uploadData()
    {
        try {
            $file = $this->request->getFile('uploadFile');

            // Check if file was uploaded
            if (!$file || !$file->isValid()) {
                return redirect()->to(base_url("/dashboard"))
                    ->with('error', 'No file uploaded or invalid file.');
            }

            // Get file extension
            $ext = $file->getClientExtension();
            $allowed_ext = ['xls', 'csv', 'xlsx'];

            if (!in_array($ext, $allowed_ext)) {
                return redirect()->to(base_url("/dashboard"))
                    ->with('error', 'Invalid file type. Please upload XLS, XLSX, or CSV file.');
            }

            // Move file to writable directory
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);
            $inputFileName = WRITEPATH . 'uploads/' . $newName;

            try {
                // Load the spreadsheet
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
                $worksheet = $spreadsheet->getActiveSheet();
                $data = $worksheet->toArray();

                // Start database transaction
                $db = \Config\Database::connect();
                $db->transStart();

                $successCount = 0;

                // Skip header row and process data
                for ($i = 1; $i < count($data); $i++) {
                    $row = $data[$i];
                    $mongoId = $this->user->insertID();

                    // Validate row data
                    if (!empty($row[0]) && !empty($row[1])) {
                        $userData = [
                            'name' => $row[0],
                            'email' => $row[1],
                            'age' => $row[2]
                        ];
                        $mongoData =[
                            '_id'=>$mongoId,
                            'name' => $row[0],
                            'email' => $row[1],
                            'age' => $row[2]
                        ];
                        // Check for existing user
                        $existingUser = $db->table('users')
                            ->where('email', $userData['email'])
                            ->get()
                            ->getRow();

                        // if ($existingUser) {
                        //     // Update existing user
                        //     var_dump("existing".$userData);

                        //     $db->table('users')
                        //         ->where('id', $existingUser->id)
                        //         ->update($userData);
                        // } else {
                            // Insert new user
                            // var_dump("insert".$userData);
                            ini_set('max_execution_time', 300); // 5 minutes
                            $db->table('users')->insert($userData);
                            $ch = curl_init();
                            $id = $this->request->getVar('updateId');
                            $url = "http://localhost:5000/users/create";
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mongoData));

                            $response = curl_exec($ch);
                            curl_close($ch);
                        // }

                        $successCount++;
                    }
                }

                $db->transComplete();

                // Clean up - delete uploaded file
                if (file_exists($inputFileName)) {
                    unlink($inputFileName);
                }

                if ($db->transStatus() === FALSE) {
                    return redirect()->to(base_url("/dashboard"))
                        ->with('error', 'Failed to import data. Please try again.');
                }

                return redirect()->to(base_url("/dashboard"))
                    ->with('success', "Successfully imported $successCount records.");
            } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                log_message('error', 'Error loading file: ' . $e->getMessage());
                return redirect()->to(base_url("/dashboard"))
                    ->with('error', 'Error reading file: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            log_message('error', 'Upload error: ' . $e->getMessage());
            return redirect()->to(base_url("/dashboard"))
                ->with('error', 'An error occurred during upload.');
        }
    }
}
