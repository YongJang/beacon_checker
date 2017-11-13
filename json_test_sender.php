<!DOCTYPE html>
<html>
<head>
<script>
obj = { "table":"customers", "name":"doyun", "number":50, "limit":10 };
dbParam = JSON.stringify(obj);
xmlhttp = new XMLHttpRequest();
/*
xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        alert(this.responseText);
        myObj = JSON.parse(this.responseText);
        for (x in myObj) {
            txt += myObj[x].name + "<br>";
        }
        document.getElementById("demo").innerHTML = txt;
    }
};
*/
xmlhttp.open("POST", "./json_test.php");
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
alert(dbParam);
xmlhttp.send("x=" + dbParam);
</script>
</head>
<body>
  <div id="demo">
    hi
  </div>
</body>
</html>
