<?php

class Auth {


    function checkLogin($username, $password) {
        
        $dbh = DatabaseConnection::getInstance();
        $dbc = $dbh->getConnection();

        $user = new User($dbc);
        $user->findBy("username", $username);

        if(property_exists($user, "id")) {
            if(password_verify($password, $user->password)) {
                return $user->id;
            }
        }
    }
}