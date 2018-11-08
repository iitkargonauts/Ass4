<?php

/* 
 * Copyright (C) 2013 peter
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once 'db_connect.php';
include_once 'psl-config.php';
include_once 'functions.php';

$error_msg = "";


if (isset($_POST['que'], $_POST['sa'], $_POST['username'], $_POST['p'])) {
    // Sanitize and validate the data passed in
    $que = filter_input(INPUT_POST, 'que', FILTER_SANITIZE_NUMBER_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
    
    $sa = filter_input(INPUT_POST, 'sa', FILTER_SANITIZE_STRING);
    if (strlen($sa) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid security answer configuration. This is rally odd\!\!</p>';
    }

    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
    $prep_stmt2 = "SELECT question_no, answer, answer_salt FROM members WHERE username = ? LIMIT 1";

    $stmt2 = $mysqli->prepare($prep_stmt2);
    $stmtu = $mysqli->prepare("UPDATE members SET password = ? , salt = ? WHERE username = ? LIMIT 1");

    if( ! $stmtu){
        $error_msg .= '<p class="error">Database error</p>';
    }

    if ( ! $stmt2) {
        $error_msg .= '<p class="error">Database error</p>';
    }

    $bind_hash='';
    $ans_='';
    $ans_salt='';
    $stmt2->bind_param('s', $username);
    $stmtu->bind_param('sss', $ans_, $ans_salt, $username);

    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.

    if (empty($error_msg)) {
        
        $stmt2->execute();
        $stmt2->store_result();

        if($stmt2->num_rows == 1){
            $stmt2->bind_result($ans['question_no'], $ans['answer'], $ans['answer_salt']);
            $stmt2->fetch();
            $bind_hash = hash('sha512', $sa . $ans['answer_salt']);
            if($que == $ans['question_no']){
                $go=1;
            }else{
                header('Location: ../error.php?err=Invalid Security Answer');
                exit();
            }
            if(strcmp($bind_hash, $ans['answer'])==0){
                $ans_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
                $ans_ = hash('sha512', $password . $ans_salt);
                echo $ans_ . '<br>';
                echo $ans_salt . '<br>';
                $stmtu->execute();
                $stmt2->store_result();
                echo $stmtu->num_rows . 'Changed Successfully<br>';
                // header('Location: ../index.php');
                // exit();
            }else{
                echo 'Not Changed Successfully<br>';
                echo strcmp($bind_hash, $ans['answer']) . '<br>';
                echo '|' . $bind_hash . '|' . '<br>';
                echo '|' . $ans['answer'] . '|';
                // header('Location: ../error.php?err=Invalid Security Answer');
                // exit();
            }
        }

        // header('Location: ../error.php?err=Invalid Security Answer');
        // exit();
    }
}

?>
