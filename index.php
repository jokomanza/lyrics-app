<?php
header('Access-Control-Allow-Origin: *');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>

<body>
    <div class=" d-flex justify-content-center align-items-center
" style="min-height:100%; min-height:100vh; align-items:center; display:flex">

        <div class="container">
            <form>
                <div class="mb-3">
                    <label for="inputTitle" class="form-label">Search</label>
                    <input type="text" class="form-control" id="inputTitle">
                    <div id="title" class="form-text">Search lyrics by song and artist name</div>
                </div>
                <button onclick="search()" class="btn btn-primary">Search</button>
            </form>

            <p id="result"></p>
        </div>
    </div>
    <!-- <pre id="main"></pre>
    <button onclick="userAction()">Search</button> -->


    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script>
        const search = async () => {
            const response = await fetch('Api.php?song=' + document.getElementById('inputTitle').value);
            const json = await response.json(); //extract JSON from the http response
            console.log(json)
            // document.getElementById("main").textContent = JSON.stringify(json.data.song.list, undefined, 2);
            //document.getElementById("result").innerText = response;
            //Refresh your control,eg if it is a listview
            //$('#favorite-table-id').listview('refresh');
        }
    </script>
</body>

</html>