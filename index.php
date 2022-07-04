<?php
session_start();

// LOGOUT REQUEST
if (isset($_POST["logout"])) unset($_SESSION["user"]);

// REDIRECT TO LOGIN PAGE IF NOT LOGGED IN
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <title>PHP Blog</title>
</head>

<body>
    <div class="container">
        <div class="content">
            <form id="logout" method="post">
                <input type="hidden" name="logout" value="1" />
                <input type="submit" value="Logout" class="logout" />
            </form>
          
            <?php
            // GET ALL FILENAMES
            $fileNames = array_map(
                function ($filePath) {
                    return basename($filePath);
                },
                glob('./files/*.{enc}', GLOB_BRACE)
            );

            $url = '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

            $var_arr = [];
            foreach ($fileNames as $value) {
                array_push($var_arr, substr($value, 0, -4));
            }

            // FROM: https://gist.github.com/EntitySK/3265ac57d80bf4a87fb11ad602398b28
            function buildHTMLTable($left, $center, $right, $arr)
            {
                $width = 300;
                $html = "<table border=1 width=" . $width . ">";
                $title =
                    "<table border=0 width=" . $width . ">" .
                    "<tr>" .
                    "<td>" .
                    $left .
                    "</td><td>" .
                    $center .
                    "</td><td>" .
                    $right .
                    "</td>" .
                    "</tr>" .
                    "</table>";
                $html = $html . "<caption>" . $title . "</caption>";
                foreach ($arr as $i) {
                    $html = $html . "<tr>";
                    foreach ($i as $j) {
                        $html = $html . "<td>" . $j . "</td>";
                    }
                    $html = $html . "</tr>";
                }
                $html = $html . "</table>";

                return $html;
            }
            $current_time = time();
            $year = $_GET['y'];
            $curr_year = date('Y', $current_time);
            if (empty($year)) {
                $year = $curr_year;
            }
            $month = $_GET['m'];
            $curr_month = date('n');
            if (empty($month)) {
                $month = $curr_month;
            }
            $month_name = date('F', mktime(0, 0, 0, $month, 10));
            $show_curr_day = ($month == $curr_month) && ($year == $curr_year);
            $curr_day = date('d', $current_time);
            $cal = array(array("S", "M", "T", "W", "T", "F", "S"));
            $cols = 5;
            $rows = 7;
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $day_shift = date('w', strtotime("$year-$month-01"));
            $n = 1;
            for ($i = 1; $i <= $cols; $i++) {
                $row = array();
                for ($j = 1; $j <= $rows; $j++) {
                    $insert = $n - $day_shift;
                    if ($insert > 0 && $insert <= $days_in_month) {
                        $p_day =  sprintf("%02d", $insert);
                        $p_month =  sprintf("%02d", $month);
                        $p_date = $p_day . "-" . $p_month . "-" . $year;

                        if (in_array($p_date, $var_arr)) {
                            array_push($row,  "<a class='entry' href='?y=" . $year . "&m=" . $month . "&date=" . $p_date . ".enc'>" . strval($insert) . "</a>");
                        } else {
                            array_push($row,  "<span class='grey'>" . strval($insert) . "</span>");
                        }

                    } else {
                        array_push($row, "");
                    }
                    $n++;
                }
                array_push($cal, $row);
            }
            $url = $_SERVER['REQUEST_URI'];
            $url = strtok($url, '?');
            $arg_last_year = $year;
            $arg_last_month = $month - 1;
            $arg_next_year = $year;
            $arg_next_month = $month + 1;
            if ($month == 1) {
                $arg_last_month = 12;
                $arg_last_year--;
            }
            if ($month == 12) {
                $arg_next_month = 1;
                $arg_next_year++;
            }
            $arg_last = $url . "?y=" . $arg_last_year . "&m=" . $arg_last_month;
            $arg_next = $url . "?y=" . $arg_next_year . "&m=" . $arg_next_month;
            $link_last = "<a href=" . $arg_last . ">&lt;Last&lt;</a>";
            $link_next = "<a href=" . $arg_next . ">&gt;Next&gt;</a>";
            $title = "<b>" . $month_name . " " . $year . "</b>";

            echo buildHTMLTable($link_last, $title, $link_next, $cal);

            // DECRYPT
            if (isset($_GET['date'])) {
                $file =  $_GET['date'];
                $ciphertext = file_get_contents('./files/' . $file, true);
                $key = $_SESSION["pw"];

                $c = base64_decode($ciphertext);
                $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
                $iv = substr($c, 0, $ivlen);
                $hmac = substr($c, $ivlen, $sha2len = 32);
                $ciphertext_raw = substr($c, $ivlen + $sha2len);
                $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
                $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
                if (hash_equals($hmac, $calcmac))
                {
                    echo '<div id="content-text">';
                    echo htmlspecialchars_decode(nl2br($original_plaintext));
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
    </div>
</body>
</html>