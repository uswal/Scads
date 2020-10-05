<?php
$getCat = NULL;
if(isset($_COOKIE['userkey'])){
    $userkey = $_COOKIE['userkey'];

    require "mysqlConfig.php";

    $result = $link->query("SELECT categories from user where userkey='$userkey'");
    $row = mysqli_fetch_row($result); 

    $getCat = $row[0];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <input type="text" id="get-cat-q" value="<?php echo $getCat; ?>" style="display:none">;
    <div id="cat-follow-modifier"></div>
    <script src="js/cat-variables.js"></script>


   <div id="cat-nav-body">

        <div id="followed-threads-container">
            <label id="label-followed">Following! <a href="signup-cat.php">(update)</a></label>
            <ul id="followed-threads">
          
            </ul>
        </div>

        <div id="all-threads-container">
            <label id="label-all">All threads!</label>
            <ul id="followed-all">
                <!-- GENERATED THROUGH JS -->
            </ul>
        </div>


    </div>

    


    <style>
        #cat-nav-body{
            position:fixed;
            height:100vh;
            width:22vw;
            overflow:scroll;
        }
        #cat-nav-body a{
            text-decoration:none;
        }
        #cat-nav-body a:hover{
            text-decoration:none;
        }

        .cat-li{
            padding:10px 20px;
            border:1px solid whitesmoke;
            color:#00acee;
        }
        .cat-li:hover{
            text-decoration:none;
            background:whitesmoke;
            padding:10px 20px;
            color:#00acee;
        }
        .cat-li a:hover{
            text-decoration:none;
            background:whitesmoke;
            padding:10px 20px;
            color:#00acee;
        }
        .widget-cat-hr{
            margin:0;
            padding:0;
        }
        .li-logo{
            width:30px;
            height:30px;
        }

        #followed-threads-container,#all-threads-container{
            float:right;
            margin-right:2%;
            background:white;
            width:70%;
            border-radius:6px;
            margin-top:40px;
        }
        #all-threads-container{
            margin-bottom:100px;
        }

        #label-followed,#label-all{
            background:rgba(0, 0, 255, 0.3);
            padding:10px 22px;
            width:100%;
            height:100%;
            font-weight:bold;
            font-size:20px;
            margin:0;
        }
        #label-all{
            background:rgba(231, 0, 135, 0.3);
        }
    </style>



    <script>
		var str = document.getElementById("get-cat-q").value;
		console.log(str);
        //Init
        var html = "";

        //All threads 
        html = "";
        for(i=0;i<cat.length;i++){
            html += `<li><a href="index.php?cat=${cat[i]}"><div class="cat-li"><img class="li-logo" src="assets/icons/${cat[i]}.svg">&emsp; ${cat[i]}</div></a></li>`;            
        }
        document.getElementById("followed-all").innerHTML = html;


        //Hide unhide Followed container
        if(document.getElementById("get-cat-q").value == ""){
            //console.log(document.getElementById("get-cat-q").value);
            document.getElementById('cat-follow-modifier').innerHTML = `
                <style>
                    #followed-threads-container{
                        display:none;
                    }
                </style>
            `;
        }else{
            var list = document.getElementById("get-cat-q").value.split(",");
            html = "";
            for(i=0;i<list.length;i++){
                if(list[i] == "")
                    continue;
                
                html += `<li><a href="index.php?cat=${list[i]}"><div class="cat-li"><img class="li-logo" src="assets/icons/${list[i]}.svg">&emsp; ${list[i]}</div></a></li>`;            
            }
			document.getElementById("followed-threads").innerHTML = html;
			
        }
		

    </script>
</body>
</html>