<?php
require_once("LEDSummoner.class.php");
require_once("ChassisSummoner.class.php");
const WIDTH = 192;
const HEIGHT = 144;
//$bitmap = initBitmap();
if (isset($_POST["bitmap"])) {
  $bitmap = json_decode(["bitmap"]);
}else{
  echo "error!";
}
$path = $_POST["path"];
$ls = new LEDSummoner(WIDTH, HEIGHT, $bitmap);
$cs = new ChassisSummoner();
$cs->go($path);
$ls->bitmapMode($path);
// function initBitmap(){
//   $bitmap = array();
//   for ($i=0; $i < HEIGHT; $i++) { 
//     $bitmap[$i] = array();
//     for ($j=0; $j < WIDTH; $j++) { 
//       $bitmap[$i][$j] = 0;
//     }
//   }
//   return $bitmap;
// }
?>

<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GRANZORT</title>
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="editor.css">
  </head>
  <body>
  <div class="container">
    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-10 text-center">
        <div class="canvasSection">
            <canvas id="backgroundGrid" width="960" height="720"></canvas>     
            <canvas id="editor" width="960" height="720" onmousemove="cnvs_drawing(event)" onmousedown="cnvs_drawStart(event)" onmouseup="cnvs_drawEnd(event)"></canvas>
        </div>
      </div>
    </div>
  </div>

    <div class="navbar navbar-default navbar-fixed-top">
      <ul class="nav nav-pills nav-justified" role="tablist">
        <li role="presentation">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            Thickness <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
           <li><a href="#" id="btn-thick">thick</a></li>
           <li><a href="#" id="btn-thin">thin</a></li>
           <li><a href="#" id="btn-dotted">dotdot</a></li>
          </ul>
        </li>
        <li role="presentation">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            Color <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
           <li><a href="#" id="btn-white">white</a></li>
           <li><a href="#" id="btn-red">red</a></li>
           <li><a href="#" id="btn-orange">orange</a></li>
          </ul>
        </li>
        <li role="presentation"><a href="#" id="btn-clear">CLEAR</a></li>
        <li class="active" role="presentation"><a href="#" id="btn-go">GO</a></li>
      </ul>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      var WIDTH = 192, HEIGHT = 144, PIXEL = 5;
      var c=document.getElementById("editor"), cBG=document.getElementById("backgroundGrid");
      var cxt=c.getContext("2d"), cxtBG=cBG.getContext("2d");
      var path = 1;
      var byteMap = new Array(WIDTH);
      var startDrawing = false;
      var WHITE = 1, RED = 2, ORANGE = 3, YELLOW = 4, GRENN = 5, CYAN = 6, BLUE = 7, PURPLE = 8;
      var color = WHITE, thickness = 1;
      cxtBG.translate(0.5,0.5); 
      cxtBG.lineWidth = 1;
      initEditor();
      initByteMap();

      jQuery(document).ready(function($) {
        $('#btn-clear').click(function(event) {
          initByteMap();
          cxt.clearRect(0, 0, WIDTH*PIXEL, HEIGHT*PIXEL);
          sleep(100);   
        });
        $('#btn-red').click(function(event) {
          color = WHITE;
          cxt.fillStyle = "white";
        });
        $('#btn-red').click(function(event) {
          color = RED;
          cxt.fillStyle = "red";
        });
        $('#btn-orange').click(function(event) {
          color = ORANGE;
          cxt.fillStyle = "orange";
        });
        $('#btn-thick').click(function(event) {
          thickness = 2;
        });
        $('#btn-thin').click(function(event) {
          thickness = 1;
        });
        $('#btn-go').click(function(event) {
          $.post(
            'editor.php', 
            {
              bitmap: JSON.stringify(byteMap),
              path:path
            }, 
            function(data, textStatus, xhr) {
            /*optional stuff to do after success    loading....*/
          });
        });
      });

      function initEditor () {
        cxtBG.fillStyle = 'black';
        cxtBG.fillRect(0, 0, WIDTH*PIXEL, HEIGHT*PIXEL);
        cxtBG.strokeStyle = 'grey';
        cxtBG.globalAlpha = 0.01;
        drawGrid();
        cxt.fillStyle = 'white';
      }

      function drawGrid(){
        for (var i = HEIGHT - 1; i > 0; i--) {
          cxtBG.moveTo(0, i*PIXEL);
          cxtBG.lineTo(WIDTH*PIXEL, i*PIXEL);
          cxtBG.stroke();
        };
        for (var i = WIDTH - 1; i > 0; i--) {
          cxtBG.moveTo(i*PIXEL, 0);
          cxtBG.lineTo(i*PIXEL, HEIGHT*PIXEL);
          cxtBG.stroke();
        };
      }

      function initByteMap(){
        for (var i = byteMap.length - 1; i >= 0; i--) {
          byteMap[i] = new Array(HEIGHT);
          for (var j = byteMap[i].length - 1; j >= 0; j--) {
            byteMap[i][j] = 0;
          };
        };
      }
      function cnvs_drawing(e)
      {
        var offset = $('#editor').offset();
        var mapX, mapY;
        cxt.globalAlpha = 1;
        x=e.clientX - offset.left;
        y=e.clientY - offset.top;
        if(startDrawing){
          //matrix in Arduibo is transposed
          mapY = parseInt(x/PIXEL);
          mapX = parseInt(y/PIXEL);
          byteMap[mapX][mapY] = color;
          if (thickness == 2) {
            cxt.fillRect(x-x%PIXEL, y-y%PIXEL, PIXEL*2, PIXEL*2);
            if (mapX < byteMap[mapX].length - 1) {
              byteMap[mapX+1][mapY] = color;
            };
            if (mapY < byteMap.length - 1) {
              byteMap[mapX][mapY+1] = color;
            };
            if ((mapY < byteMap.length - 1)&&(mapX < byteMap[mapX].length - 1)) {
              byteMap[mapX+1][mapY+1] = color;
            };
          }else{
            cxt.fillRect(x-x%PIXEL, y-y%PIXEL, PIXEL, PIXEL);
          };

         console.log(x+','+y);
        }
      }
      function cnvs_drawStart(e)
      {
        startDrawing = true;
        var offset = $('#editor').offset();
        x=e.clientX - offset.left;
        y=e.clientY - offset.top;
        console.log('mousedown'+x+','+y);
      }
      function cnvs_drawEnd(e){
        startDrawing = false;
      }

      function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
      }
    </script>
  </body>
</html>