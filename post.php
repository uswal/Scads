<?php
    $get = $_GET['link'];

    $username = NULL;
    if(isset($_COOKIE['userkey'])){
        require "mysqlConfig.php";

        $userkey = $_COOKIE['userkey'];
        $result = $link->query("SELECT username FROM user WHERE userkey='$userkey'");
        $row = mysqli_fetch_row($result);
        $username = $row[0];
    }
?>
<!DOCTYPE html>
<html>
<head>

    <title id="post-titlebar">  </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <title>Post</title>

    <!--Navigation bar *Requires jquery*-->    
    <div id="nav-placeholder" class="fixed-top">

    </div>

    <script>
    $(function(){
    $("#nav-placeholder").load("widget-top-nav.php");
    });
    </script>
    <!--end of Navigation bar-->
    
</head>
<body id="body-container" onresize="bodyScaler();" onload="bodyScaler();getContents();">    
    
    <div id="scale-helper"></div>
    <style id="like-helper"></style>
    <input type="text" id="link" value="<?php echo $get; ?>" style="display:none;">
    <input type="text" id="uname" value="<?php echo $username; ?>" style="display:none;">

    <div class="row-p">
        <div class="col-p1">
            <label class="auth" id="auth-q"></label>
            <br><label class="post-title" id="title"></label>
            <p class="post-contents" id="paragraph"></p>
            <br><div id="pst-img"> </div>
            <hr>
            <label id="like-count"></label>
            <!-- Comments -->
            <br>
            <div id="com-tog">
                <textarea id="post-comments" placeholder="Join discussion."></textarea>
                 <button type="text" class="submit-btn" onclick="submitComment();">Submit</button>
            </div>
            <hr>
            <div class="comments-container" id="comments-container-m"> <!-- User comments inside here... -->
        
        
            </div>

        </div>
    </div>

    <style>
        *{
            margin:0;
            padding:0;
        }        
        #body-container{
            background:whitesmoke;
        }
        .row-p{           
            margin-top:80px;
            width:100%;
        }
        .col-p1{
            left:0;
            background:white;
            border-radius:5px;
            padding:10px;
        }
        .auth a,.auth{
            color:Grey;
            font-size:14px;
        }
        .auth{
            margin-left:15px;
        }
        .post-title{
            margin:0 15px;
            padding:0;
            font-size:35px;
            font-weight:bold;
        }
        .post-image{
            width:100%;
            margin-bottom:6px;
        }
        .post-contents{
            margin:0 15px;
        }
        
        .col-p1 #post-comments{
            resize:none;
            width:90%;
            height:100px;
            transition:0.3s;
            background:whitesmoke;
            margin:4px 20px;
            padding:10px;
        }
        #post-comments:focus{
            height:200px;
        }
        .user-id{
            font-size:25px;
            font-weight:700;
            color:black;
        }
        .comment-container{
            margin:0 15px;
        }
        .user-id a:hover{
            text-decoration:none;
        }
        .datestyle{
            color:grey;
            margin-left:10px;
        }
        #like-count{
            margin-top:10px;
            font-weight:500;
            color:grey;
        }
        hr{
            margin:0;
            padding:0;
        }
        .submit-btn{
            margin-left:20px;
            padding:6px 20px;
            background:green;
            border:none;
            font-weight:bold;
            color:white;
            border-radius:4px;
            margin-bottom:10px;
        }
        .comments-container{
            margin:10px 20px;
            display:flex;
            flex-direction:column-reverse;
            transition:0.4s;
        }
        .row-z a{
            font-weight:600;
            font-size:18px;
        }
        .row-z p{
            font-size:16px;
            padding:10px;
        }
        a:hover{
            text-decoration:none;
        }
        #is-liked{
            cursor:pointer;
        }
    </style>

    <script>
       var commentCounter = 0;
       var likeCounter = 0;
       var likeList = "";

        function likeIt(){
            //console.log("LIKIT");

            var getLink = document.getElementById("link").value;
            var username = document.getElementById('uname').value;
            var html = "";
            
            if(username == "")
                return false;
            
            let xhr = new XMLHttpRequest(); 
            let url = "http://3.7.21.219:9999/addLike"; 
            xhr.open("POST", url, true); 
            xhr.setRequestHeader("Content-Type", "application/json"); 

            
            xhr.onreadystatechange = function () { 
                if (xhr.readyState === 4 && xhr.status === 200) { 
                    var l = this.responseText.replace(/"/g,"");
                    if(l == "liked"){
                        //console.log("liked");
                        likeCounter += 1;
                        document.getElementById("like-count").innerHTML = `&emsp; ${likeCounter} <label id="is-liked" onclick="likeIt();">Like</label> &emsp; &emsp; ${commentCounter} Comments`;
                        html = `#is-liked{
                            font-weight:800;
                            }
                        `;
                    }
                    else{
                        //console.log("unliked");
                        likeCounter -= 1;
                        document.getElementById("like-count").innerHTML = `&emsp; ${likeCounter} <label id="is-liked" onclick="likeIt();">Like</label> &emsp; &emsp; ${commentCounter} Comments`;
                        html = `#is-liked{
                            font-weight:500;
                            }
                        `;
                    }
                    xhr.abort(); 
                    document.getElementById('like-helper').innerHTML = html;
                } 
            }; 
            var data = JSON.stringify({ "post_link":getLink,"username":username }); 
            xhr.send(data);

        }

        function submitComment(){
            var getLink = document.getElementById("link").value;
            var comm = document.getElementById("post-comments").value;
            var username = document.getElementById('uname').value;


            if(comm.trim() == "")
                return false;
                
            let xhr = new XMLHttpRequest(); 
            let url = "http://3.7.21.219:9999/postComment"; 
            xhr.open("POST", url, true); 
            xhr.setRequestHeader("Content-Type", "application/json"); 
            xhr.onreadystatechange = function () { 
                if (xhr.readyState === 4 && xhr.status === 200) { 
                    xhr.abort(); 
                } 
            }; 
            var data = JSON.stringify({ "comment":comm,"post_link":getLink,"username":username }); 
            xhr.send(data);

            document.getElementById("post-comments").value = "";
            commentCounter += 1;
            document.getElementById("like-count").innerHTML = `&emsp; ${likeCounter} <label id="is-liked" onclick="likeIt();">Like</label> &emsp; &emsp; ${commentCounter} Comments`;
            var html = `
                        <div class="row-z">
                            <a href="#">${username}</a>
                            <p>${comm}</p>
                            <hr>
                        </div>
                    `;
            document.getElementById('comments-container-m').innerHTML += html;
        }
        function getContents(){
            var getLink = document.getElementById("link").value;

            $.ajax({url: `http://3.7.21.219:9999/postUrl?link=${getLink}`, success: function(result){                        
                    //console.log(result);

                    if(result.length!=0){
						likeList = result[0].like_list;
						console.log("EXEC CONT");
						
                        var author = result[0].author;
                        var cate = result[0].categories;
                        document.getElementById('title').innerHTML = result[0].title;
                        document.getElementById('paragraph').innerHTML = result[0].paragraph;
                        commentCounter = result[0].comments;
                        likeCounter = result[0].likes;

                        var imglinks = result[0].images_links.split(",");
                        for(i=0; i<imglinks.length ;i++){
                            if(imglinks[i].trim() == "")
                                continue;
                            
                            document.getElementById("pst-img").innerHTML += `<img class="post-image" src="${imglinks[i]}">`;
                        }

                        //Date conversion
                        var temp = result[0].creation_datetime.split(" ");
                        temp = temp[0].split("");
                        var date = ""+temp[8]+temp[9]+'/'+temp[5]+temp[6]+"/"+temp[0]+temp[1]+temp[2]+temp[3];
                        //document.getElementById("date-e").innerHTML = date;
                        //Posted by USER on DATE in CATEGORY
                        var html = `
                            Posted by <a href="#" id="author">${author}</a> on ${date} in <a href="index.php?cat=${cate}" id="pst-cat">${cate}</a>
                        `;
                        document.getElementById('auth-q').innerHTML = html;
                    }else{
                        document.getElementById('title').innerHTML = "PAGE NOT FOUND";
                    }

                    document.getElementById("like-count").innerHTML = `&emsp; ${result[0].likes} <label id="is-liked" onclick="likeIt();">Like</label> &emsp; &emsp; ${result[0].comments} Comments`;
                    getComment(result[0].comment_list);
					getLikeF(result[0].like_list);
					
            }});

        }

        function bodyScaler(){
            console.log("Scalinmg?");
            if($(window).width() < 800){
                document.getElementById('scale-helper').innerHTML = `
                    <style>
                        .col-p1{
                            margin:0;
                        }
                    </style>
                `;
            }
            else{
                document.getElementById('scale-helper').innerHTML = `
                    <style>
                        .col-p1{
                            margin:0 20%;
                        }
                    </style>
                `;
            }
        }
        function getComment(str){ //Always call this is last
			if(str == null || typeof str == "undefined")
				return;
			
            var getLink = document.getElementById("link").value;
            
                var html = "";
                str=str.split("::::,");
                for(i=0 ; i < str.length - 1 ; i++){ //Ex, user1::::-comment1::::,user2::::-comment2::::,
                    var temp = str[i].split("::::-"); //Ex, username::::-comment
                    
                    html += `
                        <div class="row-z">
                            <a href="#">${temp[0]},</a>
                            <p>${temp[1]}</p>
                            <hr>
                        </div>
                    `;
                    
                }
               document.getElementById('comments-container-m').innerHTML = html;
			   
        }
        function getLikeF(str){
			console.log("GET LIKE IS CALLED");
			if(str == null || typeof str == "undefined")
				return;
			
            var username = document.getElementById('uname').value;
            var html = "";
			console.log(username);
            var x = str.indexOf(username);
            if(x==-1 || username==""){
                html = `#is-liked{
                    font-weight:500;
                    }
                `;
            }else{
                html = `#is-liked{
                    font-weight:800;
                    }
                `;
                document.getElementById('like-helper').innerHTML = html;
            }
        }
		
		
		
		
    </script>
</body>
</html>
