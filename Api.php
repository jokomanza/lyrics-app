<?php

require 'vendor/autoload.php';


$client = new GuzzleHttp\Client();

$song = "";
if (isset($_GET['song'])) {
    $song =  $_GET['song'];
} else {
    echo "song required";
    die;
}



$params = [
    'query' => [
        'w' => $song
    ]
];

$response = $client->get('https://c.y.qq.com/soso/fcgi-bin/client_search_cp', $params);
$lyric = $response->getBody()->getContents();
$lyric = substr($lyric, 9);
$lyric = substr($lyric, 0, strlen($lyric) - 1);
$result = json_decode($lyric);


$params = [
    'query' => [
        'songmid' => $result->data->song->list[0]->songmid
    ],
    'headers' => [
        'Referer' => 'y.qq.com/portal/player.html'
    ]

];

$response = $client->get('https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric_new.fcg', $params);
$lyric = $response->getBody()->getContents();
$lyric = substr($lyric, 18);
$lyric = substr($lyric, 0, strlen($lyric) - 1);
$result = json_decode($lyric);

$lyrics =  base64_decode($result->lyric);
// header('Content-Type: application/json; charset=utf-8');
echo $lyrics;
die;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.css">
</head>

<body>
    <nav class="nav justify-content-center">
        <a href="#" class="nav-item nav-link active">Home</a>
        <a href="#" class="nav-item nav-link">Profile</a>
        <a href="#" class="nav-item nav-link">Messages</a>
        <a href="#" class="nav-item nav-link disabled" tabindex="-1">Reports</a>
    </nav>

    <div class="container-sm">
        <h1>Hello, world!</h1>
        <?php
        foreach ($result->data->song->list as $data) {
            $singer = $data->singer[0]->name;
            echo "<p>Singer : $singer</p>";
            echo "<p>Album : $data->albumname</p>";
            echo "<p>Song Name : $data->songname</p>";
            echo "<p>Song mid : $data->songmid</p>";
            echo "<br>";
        }

        ?>
        <button id="copyButton" onclick="myCopyFunction()">Copy Lyrics</button>
        <p id="theList">
            <?PHP echo trim($lyrics); ?>
        </p>
    </div>

    <script>
        function myCopyFunction() {
            var myText = document.createElement("textarea")
            myText.value = document.getElementById("theList").innerHTML;
            document.body.appendChild(myText)
            myText.focus();
            myText.select();
            document.execCommand('copy');
            document.body.removeChild(myText);
        }
    </script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</body>

</html>