<!DOCTYPE html>
<!--
<html>
<head>
<script type="text/javascript" src="js/jquery-1.9.1.js">
<script type="text/javascript">
$(function() {
    $("#output").html('hahaha');
    alert('shit');
});
</script>
</head>
<body onload="alert('holy');">
<a href="javascript: window.open('test.php', 'newwindow');">新窗口</a>
<div id="output"></div>
</body>
</html>
-->

<html>
<head>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.9.1.js">
</script>
<script>
$(document).ready(function(){
    $("#output").html('hahaha');
});
function ok(text){
    $("#output").html(text);    
}
</script>
</head>
<body>
<a href="javascript: window.open('test.php', '_blank');">新窗口</a>
<div id="output"></div>
</body>
</html>
