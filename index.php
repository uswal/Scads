<?php
$cat = null;
if(isset($_GET['cat']))
    $cat = $_GET['cat'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/css-home.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    

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
<body id="body-container" onload="getContents();bodyScaler();" onresize="bodyScaler();">    
    <!-- Hidden Elements -->
    <input type="text" id="get-cat" value="<?php echo $cat; ?>" style="display:none;">
    <style id="bs-helper"></style>
    <!-- My grid -->
        
            <div class="row-m" >
                <div class="col-a" >
                    <!-- left pane open -->
                    <div id="nav-left" style="display: flex;overflow:scroll;flex-direction:column;">
                        <script>
                            $(function(){
                            $("#nav-left").load("widget-cat-nav.php");
                            });
                        </script>
                    </div>
                    <!-- left pane close -->
                </div>
                <div class="col-b">
                    <!-- middle pane -->
                    <p class="container-y" id="render-contents"> 
                        <!-- Post pane -->


                        <div class="post-container" style="padding:0;margin:10px 0;">
                            <div id="widget-createpost"> </div>
                            <script>                                
                                $(function(){
                                $("#widget-createpost").load("widget-createpost.php");
                                });                                
                            </script>
                        </div>
                        <!-- Contents-->
                        
                        <!-- POST END -->
                    </p>
                </div>
                <div class="col-c">
                    
                    <!-- TODO --> 
                </div>
            </div>
            
    <!-- MY GRID -->
    <script>
        function bodyScaler(){
            console.log("resizing");
            var html = "";
            if($(window).width() < 800){
                html = `.col-a,.col-c{
                    display:none;
                    }
                    .col-b{
                        left:0;
                        width:100%;
                    }
                    .post-container{
                        margin-left:0;
                        margin-right:0;
                        width:100%;
                    }
                    `;
            }else{
                html = `.col-a,.col-c{
                    display:block;
                    }
                    .col-b{
                        left:25vw;
                        width:55vw;
                    }
                    `;
            }
            document.getElementById("bs-helper").innerHTML = html;
        }
        var ll = 0; //Send lower limit
        function getContents(){
            var cat = document.getElementById("get-cat").value; // get category lul

            if(cat != "")
                cat = `&cat=${cat}`;
            
            $.ajax({url: `http://3.7.21.219:9999/contents?num=${ll}${cat}`, success: function(result){
                
                html = "";
                for(i=0;i<result.length;i++){
                    var cc = "";
                    var tmig = [];
                
                    if(result[i].images_links != ""){
                        if(result[i].images_links.indexOf(',') == -1)  
                            cc = `<img class=img src="${result[i].images_links}"`;
                        else{
                            tmig = result[i].images_links.split(",");
                            cc = `<img class=img src="${tmig[0]}"`;
                        }
                    }
                    //console.log(cc);

                    //Short description
                    var temppara = "";
                    if(result[i].paragraph.length > 600)
                        temppara = result[i].paragraph.substr(0,600) + `...<br><a class="normie-href" href="post.php?link=${result[i].post_link}">read more</a>...`;
                    else
                        temppara = result[i].paragraph;

                    //Extract Date
                    var temp = result[i].creation_datetime.split(" ");
                    temp = temp[0].split("");
                    var date = ""+temp[8]+temp[9]+'/'+temp[5]+temp[6]+"/"+temp[0]+temp[1]+temp[2]+temp[3];

                    html = `
                    <div class="post-container">                    
                            <a href="post.php?link=${result[i].post_link}" class="post-title">${result[i].title}</a>
                            <br><label class="auth"> Posted by <a href="#" id="author">${result[i].author}</a> on <label id="date">${date}</label> in <a href="index.php?cat=${result[i].categories}" id="pst-cat">${result[i].categories}</a></label>
                            <p class="para">${temppara}</p>
                            ${cc}
                            <br>
                            <div class="ff">
                            <a class="com-btn" href="post.php?link=${result[i].post_link}">${result[i].likes} Likes</a> 
                            <a class="com-btn" href="post.php?link=${result[i].post_link}">${result[i].comments} Comments</a>
                            </div>
                    </div>
                    `;

                    document.getElementById("render-contents").innerHTML += html;
                }
            }});

            ll += 10;
        }  
        var maxScroll = 2000;
        window.addEventListener('scroll', function() {
            
            if( window.pageYOffset > maxScroll ){
                getContents();
                maxScroll += 2000;
            }
            
        });
    </script>


</body>
</html>
