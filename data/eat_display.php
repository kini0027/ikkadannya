<!DOCTYPE html>
<head>
<style>
@media screen and (min-width: 768px) and (min-height:768px){
        .chart_container {
        width: 40%;
        height:640px;
        margin: 5%;
        float : left;
  }
}
</style>
</head>
<?php
$mysqli = new mysqli ("localhost", "nakamura-lab", "n1k2m3r4fms");
$mysqli->select_db("ryota_db");
$week = array();
$eat = array(0,0,0,0,0,0,0);
for ($i = -6; $i <= 0; $i++){
    $week[] = date("Y-m-d", strtotime($i."day"));
}
for($i = 0; $i<7; $i++){
    $results=$mysqli->query("select gram from wp where date='".$week[$i]."'");
    $data_list = $results->fetch_all(MYSQLI_BOTH);

    for($j = 1; $j<count($data_list); $j++){
        if($data_list[$j][0] < $data_list[$j-1][0]){
            $eat[$i] = $eat[$i] + $data_list[$j-1][0] - $data_list[$j][0];
        }
    }
}
$mysqli->close();
?>
<div class="chart_container"><canvas id="myBarChart"></canvas></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
        <script>
        var date = <?php  echo json_encode($week); ?>;
        var eat = <?php  echo json_encode($eat); ?>;
          var ctx = document.getElementById("myBarChart");
          var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: date,
              datasets: [
                {
                  label: "猫が食べたえさの量",
                  data: eat,
                  backgroundColor: "rgba(255,153,0,0.4)"
                }
              ]
            },
            options: {
              title: {
                display: false,
              },
              scales: {
                yAxes: [{
                  ticks: {
                    suggestedMax: 150,
                    suggestedMin: 0,
                    stepSize: 20,
                    callback: function(value){
                     return value+'g';
                    }
                  }
                }]
              },
            }
          });
        </script>
</html>