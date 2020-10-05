<?php
$error = NULL;
echo "<script> localStorage.setItem('reloadme',false); </script>";

if(isset($_GET['submit'])){
    //Get data
    $email  = $_GET['email'];
    
    if(strlen($email)>40)
        $error = "Out of email's character limit!";

    if($error==NULL){ // Then register him up lol
        require_once "mysqlConfig.php";

        $result = $link->query("SELECT email FROM user WHERE email = '$email'");

        if($result->num_rows > 0)
            $error = "Email already exist!";
        else
            header("location: signup-1.php?email=".$email);

    }

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

    

</head>
<body id="body-container" onload="loginScaler();" onresize="loginScaler();">
    
            

    <div class="gridd">
        <div class="inlinee abstract">
            
        </div>
        <form id="signup-form" action="" method="GET" autocomplete="off"> 
                <h3>Hello friend!</h3><br><br>
                <label class="email-label" for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control" required>
                <p class="error"> <?php if($error!=NULL){echo $error."<style>#email{color:rgb(145, 1, 1);} </style>";} ?> </p><br>
                <input type="submit" name="submit" value="Next!" id="next-button">
                <br><br>
                <p> Already a member? <a href="#" class="message-link"><b>LOG IN</b></a></p>
                <p> You can view contents without registering.</p>
                <p> Posting and replying requires registration.</p>
        </form>
        
    </div>
    <style id="login-scaler"> </style>
    <script>

        function loginScaler(){
            var html = "";
            
                html = `
                    .abstract{
                        width:10%;
                        height:100%;
                    }
                `;
            
            document.getElementById('login-scaler').innerHTML = html;
        }
        
    </script>
</body>
</html>