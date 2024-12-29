<?php
class Auth {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function registerUser($name, $email, $password) {
        return $this->user->register($name, $email, $password);
    }

    public function authenticateUser($email, $password) {
        return $this->user->login($email, $password);
    }

    public function isAdmin($role) {
        return $role === 'admin';
    }

    public function isClient($role) {
        return $role === 'borrower';
    }
}
?>
