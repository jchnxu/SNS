<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.9.1.js">
</script>
<script>
$(document).ready(function(){
    $("#output").html('hahaha');
    window.opener.ok('weibo name');
    window.close();
});
</script>
</head>
<body>
<div id="output"></div>
</body>
</html>>
