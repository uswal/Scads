var para = "norme normie bot son of fucking fuck is so bad this is bad";
var isHasThere = para.indexOf('#');

if(isHasThere!=-1){
    var hasList = [];
    var one = para.split('#');

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
}
