/* Important notes
  1. On executing system first time keep the get post counter from mysql table off, because it node can't
        get the last post_no and will crash the server.
 */

const mysql = require('mysql');
const express = require('express');
const app = express();

var cmd = "";
var con = mysql.createConnection({ 
    host: "piyush.crhyw7aw5mof.ap-south-1.rds.amazonaws.com",
port:"3333",
    user: "admin",
    password: "piyush3t",
    database: "scads"
});
con.connect(function(err) {
    if (err) throw err;
    console.log("Mysql is Connected!");
}); //Established MySql 

app.use(function(req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    next();
}); //Fixed CORS

var postCounter = 0;

con.query("SELECT post_no FROM posts ORDER BY post_no DESC LIMIT 1", function (err, result) {   // * 1.
    postCounter = result[0].post_no;
    postCounter += 1;
});





app.use(express.json()) // for parsing application/json
app.use(express.urlencoded({ extended: true })) // for parsing application/x-www-form-urlencoded


// --- MAIN CODE START FROM HERE -------

app.post('/addLike',function(req,res){
    var post_link = req.body.post_link;
    var username = req.body.username;

    con.query(`SELECT like_list,likes FROM posts WHERE post_link='${post_link}'`,function(err,result){
        var like_list = result[0].like_list;
        var nLikes = 0;
        var reply = "liked";

        if(like_list == null || like_list.trim() == ""){
            nLikes = 1;
            like_list = `${username},`;
        }
        else{
            var x = like_list.indexOf(username);

            if(x==-1){
                like_list += `${username},`;
            }else{
                like_list = like_list.replace(`${username},`,"");
                reply = "unliked";
                //console.log(like_list);
            }

            var temp = like_list.split(",");
            nLikes = temp.length-1;
           // console.log(`${nLikes} Likes`);
        }
        

        con.query(`UPDATE posts SET like_list='${like_list}',likes=${nLikes} where post_link='${post_link}'`,function(err,result){
            res.json(reply);
            //console.log(reply);
        });

    })
})
app.post('/postComment',function(req,res){
    var comment = req.body.comment;
    var post_link = req.body.post_link;
    var username = req.body.username;
    comment = `${username}::::-${comment}`;
    console.log(comment);

    //First get existing comments
    con.query(`SELECT comment_list FROM posts WHERE post_link='${post_link}'`,function(err,result){

        var comment_list = result[0].comment_list;
        var nComments = 0;

        if(comment_list == null){
	nComments = 1;

            comment_list = "";
	}
        else{
            var temp = comment_list.split("::::,");
            nComments = temp.length;
            console.log(nComments);
        }

        comment_list += `${comment}::::,`;
        con.query(`UPDATE posts SET comment_list='${comment_list}',comments=${nComments} where post_link='${post_link}'`,function(err,result){
            res.json("done");
        });

    });
})

app.get('/contents',function(req,res){
    var cat = req.query.cat;

    var ll = req.query.num;
    var ul = ll+10;

    if(typeof cat == "undefined")
        cmd = `SELECT title,paragraph,images_links,categories,author,likes,comments,creation_datetime,post_link FROM posts
        ORDER BY creation_datetime DESC LIMIT ${ll},${ul}`;
    else
        cmd = `SELECT title,paragraph,images_links,categories,author,likes,comments,creation_datetime,post_link FROM posts
        WHERE categories='${cat}'
        ORDER BY creation_datetime DESC LIMIT ${ll},${ul}`;

    con.query(cmd, function (err, result) {
            res.json(result);
    });
})

app.get('/getPostCounter',function(req,res){
    res.json(postCounter);
})

app.get('/postUrl',function(req,res){ 

    var post_link = req.query.link;
    //console.log(post_link)
    cmd = `SELECT title,paragraph,images_links,categories,author,likes,comments,comment_list,like_list,creation_datetime FROM posts WHERE post_link='${post_link}'`;
    con.query(cmd, function (err, result) {
        res.json(result);
    });
})

app.post('/postIt', function (req, res, next) {


    var postLink = req.body.title.replace(/[^a-zA-Z ]/g,"_");
    postLink = postLink.replace(/ /g,"_");
    postLink = postLink.substr(0,10);
    postLink += `_${postCounter}`;
    postCounter += 1;
    console.log(`Post link : ${postLink}`);

    cmd=`SELECT username FROM user WHERE userkey='${req.body.userkey}'`;    
    con.query(cmd, function (err, result) {
        var username = result[0].username;
        if(username != undefined){
            //Adding record to table
            cmd = `INSERT INTO posts(title,paragraph,creation_datetime,post_link,author,images_links,categories)
            VALUES('${req.body.title}','${req.body.para}',NOW(),'${postLink}','${username}','${req.body.imglinks}','${req.body.cat}')
            `;
            con.query(cmd, function (err, result) {
                if (err) throw err;
            });

        }
        if (err) throw err;
        
    });


    res.json(postLink);

    //Extra feature - #tag extractor ** MIGHT BE GARBAGE LOL
    var isHasThere = req.body.para.indexOf('#');

    if(isHasThere!=-1){
        var hasList = [];
        var one = req.body.para.split('#');

        for(i=0;i<one.length;i++){
            if(one[i] == "")
                continue;

            if(i==0 && one[i].indexOf('#' == -1))
                continue;

            one[i] = `#${one[i]}`;
            var two = one[i].split(" ");
            for(j=0;j<two.length;j++){
                var s = two[j][0];
                if(s == "#"){
                    hasList.push(two[j]);
                }
            }
        }

    for(i=0;i<hasList.length;i++)
        console.log(hasList[i]);
        //TODO: do something about # lol
    }
    

})

app.listen(9999, () => console.log(`Example app listening at http://127.0.0.1:9999`))