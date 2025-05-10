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
        "gypsy", "gipsy", "nig.ger","niggar","neega"
    );

    foreach ($badlist as $badword) {
        $text = preg_replace_callback('/\b' . preg_quote($badword, '/') . '\b/i', function ($matches) {
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

    $filteredText = filter($text);

    $response = array(
        "success" => true,
        "data" => array(
            "AgeUnder13" => $filteredText,
            "Age13OrOver" => $filteredText
        )
    );

    echo json_encode($response, JSON_UNESCAPED_SLASHES);
} else {
    $errorResponse = array(
        "success" => false,
        "error" => "parameters empty"
    );

    echo json_encode($errorResponse, JSON_UNESCAPED_SLASHES);
}
?>
