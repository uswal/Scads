<?php
    $userkey = NULL;
    if(isset($_COOKIE['userkey']))
	    $userkey = $_COOKIE['userkey'];
?>

<html>
<body>
	<input type="text" id="ukey" value="<?php echo $userkey; ?>" style="display:none;">
	
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="js/cat-variables.js"></script>

    <div id="container-createpost">

            <br><label class="lab-createpost"> Create title </label>            
            <br> <input type="text" id="title-createpost" onclick="unhideElems();characterCheck(200,'title-createpost','phase1');" placeholder="Let's keep the title short and unique.">

            <div class="toggle-hiddenelem">

                <br> <label class="lab-createpost" > Add Story</label>
                <br> <textarea id="paragraph-createpost" placeholder="Nice weather we have, don't we." style="resize:none;"></textarea>

                <br> <label class="lab-createpost"> Direct image links</label>
                <br> <textarea type="text" id="image-links-createpost" placeholder="Optional! Seperated by commas. Direct links generally ends with .jpg,.png, etc." style="resize:none;"></textarea>

                <br><br> <label class="lab-createpost"> Upload Images </label>
                <br> <input type="file" id="input-file" accept="image/*" multiple>
                <button id="btn-upload"> Uplaod me </button>

                <br><br> <label class="lab-createpost"> Categories </label>
                <br>
                    <input id="categories-createpost" list="opt" name="opt" placeholder="Type few texts and select category from dropdown.">
                    <datalist id="opt">
                        
                    </datalist>
                    
                <br> <p id="error-msg-createpost"></p>

                <br> <button id="post-btn" onclick="sendPostToNode();" disabled>Post!</button>
            </div>
        
    </div>

    <style>
        .lab-createpost{
            font-family:Helvetica;
            font-size:18px;
            font-weight:bold;
        }
        .hr-createpost{
            margin:0;
            padding:0;
        }
        #container-createpost{
            padding:0px 40px;
            color:black;
            border-radius:4px;
            padding-bottom:30px;
            transition:0.3s;
            text-align:center;
        }
        #paragraph-createpost{
            resize:none;
            height:auto;
            width:100%;
            height:75px;
            overflow:hidden;
            display:block;
            border:1px solid whitesmoke;
            border-radius:6px;
            padding:8px 16px;
            transition:0.3s;

        }
        #paragraph-createpost:focus{
            height:200px;
        }
        

        #title-createpost,#image-links-createpost,#categories-createpost{
            width:100%;
            border:1px solid whitesmoke;
            border-radius:6px;
            padding:8px 16px;
        }
        #image-links-createpost{
            height:75px;
        }
        #container-createpost .toggle-hiddenelem{
            display:none;
        }
        #post-btn{
            border:none;
            background:#09c732;
            font-size:20px;
            padding:8px 20px;
            color:White;
            font-weight:700;
            border-radius:4px;
            margin-top:2px;
        }
        #post-btn:disabled{
            background:grey;;
        }
        #error-msg-createpost{
            margin:0;
            padding:0;
            margin-top:5px;
            color:red;
        }
        
    </style>
    

    <script>
        
        //Fill the list
        var html = "";
        for(i=0;i<cat.length;i++){
            html += `<option value="${cat[i]}">`;
        }
        document.getElementById('opt').innerHTML = html;


        function randomBackground(){
        
            r = Math.floor((Math.random() * 255) + 1);
            g = Math.floor((Math.random() * 255) + 1);
            b = Math.floor((Math.random() * 255) + 1);

            document.getElementById("gen-back-color").innerHTML = `
                <style>
                #container-createpost{
                    background:rgba(${r},${g},${b},0.4);
                }
                </style>
            `;
        }
        
        function unhideElems(){
            document.getElementById('hid-tog-q').innerHTML = `
                <style>
                    #container-createpost .toggle-hiddenelem{
                        display:block;
                }
                </style>
            `;
            
        }
        function hideElems(){
            //NOT WORKING ATM
            document.getElementById('hid-tog-q').innerHTML = `
                <style>
                    #container-createpost .toggle-hiddenelem{
                        display:none;
                }
                </style>
            `;
            
        }
        function sendPostToNode(){
            document.getElementById('post-btn').disabled = true;
            
            var title = document.getElementById('title-createpost').value;
            var para = document.getElementById('paragraph-createpost').value;
			
			para = para.replace(/(\r\n|\n|\r)/gm,"<br>");
			
            var imglinks = document.getElementById('image-links-createpost').value;
            var cat = document.getElementById('categories-createpost').value;
            
            var userkey = document.getElementById("ukey").value;
			console.log(userkey);
			
            if(typeof userkey == "undefined"){
                return false;
            }
                
            uploadImages();

            let xhr = new XMLHttpRequest(); 
            let url = "http://3.7.21.219:9999/postIt"; 
        
            // open a connection 
            xhr.open("POST", url, true); 
  
            // Set the request header i.e. which type of content you are sending 
            xhr.setRequestHeader("Content-Type", "application/json"); 
  
            // Create a state change callback 
            xhr.onreadystatechange = function () { 
                if (xhr.readyState === 4 && xhr.status === 200) { 
                    var l = this.responseText.replace(/"/g,"");
                    //if(this.responseText != "")
                       //window.location.href = `post.php?link=${l}`;
  
                } 
            }; 
  
            // Converting JSON data to string 
            var data = JSON.stringify({ "title":title,"imglinks":imglinks,"para":para,"cat":cat,"userkey":userkey }); 
  
            // Sending data with the request 
            xhr.send(data);
            console.log("YEAH YEAH");
        }
        //Date validation below
        var phase1=false;

        function characterCheck(maxChar,elemId,phaseName,elemName){

            var bool = false;
            eval(`${phaseName} = ${bool}`);
            phaseCheck();

            var textbox = document.querySelector(`#${elemId}`);

            textbox.addEventListener('keyup', function (e) {
                
                if(textbox.value.length < 5){
                    bool = false;
                }else if(textbox.value.length > maxChar){
                    bool = false;
                }else{
                    bool = true;
                }

                eval(`${phaseName} = ${bool}`);
                phaseCheck();
            });
        }

        function phaseCheck(){

            if(phase1 == true)
                document.getElementById('post-btn').disabled = false;
            else
                document.getElementById('post-btn').disabled = true;

        }


        randomBackground(); //Just a cool stuff
        
    </script>

<script>       
        //Get post counter number
        var nextPostNum = 0;
        
        const inpFile = document.getElementById("input-file");
        const btnUpload = document.getElementById("btn-upload");

        btnUpload.addEventListener("click", function(){
            console.log(inpFile.length);

            const xhr = new XMLHttpRequest();
            const formData = new FormData();

            console.log(inpFile.files);

            for(const file of inpFile.files){
                formData.append("myFiles[]", file);
            }

            xhr.open("post", `widget-createpost-upload.php`);
            xhr.send(formData);
        });
    </script>

    <div id="gen-back-color"></div>
    <div id="hid-tog-q"></div>
</body>
</html>