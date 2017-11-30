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
                </li>
                <?php
                  $detectBeaconData = $db_manager->getBeaconDetect();
                  for($i = 0; $i < count($detectBeaconData); $i++) {
                    echo "<li><a href=\"?beacon_no={$detectBeaconData[$i]['beacon_no']}\">".htmlspecialchars($detectBeaconData[$i]['beacon_no'])."</a></li>";
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
                      if(!$beacon_no) {
                        $pivot = $_GET['pivot'];
                        $order = $_GET['order'];
                        $order_reverse;

                        if(!$pivot && !$order) {
                          $pivot = 'beacon_no';
                          $order = 'ASC';
                          $order_reverse = 'DESC';
                          error_log("pivot order test");
                        }

                        $detectBeaconData = $db_manager->getBeaconDetect($pivot, $order);


                        if($order == 'DESC') {
                          $order_mark = '▼';
                          $order_reverse = 'ASC';
                        } else {
                          $order_mark = '▲';
                          $order_reverse = 'DESC';
                        }



                        if($pivot == 'beacon_no') {
                          echo "<td style='padding-right:30px'><strong><a href=\"?pivot=beacon_no&order={$order_reverse}\">Beacon Number ".$order_mark."</a></strong></td>";
                          echo "<td style='padding-right:30px'><strong><a href=\"?pivot=detect_cnt&order=ASC\">Detect Count</a></strong></td>";
                          echo "<td style='padding-right:30px'><strong><a href=\"?pivot=lastdetect&order=ASC\">Last Detect Time</a></strong></td>";
                        } else if($pivot == 'detect_cnt') {
                          echo "<td style='padding-right:30px'><strong><a href=\"?pivot=beacon_no&order=ASC\">Beacon Number</a></strong></td>";
                          echo "<td style='padding-right:30px'><strong><a href=\"?pivot=detect_cnt&order={$order_reverse}\">Detect Count ".$order_mark."</a></strong></td>";
                          echo "<td style='padding-right:30px'><strong><a href=\"?pivot=lastdetect&order=ASC\">Last Detect Time</a></strong></td>";
                        } else {
                          echo "<td style='padding-right:30px'><strong><a href=\"?pivot=beacon_no&order=ASC\">Beacon Number</a></strong></td>";
                          echo "<td style='padding-right:30px'><strong><a href=\"?pivot=detect_cnt&order=ASC\">Detect Count</a></strong></td>";
                          echo "<td style='padding-right:30px'><strong><a href=\"?pivot=lastdetect&order={$order_reverse}\">Last Detect Time ".$order_mark."</a></strong></td>";
                        }

                      } else {
                        echo "<td style='padding-right:30px'><strong>Beacon Number</strong></td>";
                        echo "<td style='padding-right:30px'><strong>Detect Count</strong></td>";
                        echo "<td style='padding-right:30px'><strong>Last Detect Time</strong></td>";
                      }
                    ?>
                  </tr>
                <?php

                  if(!$beacon_no) {
                      for($i = 0; $i < count($detectBeaconData); $i++) {
                        echo "<tr><td id='content-table'><a href=\"?beacon_no={$detectBeaconData[$i]['beacon_no']}\">".htmlspecialchars($detectBeaconData[$i]['beacon_no'])."</a></td id='content-table'><td>".htmlspecialchars($detectBeaconData[$i]['detect_cnt'])."</td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['lastdetect'])."</td></tr>";
                      }
                  } else {
                    $detectBeaconData = $db_manager->getBeaconDetect("beacon_no", "ASC", $beacon_no);
                      for($i = 0; $i < count($detectBeaconData); $i++) {
                        echo "<tr><td id='content-table'><a href=\"?beacon_no={$detectBeaconData[$i]['beacon_no']}\">".htmlspecialchars($detectBeaconData[$i]['beacon_no'])."</a></td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['detect_cnt'])."</td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['lastdetect'])."</td></tr>";
                      }
                      $beacon_position = $db_manager->getBeaconInfo($beacon_no);
                      if(count($beacon_position) == 0) {
                        echo "No image is found.";
                      } else {
                        echo "<tr><td colspan=3><img id=mapimage style='width:100%;max-width:640px;max-height:640px' src='./res/maps/".$beacon_position[0]['file_name']."'></td></tr>";
                        $beacon_pos_x = $beacon_position[0]['pos_x'];
                        $beacon_pos_y = $beacon_position[0]['pos_y'];
                        echo "<div id='location_area' style='position:absolute; z-index:2; left:0px; top:0px; visibility:hidden'><font id='circle' color='#ff0000' size='15'>●</font></div>";
                      }
                  }


                ?>

                </table>

                <p></p>
                <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle">Beacon Menu</a>
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

      var pos_x = menu_toggle_width + map_image_left + (beacon_pos_x * map_image_width / 1200);
      var pos_y = map_image_top + (beacon_pos_y * map_image_height / 1360);
      $('#location_area').css({left:pos_x,top:pos_y,visibility:'initial'});
      $('#circle').css({'font-size':(map_image_width/20)});
    }
    </script>
</body>
</html>
