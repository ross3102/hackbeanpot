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
                            <span class="card-title">Speak</span>
                            Enter the text you want to hear spoken!
                            <input type="text" name="in-text" id="in-text">

                            <button class=" btn btn-large blue lighten-1" type="submit" id="submit">Submit</button>
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
    const submitButton = $("#submit");

    submitButton.click(function() {
        let text = $("#in-text").val();

        $.ajax({
            type: 'GET',
            url: './index.php',
            data: {
                "action": "speak",
                "text": text
            },
            success: function(data) {
                new Audio('clips/ross3102/out.mp3').play()
            }
        });
    })
</script>

</html>