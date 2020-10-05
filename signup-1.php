<?php 
$error = NULL;
$email = $_REQUEST['email'];
$u_err = NULL;

$redi_msg = NULL;
echo "<script> localStorage.setItem('reloadme',false); </script>";


if( null !== $email){
 
    //Execute process of registration
    if(isset($_POST['submit'])){
    
        $password = $_POST['password'];
        $username = $_POST['username'];
    
        if(strlen($username) < 6 || strlen($username) > 18){
            $error = "<p> Username character limit is 6 to 18. <p><br>";
        }else if(strlen($password) <= 6){
            $error .= "<p> Password can't be less than 6 characters. <p><br>";
        }
    
        if($error==NULL){ // Then register him up lol
            require_once "mysqlConfig.php";

            //
            $result = $link->query("SELECT username FROM user WHERE username = '$username'");

            if($result->num_rows > 0)
                $u_err = "Username already exist!";

            if(NULL == $u_err){
                
            //
    
            $userkey = md5(time().$username);
            $isverified = "false";
            $verificationlink = md5(time().$username);
            $passwordresetlink = "NO";
            $password = md5($password);
            $categories = "none";
            $acctype = "member";

            $insert = $link->query("INSERT INTO user(username,password,email,acctype,userkey,isverified,verificationlink,registrationdate,passwordresetlink,categories)
            VALUES('$username','$password','$email','$acctype','$userkey','$isverified','$verificationlink',CURDATE(),'$passwordresetlink','$categories')");
    
            if($insert){
                //Sending mail
                $to = $email;
                $subject = "Scads email verification.";
                //$message = "<a href='http://3.7.21.219/scads/verify.php?vkey=$verificationlink'> Verify account </a>";
                $message = "Yo";
                $headers = "From: scads@mailbox.com \r\n";
                $headers .= "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
                //** NEED TO SETUP MALE SERVER FOR EMAILS */
                /*
                $mm = mail($to,$subject,$message,$headers);
                if(!$mm)
                    echo $mm->error;
                else
                    echo "mail sent";*/
                //
                $cookie_name = "userkey";
                $cookie_value = $userkey;

                setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 30), "/"); // 86400 = 1 day
                $redi_msg = "Redirecting you soon!";
                header("location: signup-cat.php?firsttime=yes");
    
            }        
            else
                echo $link->error;
    
        }
        }
    
    }
    
}
else{
    //Return to main page
    header("location: signup.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/css-acc.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="js/js-acc.js"></script>

</head>
<body id="body-container" onload="loginScaler();" onresize="loginScaler();">
    
            

    <div class="gridd">
        <div class="inlinee abstract-1">
            
        </div>
        
        <form id="signup-form" action="" method="POST" autocomplete="off"> 
                <h3>And One step away! </h3><br>
                <p id = "username-error" class="error"> <?php if(NULL != $u_err){echo $u_err;}?> </p>
                <input id="username" type="text" name="username" class="form-control txtbox" onclick="U();" placeholder="Enter username!" required > <br>
                <p id = "password-error" class="error">  </p>
                <input id="password" type="password" name="password" class="form-control txtbox" onclick="P();" placeholder="Enter password!" required> <br>
                 
                <?php echo
                "<input type='text' name='emails' style='display:none;' value='$email'>";  
                ?>
                <br>
                

                <input type="submit" name="submit" value="Signup!" id="next-button" disabled>
        
                <br><br>
        </form>

        
    </div>

    <style id="login-scaler"> </style>
    <script>

        function loginScaler(){
            var html = "";
            
                html = `
                    .abstract-1{
                        width:10%;
                        height:100%;
                    }
                `;
            
            document.getElementById('login-scaler').innerHTML = html;
        }
        
    </script>
</body>
</html>