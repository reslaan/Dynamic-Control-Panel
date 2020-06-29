<?php
include('db/connect.php');

?>

<?php

function save()
{
    include('db/connect.php');
    $forword = $_POST['forword'];
    $left = $_POST['left'];
    $right = $_POST['right'];
    $backword = $_POST['backword'];
    if ((($forword > 0) or ($left > 0) or ($right > 0))) {




        $sql = "INSERT INTO dynamic_control ( forword , L , R , backword ) VALUES ($forword , $left ,$right , $backword)";
        $result = mysqli_query($connect, $sql);

        if ($result) {
            echo 'data is intered';
        } else {
            echo 'not successful';
        }
    } else {
        echo 'All values are zeros';
    }
}

function rows()
{
    include('db/connect.php');
    $sql = "SELECT  * FROM dynamic_control  ";
    $result = mysqli_query($connect, $sql);
    mysqli_close($connect);
    return mysqli_num_rows($result);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style1.css">
    <title>Document</title>
</head>

<body>



    <header class="header">
        <div class="div-header">
            <img src="img/logo.jpg" alt="logo">
        </div>
    </header>
    <h1 class="title">Initial Control panel</h1>
    <section class="maincontent">
        <div class="div-button box">
            <h2 style="color: brown; margin-top:10px">
                '
                <?php
                if (isset($_POST['save'])) {
                    save();
                }
                ?> '</h2>
            <form class="data_form" style="height: 100px;" method="POST">

                <div class="input_div">

                    <label for="forword">Forword&nbsp;</label>&nbsp;&nbsp;&nbsp;
                    <input type="number" name="forword" min="0" max="200" step="10" value="0">
                    <br>
                    <br>
                    <label for="left">Left&nbsp; &ensp; </label>&nbsp;&nbsp;&nbsp;
                    <input type="number" name="left" min="0" max="170" step="10" value="0">
                    <br>
                    <br>
                    <label for="right">Right&nbsp; &ensp;</label>&nbsp;&nbsp;&nbsp;
                    <input type="number" name="right" min="0" max="170" step="10" value="0">
                    <br>
                    <br>
                    <label for="backword">Backword</label>&nbsp;&nbsp;&nbsp;
                    <input type="number" name="backword" min="0" max="200" step="10" value="0">
                    <br>
                </div>
                <br>
                <input class="clean " type="submit" name="save" value="save">&nbsp;&ensp;
                <!-- &emsp; = 4 space ,  -->
                <input class="clean " type="submit" name="start" value="start">&nbsp;&nbsp;
                <!-- &ensp; = 2 space ,  -->
                <input class="clean " type="submit" name="clean" value="clean"> &nbsp;
                <!-- &nbsp; = 1 space ,  -->

            </form>

        </div>

        <div class="div-moves box">
            <ul>

                <?php
                include('db/connect.php');
                $sql = "SELECT  forword , L , R , backword FROM dynamic_control ORDER BY id DESC LIMIT 1";
                $result = mysqli_query($connect, $sql);
                mysqli_close($connect);
                if (isset($_POST['start'])) {
                    if (mysqli_num_rows($result) > 0) {
                        $states = mysqli_fetch_assoc($result);
                        if ($states['forword'] == 0)
                            $states['backword'] = 0;
                        echo '<li>' . 'Right : ' . $states['R'] . '</li>';
                        echo '<li>' . 'Forword : ' . $states['forword'] . '</li>';
                        echo '<li>' . 'Left :' . $states['L'] . '</li>';
                        echo '<li>' . 'Backword : ' . $states['backword'] . '</li>';
                    }
                }
                if (isset($_POST['clean'])) {
                    include('db/connect.php');
                    $sql = "DELETE FROM dynamic_control ";
                    $result = mysqli_query($connect, $sql);
                }

                ?>

            </ul>

        </div>

        <div class="map box">

            <?php
            $check = false;
            if (isset($_POST['start'])) {

                include('db/connect.php');
                if (mysqli_num_rows($result) > 0) {
                    $sql = "SELECT  forword , L , R , backword FROM dynamic_control ORDER BY id DESC LIMIT 1";
                    $result = mysqli_query($connect, $sql);
                    mysqli_close($connect);
                    $check = true;

                    $states = mysqli_fetch_assoc($result);
                    $forword =  $states['forword'];
                    $left =  $states['L'];
                    $right =  $states['R'];
                    $backword =  $states['backword'];
                } else {
                    echo 'data base is empty';
                }
            } ?>
            <canvas id="myCanvas" width="350" height="330">
                Your browser does not support the HTML canvas tag.</canvas>

            <script>
                var x = 175;
                var y = 320;
                if (<?php echo $check ?>) {
                    var f = y - <?php echo $forword ?>;
                    var l = <?php echo $left ?>;
                    var r = x + <?php echo $right ?>;
                    var b = <?php echo $backword ?>;
                    var c = document.getElementById("myCanvas");
                    var ctx = c.getContext("2d");

                    ctx.lineWidth = 5;
                    ctx.strokeStyle = 'red';
                    ctx.moveTo(x, y);
                    ctx.lineTo(r, y);
                    ctx.stroke();
                    ctx.font = "20px Arial ";
                    ctx.fillText("◉", x - 8, y + 6);
                    ctx.fillStyle = "red";

                    if (r != x)
                        ctx.fillText("▶", r - 5, y + 7);
                    ctx.beginPath();



                    var ctf = c.getContext("2d");
                    ctf.lineWidth = 5;
                    ctf.strokeStyle = 'blue';
                    ctf.moveTo(r, y);
                    ctf.lineTo(r, f);
                    ctf.stroke();
                    ctf.font = "20px Arial";
                    ctf.fillStyle = "blue";
                    if (f < y)
                        ctf.fillText("▲", r - 10, f + 5);
                    ctf.beginPath();

                    var ctl = c.getContext("2d");
                    ctl.lineWidth = 5;
                    ctl.moveTo(r, f);
                    ctl.lineTo(r - l, f);
                    ctl.strokeStyle = 'green';
                    ctl.stroke();
                    ctl.font = "20px Arial";
                    ctl.fillStyle = "green";
                    if (l > 0)
                        ctf.fillText("◀", r - l - 10, f + 7);
                    ctl.beginPath();

                    if (f < y) {
                        var ctb = c.getContext("2d");
                        ctb.lineWidth = 5;
                        ctb.moveTo(r - l, f);
                        ctb.lineTo(r - l, f + b);
                        ctb.strokeStyle = 'yellow';
                        ctb.stroke();
                        ctb.font = "20px Arial";
                        ctb.fillStyle = "yellow";
                        if (b > 0)
                            ctf.fillText("▼", r - l - 10, f + b + 5);
                        ctb.beginPath();
                    }
                }
            </script>

        </div>
    </section>

    <footer class="footer"> writen by Reslaan Alobeidi</footer>


</body>

</html>