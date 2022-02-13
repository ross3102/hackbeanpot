<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <nav class="blue lighten-1">
        <div class="nav-wrapper">
            <div class="container">
                <div class="brand-logo" style="font-family: 'Comfortaa'"><a href="./index.php">speakr</a></div>
                <ul class="right">
                    <li><a href="./index.php?action=tutorial">Voice Creator</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="container" style="margin: 20px auto">
            <div class="row">
                <div class="col s12 l6 offset-l3">
                    <div class="card white">
                        <div class="card-content center-align">
                            <span class="card-title">Record Sound</span>
                            Listen to the following recording and replicate it as close as possible.<br>
                            The phonetic sound in question is <?= $letter ?>

                            <br>

                            <audio controls>
                                <source src="clips/ross3102/<?= $file_name ?>" type="audio/mp3">
                                Your browser does not support the audio element.
                            </audio>

                            <form action="./index.php" method="post" style="margin: 10px 0">
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
        <?php if (!isset($name)): ?>
            name = prompt("Enter your name");
        <?php else: ?>
            name = "<?= $name ?>"
        <?php endif; ?>

        if (blob == null)
            return;

        console.log(blob)

        var formData = new FormData();

        formData.append("action", "save_sound");
        formData.append("filename", "<?= $file_name ?>");
        formData.append("name", name);
        formData.append("sound", blob);

        $.ajax({
            type: 'POST',
            url: './index.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                console.log(data);
                location.href = "./index.php?action=tutorial&completion=<?= $completion + 1 ?>&name=" + name
            }
        });
    })
</script>

</html>