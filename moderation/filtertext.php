<?php

header('Content-Type: application/json');

function filter(string $text)
{
    $badlist = array(
    "equinox", 
    "nigger", "nigga", "niger", "niga", "niggger", "niggga", "nigge", "niggge", "nige", "nigg", 
    "faggot", "faggort", "faggo", "fagot", "fago", "fagggot", "faggor", "fagggor", "fag", 
    "chink", "chinky", "chinki", "ch1nk", 
    "kike", "kyke", "k1ke", 
    "spic", "spik", "sp1c", 
    "gook", "g00k", 
    "wetback", "wetbck", "wetbak", "wetb@ck", 
    "beaner", "beanr", "beanar", 
    "coon", "c00n", 
    "jigaboo", "jiggaboo", "jigabo", 
    "porchmonkey", "porch monkey", 
    "sandnigger", "sandnigga", "sandniger", "sandniga", 
    "raghead", "rag hed", "raghed", 
    "camel jockey", "camel jocky", "camel j0ckey", 
    "heeb", "h3eb", 
    "dune coon", "dunecoon", 
    "slant-eye", "slanteye", 
    "honkie", "honky", 
    "cracker", "cracka", 
    "redskin", "redskn", 
    "paki", "p@ki", 
    "tarbaby", "tar baby", 
    "gypsy", "gipsy"
);

    $filterCount = sizeof($badlist);
    for ($i = 0; $i < $filterCount; $i++) {
        $text = preg_replace_callback('/(' . $badlist[$i] . ')/i', function ($matches) {
            return str_repeat('#', strlen($matches[0]));
        }, $text);
    }
    return $text;
}

if (isset($_GET['apiKey']) && !empty($_GET['apiKey'])) {
    $apiKey = $_GET['apiKey'];
} else {
    $apiKey = 'filtertext.php';
}

if (isset($_POST['text']) && isset($_POST['userId']) && !empty($_POST['text']) && !empty($_POST['userId'])) {
    $text = $_POST['text'];
    $userid = $_POST['userId'];

    $textog = filter($text);

    $return = json_encode(
        array(
            "success" => true,
            "data" => array(
                "white" => $textog,
                "black" => $textog
            )
        ),
        JSON_UNESCAPED_SLASHES
    );

    echo $return;
} else {
    $errorResponse = json_encode(
        array(
            "success" => false,
            "error" => "parameters empty"
        ),
        JSON_UNESCAPED_SLASHES
    );

    die($errorResponse);
}
