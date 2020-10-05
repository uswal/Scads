<?php 
echo "<script> localStorage.setItem('reloadme',false); </script>";
$usercat = NULL;

require_once "mysqlConfig.php";

if (!empty($_REQUEST))
    $firsttime = $_REQUEST['firsttime']; //Just for our js
else
    $firsttime = "no";

if(empty($_COOKIE['userkey']))
  header("location: login.php");
else{
  $userkey = $_COOKIE['userkey'];
  $result = $link->query("SELECT categories FROM user WHERE userkey='$userkey'");
  $row = mysqli_fetch_row($result);
  $usercat = $row[0];
}

if(!empty($_REQUEST['categories'])){
  $categories = $_REQUEST['categories'];
  

  if(isset($_POST['submit'])){      
    $link->query("update user set categories='$categories' where userkey='$userkey'");
  }
    header("location: redirecting.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/css-cat.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="js/cat-variables.js"></script>
</head>
<body id="body-container">
    <label id="msg"> Looking for something?</label>
    <label id="msg-1">Select you prefered categories, atleast three.</label>

    <div class="container-p">
    
    <ul id="catcat">
      <!-- Rendered through JS -->  

    </ul>

      <form action="" method="POST" autocomplete="off"> 
          <input type="text" name="categories" id="categoriesp" style="display:none;">
          <input type="submit" name="submit" value="Done!" id="done-button" disabled>   
      </form>

    </div>
    <input type="text" id="first-time-check" value="<?php echo $firsttime; ?>" style="display:none;">
    <input type="text" id="pre-usercat" value="<?php echo $usercat; ?>" style="display:none;">

    <script>
        //Rendering categories first
        var html = "";
        for(i=0;i<cat.length;i++){
            html += `
            <li>  
                <input type="checkbox" id="${cat[i]}" class="cbox"  onclick="createList();"/>
                <label for="${cat[i]}"><img src="assets/icons/${cat[i]}.svg" /></label>
                <label class="caption">${cat[i]}</label>
            </li>         
            `;
          }
          document.getElementById("catcat").innerHTML = html;

        //Check if user has already following
        var fCheck = document.getElementById('first-time-check').value;
        if(fCheck != "yes"){
            var catx = document.getElementById("pre-usercat").value;
            catx = catx.split(",");

            for(i=0;i<catx.length;i++){
              if(catx[i].trim() != ""){
                document.getElementById(catx[i]).checked = true;
              }
            }
        }

        //Create list to submit
        var catCounter = 0;
        var completeStr = "";     
        function createList(){

                //Init 
                catCounter = 0;
                completeStr = "";

                for(i=0;i<cat.length;i++){
                    checkCat(cat[i]);
                }

                
                document.getElementById("categoriesp").value = completeStr;
      
                //Enable disable button
                if(catCounter > 2)
                    document.getElementById("done-button").disabled = false;
                else
                    document.getElementById("done-button").disabled = true;

            
            return false;
        }

        function checkCat(itemname){
            var tf = document.getElementById(itemname).checked;
            
            if(tf == true){
                completeStr += itemname + ","; // : - delimeter
                catCounter += 1;
            }            
        }
    </script>

    <style>
      #done-button{
        margin-left:40vw;
        border:none;
        font-size:20px;
        background:darkgreen;
        border-radius:4px;
        color:white;
        padding:5px;
        width:140px;
      }

      #done-button:disabled{
        background:grey;
      }
    </style>

</body>
</html>