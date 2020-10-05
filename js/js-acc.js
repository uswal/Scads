var allowButtonU = false;
var allowButtonP = false;
let u="str";
let p="str";


function dim(bool)
{  
    if (typeof bool=='undefined') bool=true; // so you can shorten dim(true) to dim()
         document.getElementById('dimmer').style.display=(bool?'block':'none');
} 

function enableSignupButton(){
    if( allowButtonU == true && allowButtonP == true)
        document.getElementById("next-button").disabled = false;
    else 
        document.getElementById("next-button").disabled = true;   
}
function U(){
    allowButtonU = false;
    enableSignupButton();

    u = document.querySelector('#username');
    // Add an event listener to monitor changes
    u.addEventListener('keyup', function (e) {
        if (/\s/.test(u.value)) {
            document.getElementById('username-error').innerHTML = "Username can't contain space!";
            allowButtonU = false;
            enableSignupButton();
        }
        else{
            if(u.value.length < 6 ){
                document.getElementById('username-error').innerHTML = "Username too short!";
                allowButtonU = false;
                enableSignupButton();
            }else if(u.value.length > 18){
                document.getElementById('username-error').innerHTML = "Username too long!";
                allowButtonU = false;
                enableSignupButton();
            }else{
                document.getElementById('username-error').innerHTML = "";
                allowButtonU = true;
                enableSignupButton();
            }
        }
        
    });
}
function P(){
    allowButtonP = false;
    enableSignupButton();
    
    p = document.querySelector('#password');
    // Add an event listener to monitor changes
    p.addEventListener('keyup', function (e) {
        
        if(p.value.length < 6 ){
            document.getElementById('password-error').innerHTML = "Password is too short.";
            allowButtonP = false;
            enableSignupButton();
        }else if(p.value.length > 18){
            document.getElementById('password-error').innerHTML = "Password is too long.";
            allowButtonP = false;
            enableSignupButton();
        }else{
            document.getElementById('password-error').innerHTML = "";
            allowButtonP = true;
            enableSignupButton();
        }
    });

    
}