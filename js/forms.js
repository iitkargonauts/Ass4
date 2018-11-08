/* 
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

function formhash(form, password) {
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");

    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    // Make sure the plaintext password doesn't get sent. 
    password.value = "";

    // Finally submit the form. 
    form.submit();
}

function recoveryformhash(form, password, confirmpasswd){
    // Check each field has a value
    
    if (password.value == '' || conf.value == '') {
        alert('You must provide all the requested details. Please try again');
        return false;
    }

    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        alert('Passwords must be at least 6 characters long.  Please try again');
        form.password.focus();
        return false;
    }

    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    if (!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
        return false;
    }

    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }

    var p = document.createElement("input");

    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    password.value = "";
    conf.value = "";

    form.submit();
    return true;
}

function forgotformhash(form, username, que, ans, password, conf){
    // Check each field has a value
    
    if (username.value == '' || que.value == '' || ans.value == '' || conf.value == '' || password.value == '') {
        console.log(username.value);
        console.log(que.value);
        console.log(ans.value);
        console.log(conf.value);
        console.log(password.value);
        alert('You must provide all the requested details. Please try again');
        return false;
    }

    if (password.value.length < 6) {
        alert('Passwords must be at least 6 characters long.  Please try again');
        form.password.focus();
        return false;
    }

    // Check the username
    var re = /^\w+$/; 
    if(!re.test(form.username.value)) { 
        alert("Username must contain only letters, numbers and underscores. Please try again"); 
        form.username.focus();
        return false; 
    }

    re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    if (!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
        return false;
    }

    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }

    //console.log("Chall");
    var sa = document.createElement("input");
    var p = document.createElement("input");

    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
    
    form.appendChild(sa);
    sa.name="sa";
    sa.type="hidden";
    sa.value = hex_sha512(ans.value);

    password.value = "";
    conf.value = "";
    ans.value = "";
    //console.log("Ye bhi");
    form.submit();
    return true;
}

function regformhash(form, uid, email, password, conf, sec_que, sec_ans) {
    // Check each field has a value
    if (uid.value == '' || email.value == '' || password.value == '' || conf.value == '' || sec_que.value == '' || sec_ans == '') {
        alert('You must provide all the requested details. Please try again');
        return false;
    }
    
    // Check the username
    re = /^\w+$/; 
    if(!re.test(form.username.value)) { 
        alert("Username must contain only letters, numbers and underscores. Please try again"); 
        form.username.focus();
        return false; 
    }
    
    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        alert('Passwords must be at least 6 characters long.  Please try again');
        form.password.focus();
        return false;
    }
    
    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    if (!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
        return false;
    }
    
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }

    // 
    if(sec_ans.value.length <=0){
        alert('Security Answer should be non-empty');
        form.sec_ans.focus();
        return false;
    }
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
    var sa = document.createElement("input");

    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    // Hash sec_ans
    form.appendChild(sa);
    sa.name="sa";
    sa.type="hidden";
    sa.value = hex_sha512(sec_ans.value);

    console.log( 'sa value : ' + sa.value);

    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
    conf.value = "";
    sec_ans.value = "";

    // Finally submit the form. 
    form.submit();
    return true;
}
