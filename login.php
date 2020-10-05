<?php
$uore_err = NULL;
$password_err = NULL;

$redi_msg = NULL;
$fill_uore = NULL;

echo "<script> localStorage.setItem('reloadme',false); </script>";

if(isset($_POST['submit'])){
    //Get data
    $uore  = $_POST['uore'];
    $password = $_POST['password'];
    $password = md5($password);

    require_once "mysqlConfig.php";

    $result = $link->query("SELECT password,userkey FROM user WHERE username = '$uore'");
    if($result->num_rows < 1){
        $result = $link->query("SELECT password,userkey FROM user WHERE email = '$uore'");
        if($result->num_rows > 0){
            $password_err = phasetwo($result,$password);
            $fill_uore = $uore;
        }else{
            $uore_err = "Username or email not found";
        }

    }
    else{
        $password_err = phasetwo($result,$password);
        $fill_uore = $uore;
    }

}


function phasetwo($result,$password){

    while ($row = mysqli_fetch_row($result)) {
        $getPassword =  $row[0];
        $getKey = $row[1];
        
        if($password == $getPassword){
            $cookie_name = "userkey";
            $cookie_value = $getKey;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30 ), "/"); // 86400 = 1 day
            $GLOBALS['redi_msg'] = "Redirecting you soon! ";
            header("location: redirecting.php"); 
            return NULL;
        }else{
            return "Incorrect password!";
        }
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/css-acc.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="js/js-acc.js"></script>

    

</head>
<body  onload="loginScaler();" onresize="loginScaler();">
    
<div id="body-container">

    <div class="gridd">
        <div class="inlinee abstract-log">
            
        </div>
        <form id="signup-form" action="" method="POST" autocomplete="off"> 
                <h3>Welcome back!</h3><br><br>
                <input type="text" name="uore" id="uore" class="form-control" placeholder="Username or email" value="<?php echo $fill_uore;?>"required>
                <p class="error"> <?php echo $uore_err;?> </p><br>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <p class="error"> <?php echo $password_err; ?> </p><br>
                <input type="submit" name="submit" value="Login!" id="next-button">
                
                <br><br>
                <p class="redi-msg"> <?php if(NULL != $redi_msg){ echo $redi_msg; echo "<script> localStorage.setItem('reloadme',true); </script>";} ?></p><br>
                <p> Not yet registered?<a href="signup" class="message-link"><b> SIGNUP</b></a></p>
                <p> You can view contents without login.</p>
                <p> Posting and replying requires an account.</p>
        </form>
        
    </div>
</div>
    <style id="login-scaler"> </style>

    <script>

        function loginScaler(){
            var html = "";
            
                html = `
                    .abstract-log{
                        width:10%;
                        height:100%;
                    }
                    
                `;
            
            document.getElementById('login-scaler').innerHTML = html;
        }
        
    </script>
</body>
</html>