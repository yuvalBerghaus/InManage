<?php
class ApiHandler {
    public static function GetDataFromAPI($apiUrl) {
        $curl = curl_init();
    
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            die("cURL Error: " . curl_error($curl));
        }
    
        curl_close($curl);
    
        $data = json_decode($response, true);
    
        if (empty($data)) {
            die("Failed to fetch data from the API.");
        }
    
        return $data;
    }
}

?>