<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = "../data/documents/";
    $fileType = strtolower(pathinfo($_FILES["uploadedFile"]["name"], PATHINFO_EXTENSION));
    $uniqueFileName = uniqid('file_', true) . '.' . $fileType;

    $targetFile = $targetDir . $uniqueFileName;
    $uploadOk = 1;
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true); 
    }
    if ($_FILES["uploadedFile"]["size"] > 2097152) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    $allowedFormats = ["jpg", "png", "jpeg", "gif", "pdf"];
   
    if ($uploadOk === 1) {
        if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $targetFile)) {

            try {
                $local_path=$targetFile;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://llamachat.zeapl.com/api/v1/files/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('file'=> new CURLFILE($local_path)),
                CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Authorization: Bearer XXXXXXXXXXXXXXXXXXXXX'
                    ),
                ));
                $response = curl_exec($curl);
                $file_id=json_decode($response,true)['id'];
                curl_close($curl);

                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://llamachat.zeapl.com/api/v1/knowledge/4a25a7c5-fff9-438a-91de-72e0ebdc3c33/file/add',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{"file_id": "'.$file_id.'"}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer XXXXXXXXXXXXXXXXXXXXX'
                ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
            } catch (Exception $e) {
                // Handle any exceptions that occurred during the execution
            } finally {
                // Removing file in case of success or exception
            }
            $data = [
                'id' => uniqid(),
                'file_id' => $file_id,
                'uploadedFilePath' => $targetFile,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $jsonData = json_encode($data, JSON_PRETTY_PRINT);

            $jsonFile = 'uploads/document_data.json';

            if (file_exists($jsonFile)) {
                $existingData = json_decode(file_get_contents($jsonFile), true);
                $existingData[] = $data;
                file_put_contents($jsonFile, json_encode($existingData, JSON_PRETTY_PRINT));
            } else {
                file_put_contents($jsonFile, json_encode([$data], JSON_PRETTY_PRINT));
            }

            header("Location: index.php");
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
