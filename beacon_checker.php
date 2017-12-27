<?php
require './config/config.php';
$db_manager = new DBMaria();
$db_manager->checkDBTableSet();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Hyundai Elevator Beacon Checker</title>

    <!-- <script src='./jquery-ui-1.12.1/jquery-ui.min.js'></script> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <!-- Bootstrap core CSS -->
    <link href="./startbootstrap-simple-sidebar-gh-pages/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./startbootstrap-simple-sidebar-gh-pages/css/simple-sidebar.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript -->
    <script src="./startbootstrap-simple-sidebar-gh-pages/vendor/jquery/jquery.min.js"></script>
    <script src="./startbootstrap-simple-sidebar-gh-pages/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="?">
                        Dashboard
                    </a>
                <!-- Floor List -->
                </li>
                <?php

                  for($i = 0; $i < 11; $i++) {
                    echo "<li><a href=\"?floor_num=".($i + 1)."\">".($i + 1)."</a></li>";
                  }
                ?>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1><a href="?" style='color:black'>Hyundai Elevator Beacon Checker</a></h1>
                <table>
                  <tr>
                    <?php
                      $beacon_no = $_GET['beacon_no'];
                      $floor_num = $_GET['floor_num'];
                      # print table header
                      $pivot = $_GET['pivot'];
                      $order = $_GET['order'];
                      printHeader($pivot, $order, $beacon_no, $floor_num);
                    ?>
                  </tr>
                <?php
                  if(!$pivot) {
                      $pivot = 'beacon_no';
                  }
                  if(!$order) {
                      $order = 'ASC';
                  }

                  $detectBeaconData = $db_manager->getBeaconDetect($pivot, $order);

                  if(!$beacon_no && !$floor_num) {
                      for($i = 0; $i < count($detectBeaconData); $i++) {
                        $current_beacon_num = $detectBeaconData[$i]['beacon_no'];
                        $beacon_information = $db_manager->getBeaconInfo($current_beacon_num, 'floor_num');
                        echo "<tr><td id='content-table'><a href=\"?beacon_no={$detectBeaconData[$i]['beacon_no']}\">".htmlspecialchars($detectBeaconData[$i]['beacon_no'])."</a></td><td id='content-table'><a href=\"?floor_num={$beacon_information[0]['floor_num']}\">".htmlspecialchars($beacon_information[0]['floor_num'])."</td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['detect_cnt'])."</td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['lastdetect'])."</td></tr>";
                      }
                  } else if($beacon_no) {
                    $detectBeaconData = $db_manager->getBeaconDetect("beacon_no", "ASC", $beacon_no);
                      for($i = 0; $i < count($detectBeaconData); $i++) {
                        echo "<tr><td id='content-table'><a href=\"?beacon_no={$detectBeaconData[$i]['beacon_no']}\">".htmlspecialchars($detectBeaconData[$i]['beacon_no'])."</a></td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['detect_cnt'])."</td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['lastdetect'])."</td></tr>";
                      }
                      $beacon_position = $db_manager->getBeaconInfo($beacon_no);
                      if(count($beacon_position) == 0) {
                        echo "No image is found.";
                      } else {
                        // echo "<tr><td colspan=3><canvas id=map-canvas style='width:100%;'></canvas></td></tr>";
                        echo "<tr><td colspan=3><img id=mapimage style='width:100%;max-width:500px;max-height:500px' src='./res/maps/temp/".$beacon_position[0]['file_name']."'></td></tr>";
                        $beacon_pos_x = $beacon_position[0]['pos_x'];
                        $beacon_pos_y = $beacon_position[0]['pos_y'];
                        echo "<div id='location_area' style='position:absolute; z-index:2; left:0px; top:0px; visibility:hidden'><font id='circle' color='#ff0000' size='15'>●</font></div>";
                      }
                  } else if($floor_num){
                    $floorBeaconData = $db_manager->getBeaconInfoByFloor($floor_num);

                    for($j = 0; $j < count($floorBeaconData); $j++) {
                      $detectBeaconData = $db_manager->getBeaconDetect("beacon_no", "ASC", $floorBeaconData[$j]['beacon_no']);
                      for($i = 0; $i < count($detectBeaconData); $i++) {
                        echo "<tr><td id='content-table'><a href=\"?beacon_no={$detectBeaconData[$i]['beacon_no']}\">".htmlspecialchars($detectBeaconData[$i]['beacon_no'])."</a></td><td id='content-table'>".htmlspecialchars($floorBeaconData[$j]['floor_num'])."</td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['detect_cnt'])."</td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['lastdetect'])."</td></tr>";
                      }
                    }
                  }
                ?>

                </table>
                <p>
                <p></p>
                <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle">Show Floor</a>
                <p>
                  <span></span>
                  <span>&nbsp;</span>
                </p>

            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->



    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");

    });


    $(document).ready(function() {
          markPosition();
          $(window).resize(function(){
              markPosition();
          });
          $('#mapimage').resize(function() {
              markPosition();
          });
    });

    function markPosition() {
      var is_map_page = false;
      var beacon_pos_x = 1;
      var beacon_pos_y = 1;
      var map_image_top = 0;
      var map_image_left = 0;
      var map_image_width = 1;
      var map_image_height = 1;
      var menu_toggle_width = 0;

      var container_fluid_left = $('.container-fluid').offset().left;
      var container_fluid_padding_left_str = $('.container-fluid').css('padding-left');
      var container_fluid_padding_left = parseFloat(container_fluid_padding_left_str.substring(0,2));
      var sidebar_wrapper_width = $('#sidebar-wrapper').width();

      <?php
        if($beacon_pos_x && $beacon_pos_y) {
          echo "beacon_pos_x = {$beacon_pos_x};";
          echo "beacon_pos_y = {$beacon_pos_y};";
          echo "map_image_top = $('#mapimage').offset().top;";
          echo "map_image_left = $('#mapimage').offset().left;";
          echo "map_image_width = $('#mapimage').width();";
          echo "map_image_height = $('#mapimage').height();";
          echo "if($('#wrapper').hasClass('toggled')) {";
          echo "menu_toggle_width = $('#sidebar-wrapper').width() * -1;";
          echo "}";
        }
      ?>

      var pos_x = menu_toggle_width + map_image_left + (beacon_pos_x * map_image_width / 500);
      var pos_y = map_image_top + (beacon_pos_y * map_image_height / 500);
      $('#location_area').css({left:pos_x,top:pos_y,visibility:'initial'});
      $('#circle').css({'font-size':(map_image_width/20)});

      /*
      var canvas = document.getElementById("map-canvas");
      $("#map-canvas").attr("width", "640px");
      $("#map-canvas").attr("height", "640px");

      var context = canvas.getContext("2d");
      var x = 0;
      var y = 0;
      var width = 640;
      var height = 640;
      var ImageObj = new Image();

      ImageObj.onload = function() {
        context.drawImage(ImageObj, x, y, width, height);
      };
      ImageObj.src = "./res/maps/temp/01F.png";
      */

      $( "#mapimage" ).mousemove(function( event ) {
        var pageCoords = "( " + event.pageX + ", " + event.pageY + " )";
        var clientCoords = "( " + (parseFloat(event.clientX) - container_fluid_left - container_fluid_padding_left - sidebar_wrapper_width) + ", " + (parseFloat(event.clientY) - map_image_top) + " )";
        $( "span:first" ).text( "( event.pageX, event.pageY ) : " + pageCoords );
        $( "span:last" ).text( "( event.imageX, event.imageY ) : " + clientCoords );
      });
    }




    </script>
</body>
</html>

<?php
function printHeader($pivot="beacon_no", $order="ASC", $beacon_no=NULL, $floor_num=NULL) {
  if(!$beacon_no && !$floor_num) {
    $order_reverse;

    if(!$pivot && !$order) {
      $pivot = 'beacon_no';
      $order = 'ASC';
      $order_reverse = 'DESC';
    }

    if($order == 'DESC') {
      $order_mark = '▼';
      $order_reverse = 'ASC';
    } else {
      $order_mark = '▲';
      $order_reverse = 'DESC';
    }

    if($pivot == 'beacon_no') {
      echo "<td style='padding-right:30px'><strong><a href=\"?pivot=beacon_no&order={$order_reverse}\">Beacon Number ".$order_mark."</a></strong></td>";
      echo "<td style='padding-right:30px'><strong>Floor</strong></td>";
      echo "<td style='padding-right:30px'><strong><a href=\"?pivot=detect_cnt&order=ASC\">Detect Count</a></strong></td>";
      echo "<td style='padding-right:30px'><strong><a href=\"?pivot=lastdetect&order=ASC\">Last Detect Time</a></strong></td>";
    } else if($pivot == 'detect_cnt') {
      echo "<td style='padding-right:30px'><strong><a href=\"?pivot=beacon_no&order=ASC\">Beacon Number</a></strong></td>";
      echo "<td style='padding-right:30px'><strong>Floor</strong></td>";
      echo "<td style='padding-right:30px'><strong><a href=\"?pivot=detect_cnt&order={$order_reverse}\">Detect Count ".$order_mark."</a></strong></td>";
      echo "<td style='padding-right:30px'><strong><a href=\"?pivot=lastdetect&order=ASC\">Last Detect Time</a></strong></td>";
    } else {
      echo "<td style='padding-right:30px'><strong><a href=\"?pivot=beacon_no&order=ASC\">Beacon Number</a></strong></td>";
      echo "<td style='padding-right:30px'><strong>Floor</strong></td>";
      echo "<td style='padding-right:30px'><strong><a href=\"?pivot=detect_cnt&order=ASC\">Detect Count</a></strong></td>";
      echo "<td style='padding-right:30px'><strong><a href=\"?pivot=lastdetect&order={$order_reverse}\">Last Detect Time ".$order_mark."</a></strong></td>";
    }

  } else {
    echo "<td style='padding-right:30px'><strong>Beacon Number</strong></td>";
    echo "<td style='padding-right:30px'><strong>Floor</strong></td>";
    echo "<td style='padding-right:30px'><strong>Detect Count</strong></td>";
    echo "<td style='padding-right:30px'><strong>Last Detect Time</strong></td>";
  }
}
?>
