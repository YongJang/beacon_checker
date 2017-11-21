<?php
require './config/config.php';
$db_manager = new DBMaria();
$db_manager->checkDBTableSet();
$detectBeaconData = $db_manager->getBeaconDetect("beacon_no", "ASC");
# echo "\n".$detectBeaconData[0]['beacon_no']."\n";
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
                <h1>Hyundai Elevator Beacon Checker</h1>
                <table>
                  <tr>
                    <td style='padding-right:30px'><strong>Beacon Number</strong></td>
                    <td style='padding-right:30px'><strong>Detect Count</strong></td>
                    <td style='padding-right:30px'><strong>Last Detect Time</strong></td>
                  </tr>
                <?php
                  $beacon_no = $_GET['beacon_no'];
                  if(!$beacon_no) {

                      for($i = 0; $i < count($detectBeaconData); $i++) {
                        echo "<tr><td id='content-table'><a href=\"?beacon_no={$detectBeaconData[$i]['beacon_no']}\">".htmlspecialchars($detectBeaconData[$i]['beacon_no'])."</a></td id='content-table'><td>".htmlspecialchars($detectBeaconData[$i]['detect_cnt'])."</td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['lastdetect'])."</td></tr>";
                      }
                  } else {
                    $detectBeaconData = $db_manager->getBeaconDetect("beacon_no", "ASC", $beacon_no);
                      for($i = 0; $i < count($detectBeaconData); $i++) {
                        echo "<tr><td id='content-table'><a href=\"?beacon_no={$detectBeaconData[$i]['beacon_no']}\">".htmlspecialchars($detectBeaconData[$i]['beacon_no'])."</a></td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['detect_cnt'])."</td><td id='content-table'>".htmlspecialchars($detectBeaconData[$i]['lastdetect'])."</td></tr>";
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

    <!-- Bootstrap core JavaScript -->
    <script src="./startbootstrap-simple-sidebar-gh-pages/vendor/jquery/jquery.min.js"></script>
    <script src="./startbootstrap-simple-sidebar-gh-pages/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

</body>

</html>
