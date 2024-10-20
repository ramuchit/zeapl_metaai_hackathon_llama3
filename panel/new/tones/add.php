<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $tones = $_POST['tones'];
    $targetDir = "../data/tones/tone.json";
    $data=['tone'=>$tones];
    if (file_exists($targetDir)) {
        $existingData = json_decode(file_get_contents($targetDir), true);
        file_put_contents($targetDir, json_encode($data, JSON_PRETTY_PRINT));
    } else {
        file_put_contents($targetDir, json_encode($keyword_array, JSON_PRETTY_PRINT));
    }
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://llama.zeapl.com/panel/update_model.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    header('Location: create.php');
}
?>