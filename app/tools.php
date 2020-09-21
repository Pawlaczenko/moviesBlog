<?php

class Tools {

    //Metoda wyświetla dane obiektu w postaci preformatowanej
    public static function showObj($data) {
        echo '<pre>';
        @print_r($data);
        echo '</pre>';
    }

    //Metoda haszująca dowolny tekst
    public static function PasswordHash($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost'=>10]);
    }

    //Metoda sprawdzająca czy hasła są takie same
    public static function PasswordCheck($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public static function showErrors($errors){
        if(!empty($errors)){
            foreach ($errors as $value) {
                echo $value;
            }
        };
    }

    public static function escapeQuotes($string){
        
    }
}
