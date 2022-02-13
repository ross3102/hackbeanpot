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
                            <span class="card-title">Speak</span>
                            Enter your name and the text you want to hear spoken!
                            <div class="input-field">
                            <input type="text" name="name" id="name">
                            <label for="name">Name</label>
                            </div>
                            <div class="input-field">
                            <input type="text" name="in-text" id="in-text">
                            <label for="in-text">Text</label>
                            </div>

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
        let name = $("#name").val();
        let text = $("#in-text").val();

        $.ajax({
            type: 'POST',
            url: './index.php',
            data: {
                "action": "speak",
                "text": text,
                "name": name
            },
            success: function(data) {
                json = JSON.parse(data);
                new Audio('clips/' + json["name"] + '/' + json["outfile"]).play()
            }
        });
    })
</script>

</html>