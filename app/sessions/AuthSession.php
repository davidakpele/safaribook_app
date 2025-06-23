<?php

namespace Session;

class AuthSession{
    public static function authCheck():bool{
        if (isset($_SESSION['email']) && isset($_SESSION['userId']) && isset($_SESSION['role'])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function destroy():bool {
        if (session_status() == PHP_SESSION_ACTIVE) {
            unset($_SESSION['userId']);
            unset($_SESSION['email']);
            unset($_SESSION['name']);
            unset($_SESSION['role_name']);
            return true;
        }else{
            return false;
        }
    }
}