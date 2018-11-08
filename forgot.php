<?php
/**
 * Copyright (C) 2013 peredur.net
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
include_once './includes/register.inc.php';
include_once './includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
        <title>Forgot Password</title>
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
        <link rel="stylesheet" href="styles/main.css" />
    </head>
    <body>
        <form method="post" name="forget_form" action="includes/forgot.inc.php">
            Username: <input type='text' name='username' id='username' /><br>
            Security Question : 
            <select name="que">
             <option value="1">Favourite Animal ?
            <option value="2">Favourite Color ?
            </select>
            <br>

            Security Answer : <input type="text" id="ans"><br>
            New Password: <input type="password"
                             name="password" 
                             id="password"/> 
                             <div id="strength"></div>
            <script>
            function Ldistance(a, b) {
                if (a.length === 0) return b.length; 
                if (b.length === 0) return a.length;

                var matrix = [];

                // increment along the first column of each row
                var i;
                for (i = 0; i <= b.length; i++) {
                    matrix[i] = [i];
                }

                // increment each column in the first row
                var j;
                for (j = 0; j <= a.length; j++) {
                    matrix[0][j] = j;
                }

                // Fill in the rest of the matrix
                for (i = 1; i <= b.length; i++) {
                    for (j = 1; j <= a.length; j++) {
                    if (b.charAt(i-1) == a.charAt(j-1)) {
                        matrix[i][j] = matrix[i-1][j-1];
                    } else {
                        matrix[i][j] = Math.min(matrix[i-1][j-1] + 1, // substitution
                                                Math.min(matrix[i][j-1] + 1, // insertion
                                                        matrix[i-1][j] + 1)); // deletion
                    }
                    }
                }

                return matrix[b.length][a.length];
            };
            function check_strength(string){
                var common_pass = ["password", "123456", "12345678", "12345", "qwerty", "abc123", "football", "monkey", "123456789", "1234567", "letmein", "111111", "1234", "1234567890", "dragon", "baseball", "trustno1", "iloveyou", "princess", "adobe123", "123123", "welcome", "login", "admin", "solo", "master", "sunshine", "photoshop", "1qaz2wsx", "ashley", "mustang", "121212", "starwars", "bailey", "access", "flower", "passw0rd", "shadow", "michael", "654321", "jesus", "password1", "superman", "hello", "696969", "qwertyuiop", "hottie", "freedom", "qazwsx", "ninja", "azerty", "loveme", "whatever", "batman", "zaq1zaq1", "Football", "000000"];
                var min=999999999;
                for (var i = 0; i< common_pass.length;i++ ){
                    if(min > Ldistance(common_pass[i], string)){
                        min = Ldistance(common_pass[i], string);
                    };
                }
                console.log('min : '+min);
                return min;
            }
            var string = document.getElementById("password");

            string.onfocus = function(){
                document.getElementById("message").style.display = "block";
            }

            string.onblur = function(){
                document.getElementById("message").style.display = "none";
            }

            string.onkeyup = function() {
                document.getElementById("strength").innerHTML = 'Strength : ' + check_strength(string.value) + ' min LDistance from common passwords';
            }
            </script>
            Confirm password: <input type="password" 
                                     name="confirmpwd" 
                                     id="confirmpwd" /><br>

            <p> Go to <a href="index.php">login page</a></p>

            <input type="button" 
                    value="Register" 
                    onclick="       return forgotformhash(this.form,
                                    this.form.username,
                                    this.form.que,
                                    this.form.ans,
                                    this.form.password,
                                    this.form.confirmpwd);" /> 
        </form>
    </body>
</html>
