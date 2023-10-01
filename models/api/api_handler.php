<?php
class ApiHandler {
    /**
     * Fetch and parse data from an external API.
     *
     * This function sends a cURL request to the specified API URL, retrieves the response data, and parses it as JSON. The parsed data is returned as an associative array.
     *
     * @param string $apiUrl The URL of the external API to fetch data from.
     *
     * @throws Exception If there is an issue with the cURL request or if the response data cannot be parsed as JSON, an exception is thrown with a relevant error message.
     *
     * @return array An associative array representing the fetched data from the API.
     */
    public static function GetDataFromAPI($apiUrl) {
        try {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $apiUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                throw new Exception("cURL Error: " . curl_error($curl));
            }

            curl_close($curl);

            $data = json_decode($response, true);

            if (empty($data)) {
                throw new Exception("Failed to fetch data from the API.");
            }

            return $data;
        } catch (Exception $e) {
            throw new Exception("Error in GetDataFromAPI: " . $e->getMessage());
        }
    }
}

?>