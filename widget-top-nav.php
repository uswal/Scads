<?php
$username = NULL;
if(isset($_COOKIE['userkey'])){
    $userkey = $_COOKIE['userkey'];

    require_once "mysqlConfig.php";
    $result = $link->query("SELECT username FROM user WHERE userkey='$userkey'");
    $row = mysqli_fetch_row($result); 
        
    $username = $row[0];
}
?>
<html>
<body>

    <link rel="stylesheet" href="css/css-nav.css">    
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="js/js-acc.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


    <header id="main-header">
            <nav id="nav-container">
                <a id="nav-logo" href="index.php"><img src="assets/icons/Scads-1.png" width="150" height="60"></a>
                
                <ul id="nav-contents" >
                    
                </ul>
            </nav>
    </header>

    <input type="text" id="un" value="<?php echo $username;?>" style="display:none">

    <script>
        /*let getDPN = "nulldp.webp";
        function getDP(){
           
            $.ajax({
            url:`http://localhost/scads/assets/dp/${username}.jpg`,
            type:'HEAD',
            async: false,
            error: function()
            {
                getDPN = "nulldp.webp";
               // console.log("FAIL");
            },
            success: function()
            {
                
                getDPN = username + ".jpg";
                //console.log(`DP : ${getDPN}`);
            }
            });

            console.log(getDPN);
        }*/
        //navScaler();
        //PC version
        document.getElementById("main-header").addEventListener("resize", navScaler());
        function navScaler(){ //TODO:
            //console.log("Exec?");
            /*if(document.body.clientWidth < 800){
                document.getElementById('nav-scaler').innerHTML = `
                    <style>
                        #trending{
                            display:none;
                        }
                        #signup-btn{
                            display:none;
                        }
                    </style>
                `;
            }
            else{
                document.getElementById('nav-scaler').innerHTML = `
                    <style>
                        #trending{
                            display:block;
                        }
                        #signup-btn{
                            display:block;
                        }
                    </style>
                `;
            }*/
        }
        var username = null;
        var isLogin = false;

        function loginStatus(){
            username = document.getElementById('un').value;

            if(username != ""){
                isLogin = true;
            }else{
                isLogin = false;                
            }

            //console.log(`Login status : ${isLogin}`);

        }
        
        function logout(){
            document.cookie = "userkey=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            location.reload();
        }

        function navElements(){
            if(isLogin){
                //console.log("logged in");
                var html = `
                    <li id="login-btn" onclick="logout();">Logout</li>
                `;
                document.getElementById('nav-contents').innerHTML += html;
            }else{
                //console.log("NOT logged in");
                var html = `
                    <li id="login-btn" onclick="popup('login.php');">Login</li>
                    <li id="signup-btn" onclick="popup('signup.php');">Signup</li>
                `;
                document.getElementById('nav-contents').innerHTML += html;
            }
        }

    
        
        //Jquery
        function popup(pagename){
            if($(window).width() < 800){
                window.location.href = pagename;
            }
            dim(true);
            var close = false;
            
            //var page = "signup.php";
            var $dialog = $('<div><div>')
            .html('<iframe class="iframe" src="' + pagename + '" width="100%" height="100%"></iframe>')
            .dialog({
                autoOpen: false,
                show:'fade',
                modal: true,
                height: 600,
                width: 800,
                dialogClass:'jq',
                buttons: {
                    "x": function () {
                        $dialog.dialog("close");
                        dim(false);
                        close = true;
                    }
                }

            });

            $dialog.dialog('open');
            
            //Check for success
            var loop = setInterval(function(){
                var gett = localStorage.getItem("reloadme");
                console.log(gett);

                if(gett == "true"){
                    dim(false);
                    $dialog.dialog('close');
                    close=true;
                    location.reload();
                }
                
                if(close == true){
                    clearInterval(loop);
                }
            },10);

            $(".ui-dialog-titlebar").css("display","none");

        }

        function checkPostWidget(){
            if(isLogin!=true)
            document.getElementById('hid-tog').innerHTML = `
                <style>
                    #container-createpost{
                        display:none;
                    }
                    #com-tog{
                        display:none;
                    }
                </style>
            `;
        }


        //Always load these in last
        loginStatus();
        //getDP();
        navElements();
        checkPostWidget();
       var gg = document.getElementById("main-header").clientWidth;
       console.log(gg);
    </script>


    <div id="nav-scaler"></div>
    <div id="dimmer"></div> 
    <div id="hid-tog"></div>

    <!--
        <li><input id="search-box" type="text" placeholder="Search here!"></li>
                    <li id="user">
                        
                        <div class="dropdown">
                            <input class="dropbtn" type="image" src="assets/dp/${getDPN}" alt="Submit">
                            <div class="dropdown-content">
                            <a href="#">Profile</a>
                            <a href="#">Settings</a>
                            <hr class="nav-hr">
                            <a href="#">Help</a>
                            <a href="#">Contact</a>
                            <hr class="nav-hr">
                            <a href="#">Activity</a>
                            <hr class="nav-hr">
                            <a href="#" onclick="logout();">Logout</a>
                            </div>
                        </div>
                        
                    </li>

    -->
</body>
</html>