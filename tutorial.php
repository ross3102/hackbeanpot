<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <main>
        <div class="container" style="margin: 0 auto">
            <div class="row">
                <div class="col s12 m6 offset-m3">
                    <div class="card white">
                        <div class="card-content center-align">
                            <span class="card-title">Record Sound</span>
                            Listen to the following recording and replicate it as close as possible.
                            The phonetic sound in question is <?= $letter ?>
                            <audio controls>
                                <source src="clips/ross3102/<?= $file_name ?>" type="audio/mp3">
                                Your browser does not support the audio element.
                            </audio>

                            <br>

                            <form action="./index.php" method="post">
                                <input type="hidden" name="action" value="save_sound">
                                <input type="hidden" name="completion" value="<?= $completion + 1 ?>">
                                <audio id="player" controls></audio>
                                <input id="sound" type="hidden" name="sound">
                            </form>

                            <br>
                            <button class=" btn blue lighten-1" id="start">Start</button>&nbsp;
                            <button class="btn blue lighten-1 disabled" id="stop">Stop</button>
                            <br>
                            <br>

                            <button class="btn btn-large blue lighten-1" type="submit" id="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script src="js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<script>
    const player = document.getElementById('player');
    const downloadLink = document.getElementById('download');
    const startButton = $('#start');
    const stopButton = $('#stop');
    const submitButton = $("#submit");

    let blob = null;

    const handleSuccess = function(stream) {
        const mediaRecorder = new MediaRecorder(stream);
        let chunks = [];

        startButton.click(function() {
            mediaRecorder.start();
            console.log(mediaRecorder.state);
            startButton.addClass("disabled");
            stopButton.removeClass("disabled");
        });

        mediaRecorder.ondataavailable = function(e) {
            chunks.push(e.data);
        }

        stopButton.click(function() {
            mediaRecorder.stop();
            console.log(mediaRecorder.state);
            stopButton.addClass("disabled");
            startButton.removeClass("disabled");
        });

        mediaRecorder.onstop = function(e) {
            const clipName = "clips/guy/<?= $file_name ?>";
            blob = new Blob(chunks);
            player.src = window.URL.createObjectURL(blob);
            chunks = [];
        }
    };

    navigator.mediaDevices.getUserMedia({
            audio: true,
            video: false
        })
        .then(handleSuccess);

    submitButton.click(function() {
        if (blob == null)
            return;

        console.log(blob)

        $.ajax({
            type: 'POST',
            url: './index.php',
            data: {
                "action": "save_sound",
                "sound": blob
            },
            processData: false,
            contentType: false,
            success: function(data) {
                console.log(data);
                location.href = "./index.php?action=tutorial&completion=<?= $completion + 1 ?>"
            }
        });
    })
</script>

</html>