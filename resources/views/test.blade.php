<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" type="text/css" 
 href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-lightness/jquery-ui.css" />
<link href="css/evol.colorpicker.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" 

 type="text/javascript" charset="utf-8"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js" 

 type="text/javascript" charset="utf-8"></script>
<script src="js/evol.colorpicker.min.js" type="text/javascript" charset="utf-8"></script>

    <title>Document</title>
</head>
<body>
<script type="text/javascript">
   $(document).ready(function() {
       $("#mycolor").colorpicker();
   });
</script>
 
<input style="width:100px;" id="mycolor" />
<div style="width:128px;">
   <input style="width:100px;" id="mycolor" class="colorPicker evo-cp0" />
   <div class="evo-colorind" style="background-color:#8db3e2"></div>
</div>
</body>
</html>