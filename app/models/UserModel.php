
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UserModel extends Model
{
    protected $table = 'users';
    protected $primary_key = 'id';

    public function findByLogin($login)
    {
        // Check email first
        $user = $this->find_by('email', $login);

        // If not found, check username
        if (!$user) {
            $user = $this->find_by('username', $login);
        }

        return $user;
    }

    public function createUser($data)
    {
        return $this->insert($data);
    }
}
