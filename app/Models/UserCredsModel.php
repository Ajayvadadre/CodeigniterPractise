<?php

namespace App\Models;

use CodeIgniter\Model;

class UserCredsModel extends Model
{
  protected $table = 'userscred';
  protected $primaryKey = 'id';
  protected $allowedFields = ['username', 'password', 'email'];

  public function __construct()
  {
    parent::__construct();
  }

  public function getUser($username)
  {
    return $this->where('username', $username)->first();
  }

  public function createUser($data)
  {
    $this->insert($data);
  }

  public function updateUser($id, $data)
  {
    $this->update($id, $data);
  }

  public function deleteUser($id)
  {
    $this->delete($id);
  }
}