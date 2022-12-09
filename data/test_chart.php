<?php
session_start();
// if(!$_SESSION["group_id"]){
//     header('Location: login_form.php');
//     exit;
//   }
$_SESSION["group_id"] = 1;

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>グラフ</title>
    <style>
        @media screen and (min-width: 500px) and (min-height:500px){
        .chart_container {
        width: 40%;
        height: 640px;
        margin: 5%;
        float : left;
        }
    }
</style>
</head>

<body>
    <div class="chart_container">
        <canvas id="myLineChart"  width="300" height="300"></canvas>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script>
        $(function() {
            //console.log( <?php $_SESSION["group_id"] ?>);
            var group_id = <?php echo $_SESSION["group_id"]; ?>;
            //console.log(group_id);
            $.getJSON("http://komatsubara.nkmr.io/web_p/android_processing.php?operate=view&group_id=" + group_id, function(data) {
                var len = data.length;
                var ctx = document.getElementById("myLineChart");
                var date = [];
                var sum = [];
                for (let i = 0; i < len; i++) {
                    date.push(data[i].date.replace(',', '/').replace(',', '/'));
                    sum.push(data[i].sum);
                }
                console.log(date);
                console.log(sum);
                var myLineChart = new Chart(ctx, {
                    //type: 'line',
                    type: 'bar',
                    data: {
                        //ここ
                        labels: date,
                        datasets: [{
                                label: '猫と遊んだ量',
                                //ここ
                                data: sum,
                                backgroundColor: "rgba(183, 255, 168, 1)"
                            }
                            // , {
                            //     label: '2',
                            //     data: [33, 45, 62, 55, 31, 45, 38],
                            //     backgroundColor: "rgba(255,183,76,0.5)"
                            // }
                        ],
                    },
                    options: {
                        title: {
                            display: true,
                            text: '中村家'
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    suggestedMax: 100,
                                    suggestedMin: 0,
                                    stepSize: 10,
                                    callback: function(value, index, values) {
                                        return value
                                    }
                                }
                            }]
                        },
                    }
                });
            });
        });
        

    </script>
    <p id="demo"></p>
</body>

</html>