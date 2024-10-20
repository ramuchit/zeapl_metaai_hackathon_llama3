<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; 
    $jsonFile = 'uploads/document_data.json'; 

    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true); 

        // $newData = array_filter($data, function($record) use ($id) {
        //     if ($record['id'] == $id) {
        //         unlink('uploadedFilePath');
        //     }
        //     return $record['id'] !== $id; 
        // });

        $newData = [];
        foreach ($data as $record) {
            if ($record['id'] == $id) {
                $filePath = $record['uploadedFilePath'];
                if (file_exists($filePath)) {
                   unlink($filePath);
                   if(isset($record['file_id']) && !empty($record['file_id'])) {
                    $file_id = $record['file_id'];
                    try {
                         $curl = curl_init();
                         curl_setopt_array($curl, array(
                         CURLOPT_URL => 'https://llamachat.zeapl.com/api/v1/knowledge/4a25a7c5-fff9-438a-91de-72e0ebdc3c33/file/remove',
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
                             'Authorization: Bearer XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
                         ),
                         ));
 
                        $response = curl_exec($curl);
                        curl_close($curl);
                        } catch (Exception $e) {
                            echo 'and the error is: ',  $e->getMessage(), "\n";
                        }
                   }   
                }
                continue; 
            }
            $newData[] = $record; 
        }

        if (count($newData) < count($data)) {
            file_put_contents($jsonFile, json_encode(array_values($newData), JSON_PRETTY_PRINT));
            echo "Record deleted";
        } else {
            echo "No record found for $id.";
        }
    } else {
        echo "JSON file does not exist.";
    }
}
?>
