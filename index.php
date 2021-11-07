<?php

require 'vendor/autoload.php';
error_reporting(0);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <style>
        .container {
            margin-top: 30px;
        }

        .filter-col {
            padding-left: 10px;
            padding-right: 10px;
        }

        .no-js #loader {
            display: none;
        }

        .js #loader {
            display: block;
            position: absolute;
            left: 100px;
            top: 0;
        }

        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url(https://smallenvelop.com/wp-content/uploads/2014/08/Preloader_11.gif) center no-repeat #fff;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
    <script>
        $(window).load(function() {
            // Animate loader off screen

            $(".se-pre-con").fadeOut("slow");;
        });
    </script>
</head>

<body>
    <div class="se-pre-con"></div>

    <div class=" " style="<?php echo empty($_POST['song']) ? 'display:flex;flex-direction: column;justify-content: space-between;' : '' ?> min-height:100%; min-height:100vh;">

        <nav class="navbar navbar-default navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        <p>Lyrics App</p>
                    </a>
                </div>


            </div>
        </nav>
        <div class="center d-flex justify-content-center align-items-center" style="margin-top: 0;display:flex; width: 100%">
            <div class="container">

                <form method="POST" action="index.php">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Song</label>
                        <input type="text" value="<?php echo (isset($_POST['song']) ? $_POST['song'] : '') ?>" class="form-control" name="song" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <small id="emailHelp" class="form-text text-muted">Enter the title of the song you want to find the lyrics for</small>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" value="<?php echo isset($_POST['showOne']) ? 'YES' : 'NO' ?>" name="showOne" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Show only the first found</label>
                    </div>
                    <div id="filter-panel" class="collapse hide form-group">
                        <label class="filter-col" style="margin-right:0;" for="pref-perpage">Rows per page:</label>
                        <select id="pref-perpage" name="count" class="form-control">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option selected="selected" value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="300">300</option>
                            <option value="400">400</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary mb-3" data-toggle="collapse" data-target="#filter-panel">
                        <span class="glyphicon glyphicon-cog"></span> Advanced Search
                    </button>
                    <button type="submit" class="btn btn-primary mb-3">Submit</button>
                </form>


                <div class="row">
                    <div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form class="form-inline" role="form">

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <button class="btn btn-default">Copy</button> -->


                <?php

                $client = new GuzzleHttp\Client();

                $song = "";
                $showOne = isset($_POST['showOne']) ? true : false;

                if (isset($_POST['song'])) {
                    $song =  $_POST['song'];
                    $params = [
                        'query' => [
                            'w' => $song,
                            'p' => 1,
                            'n' => $showOne ? 1 : (empty($_POST['count']) ? 10 : $_POST['count'])
                        ]
                    ];

                    $response = $client->get('https://c.y.qq.com/soso/fcgi-bin/client_search_cp', $params);
                    $lyric = $response->getBody()->getContents();
                    $lyric = substr($lyric, 9);
                    $lyric = substr($lyric, 0, strlen($lyric) - 1);
                    $result = json_decode($lyric);

                    // echo "<pre>";
                    // print_r($result);
                    // die;

                    if ($result->data->song->totalnum == 0) {
                        echo <<<END
                        <div class="alert alert-danger" role="alert">
                            Song lyrics from the keyword you entered was not found
                        </div>
                    END;
                    } else {
                        echo <<<END
                        <div class="alert alert-success" role="alert">
                            The search process was successful, here are some song lyrics that match the keywords you entered
                        </div>
                    END;

                        echo <<<END
                        <div class="accordion" id="accordionExample">
                    END;
                        foreach ($result->data->song->list as $item) {
                            // print_r($item->docid);
                            // die;
                            $singer = $item->singer[0]->name;
                            $songmid = $item->songmid;
                            $params = [
                                'query' => [
                                    'songmid' => $songmid
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

                            // print_r($result);

                            if (empty($result->lyric)) {
                                $lyrics = "Lyrics not found";
                            } else {
                                $lyrics =  base64_decode($result->lyric);
                            }

                            // print_r($item);
                            // die;
                            echo <<<END
                            <div class="card mb-0">
                                <div class="card-header" id="headingOne">
                                <p>$item->songname by $singer</p>
                                <p>Album : $item->albumname</p>
                                    <h5 class="mb-0">
                                        <button class="btn btn-primary" data-toggle="collapse" data-target="#collapse$item->docid" aria-expanded="true" aria-controls="collapseOne">
                                            Show Lyric
                                        </button>
                                        <button onclick="copyLyric('$item->songid')" id="copyItem$item->docid" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">
                                            Copy Lyric
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapse$item->docid" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <pre id="lyric$item->songid">$lyrics</pre>
                                    </div>
                                </div>
                            </div>
                        END;
                        }
                        echo <<<END
                        </div>
                    END;
                    }
                }

                ?>


            </div>
        </div>
        <footer class="text-center text-lg-start bg-light text-muted mt-5" style=" bottom: 0; width: 100%; ">
            <!-- Copyright -->
            <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
                © 2021 Copyright:
                <a class="text-reset fw-bold" href="https://jokomanza.skom.id/">Jokomanza.skom.id</a>
            </div>
            <!-- Copyright -->
        </footer>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    The lyrics have been successfully copied
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script>
        function copyLyric(docid) {
            var myText = document.createElement("textarea")
            myText.value = document.getElementById("lyric" + docid).innerHTML;
            document.body.appendChild(myText)
            myText.focus();
            myText.select();
            document.execCommand('copy');
            document.body.removeChild(myText);
        }
    </script>
</body>

</html>