<?php
Header("Cache-Control: max-age=3000, must-revalidate");

$action = filter_input(INPUT_GET, "action");
if (!isset($action)) {
    $action = filter_input(INPUT_POST, "action");
    if (!isset($action)) {
        $action = "homepage";
    }
}

$file_map = array(
    "k" => "k.mp3",
    "æ" => "ae.mp3",
    "t" => "t.mp3",
    "i" => "i.mp3",
    "ɛ" => "e.mp3",
    "ɪ" => "bigI.mp3",
    "ɑ" => "revA.mp3",
    "v" => "v.mp3",
    "n" => "newn.mp3",
    "ŋ" => "ng.mp3",
    "h" => "h.mp3",
    "s" => "s.mp3",
    "z" => "z.mp3",
    "ə" => "schwa.mp3",
    "r" => "r.mp3",
    "p" => "p.mp3",
    "m" => "m.mp3",
    "θ" => "th.mp3",
    "l" => "l.mp3",
    "ɔ" => "aw.mp3",
    "b" => "b.mp3",
    "ð" => "thV.mp3",
    "j" => "j.mp3",
    "ʒ" => "zh.mp3",
    "ʧ" => "ch.mp3",
    "ʤ" => "dz.mp3",
    "ʃ" => "sh.mp3",
    "g" => "g.mp3",
    "d" => "d.mp3",
    "w" => "w.mp3",
    "f" => "f.mp3",
    "u" => "u.mp3",
    "ʊ" => "euh.mp3"
);

$double_letters = array(
    "eɪ" => "ei.mp3",
    "aɪ" => "ai.mp3",
    "oʊ" => "ou.mp3",
    "aʊ" => "aun.mp3",
    "ər" => "r.mp3"
);

$letters = ["k", "æ", "t", "i", "ɛ", "ɪ", "ɑ", "v", "n", "ŋ", "h", "s", "z", "ə", "r", "p", "m", "θ", "l", "ɔ", "b", "ð", "j", "ʒ", "ʧ", "ʤ", "ʃ", "g", "d", "w", "f", "u", "ʊ", "eɪ", "aɪ", "oʊ", "aʊ", "ər"];

switch ($action) {
    case "homepage":
        include "view.php";
        break;
    case "save_sound":
        // header("Content-Type", "application/json");
        $file_name = filter_input(INPUT_POST, "filename");
        $name = filter_input(INPUT_POST, "name");
        $blob = $_FILES["sound"];

        if (!file_exists('clips/' . $name)) {
            mkdir('clips/' . $name, 0777, true);
        }

        copy($blob["tmp_name"], "./clips/" . $name . "/" . $file_name);
        break;
    case "tutorial":
        $completion = filter_input(INPUT_GET, "completion");
        $name = filter_input(INPUT_GET, "name");
        if (!isset($completion)) {
            $completion = 0;
        }

        if ($completion >= count($letters)) {
            header("Location: ./index.php?action=homepage");
        } else {
            $letter = $letters[$completion];
            $file_name = ($completion < count($file_map)) ? $file_map[$letter] : $double_letters[$letter];

            include "tutorial.php";
        }

        break;
    case "speak":
        header("Content-Type", "application/json");
        $text = filter_input(INPUT_POST, "text");
        $name = filter_input(INPUT_POST, "name");

        if (!file_exists('clips/' . $name)) {
            $name = "ross3102";
        }

        $outfile = "out" . time() . ".mp3";
        $command = escapeshellcmd('python main2.py ' . $name . ' "' . $text . '" "' . $outfile . '"');
        $output = shell_exec($command);
        echo json_encode(array("outfile"=>$outfile, "name"=>$name));
}
