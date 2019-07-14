<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="refresh" content="30">
  <meta name="viewport" content="width=device-width,initial-scale=0.7,maximum-scale=0.7,user-scalable=no">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:600,700|Roboto:500" rel="stylesheet">
  <style>
  * {
    box-sizing: border-box;
  }
  html, body{
    margin: 0;
    font-family: 'Open Sans', sans-serif;
    overflow:hidden;
    background-color: #FFF;
    height: 100%;
  }
  .container{
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .container p,table {
    margin-left: 12px;
    margin-right: 12px;
  }
  #top{
    padding: 6px 12px;
    justify-content: space-between;
    background-color: #000;
    color: #FFF;
  }
  #top p {
    margin: 0;
    font-size: 250%;
    background-color:transparent;
  }
  .stopName{
    font-weight: 600;
    font-size: 260%;
    margin: 0;
  }
  .stop{
    table-layout: fixed;
    border-collapse: collapse;
  }
  .busNo{
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    font-size: 58px;
    color: #FFF;
    border-bottom-style: solid;
    text-align: center;
    background-color: #b30000;
  }
  .tableCell{
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    font-size: 58px;
    border-right-style: solid;
    border-left-style: solid;
    text-align: center;
    border-bottom-style: solid;
    border-color: #FFF;
  }
  .colorTable{
    position: absolute;
    top: 10%;
    right: 12px;
  }
  .colorTable td{
    padding:8px;
    border-width: 0px;
    font-size: 180%;
    font-weight: 600;
    border: none;
  }
  table{
    flex: auto;
  }
  td{
    font-size: 320%;
    overflow: hidden;
  }
  .m{
    font-size:60%;
  }
</style>
</head>
<body>
<?php
  require 'config.php';

  $busstops = array("16991", "17191", "17129", "17121");
  $busstopsname = array("Front Gate", "Back Gate", "Aft NUSH", "Blk 610");
  $ch = curl_init();
  curl_setopt_array($ch, array(
    CURLOPT_HTTPHEADER => array('AccountKey:'. ACCOUNT_KEY),
    CURLOPT_RETURNTRANSFER => true,
  ));

  for ($i = 0; $i < count($busstops); $i++) {
    curl_setopt($ch, CURLOPT_URL, "http://datamall2.mytransport.sg/ltaodataservice/BusArrivalv2?BusStopCode=" . $busstops[$i] . "&SST=True");
    $current_time = time();
    $expire_time = 60;
    $file = $i."cache";
    if(file_exists($file)){
        $file_time = filemtime($file);
    }
    if (file_exists($file) && ($current_time - $expire_time < $file_time)) {
      $json = file_get_contents($file);
    } else {
      $json = curl_exec($ch);
      file_put_contents($file, $json);
    }
    // var_dump($json);
    $out[$i] = json_decode($json, true);
  }
  curl_close($ch);

  function getMins($s) {
    $n = strtotime($s);
    date_default_timezone_set("Asia/Singapore");
    $now = date("U");
    if (empty($s)) {
      return "--";
    }
    if (($n - $now) > 60) {
      return (string) (floor(($n - $now) / 60));
    }
    if (($n - $now) < 60) {
      return "Arr";
    } else {
      return "--";
    }
  }

  function getColor($s) {
    if (empty($s)) {
      return "LightCyan";
    }
    if ($s == "SEA") {
      return "#99ff99";
    }
    if ($s == "SDA") {
      return "#ffff99";
    }
    if ($s == "LSD") {
      return "#ff9999";
    }
  }

  function disRow($data, $i, $n) {
    echo "<td class='busNo'>" . $data[$i]['ServiceNo'] . "</td>
    <td class='tableCell' style=' background-color: " . getColor($data[$i]['NextBus']['Load']) . "'>
    " . getMins($data[$i]['NextBus']['EstimatedArrival']);
    if (getMins($data[$i]['NextBus']['EstimatedArrival']) != 'Arr') {
      echo "<span class='m'>m</span>
      </td>";
    } else {
      echo "</td>";
    }
    echo "<td class='tableCell' style=' background-color: " . getColor($data[$i]['NextBus2']['Load']) . "'>
    " . getMins($data[$i]['NextBus2']['EstimatedArrival']);
    if (getMins($data[$i]['NextBus2']['EstimatedArrival']) != 'Arr') {
      echo "<span class='m'>m</span>
      </td>";
    } else {
      echo "</td>";
    }
  }

  function cleanUp($data) {
    $newData = array();
    for ($i = 0; $i < count($data); $i++) {
      if ($data[$i]["ServiceNo"] != "963R" && $data[$i]["ServiceNo"] != "97e") {
        array_push($newData, $data[$i]);
      }
    }
    return $newData;
  }

  function display($data, $n) {
    global $busstopsname;
    if ($n <= 1) {
      echo "
      <p class='stopName'>
      " . $busstopsname[$n] . "</p>
      <table class='stop' style='width: calc(50% - 12px);''>
      ";
      for ($i = 0; $i < count($data); $i++) {
        echo "<tr>";
        disRow($data, $i, $n);
        echo "<td style='width: 2%;'></td>";
        echo "</tr>";
      }
      echo"
      </table>
      ";
    } else {
      echo"
      <p class='stopName' style='width: calc(100% - 24px);'>
      " . $busstopsname[$n] . "</p>
      <table class='stop' style='width: calc(100% - 24px);'>
      ";
      for ($i = 0; $i < (count($data) / 2); $i++) {
        echo "<tr>";
        disRow($data, 2 * $i, $n);
        echo "<td style='width: 2%;'></td>";
        disRow($data, (2 * $i) + 1, $n);
        echo"</tr>";
      }
      echo"
      </table>
      ";
    }
  }
?>
<table class="colorTable">
  <tr>
    <td style="background-color: #99ff99;">Seats Available</td>
  </tr>
  <tr>
    <td style="background-color: #ffff99;">Standing Available</td>
  </tr>
  <tr>
    <td style="background-color: #ff9999;">Standing Limited</td>
  </tr>
</table>
<div class="container">
  <div id="top">
    <p style="display: inline-block;font-weight:700;">NUSH Bus Timings</p>
    <p style="display: inline-block;font-weight:600;float:right;">
      <?php
      date_default_timezone_set("Asia/Singapore");
      echo date("h:ia");
      ?>
    </p>
  </div>
  <?php
    for ($i = 0; $i < count($busstops); $i++) {
      display(cleanUp($out[$i]['Services']), $i);
    }
  ?>
</div>
</body>
</html>
