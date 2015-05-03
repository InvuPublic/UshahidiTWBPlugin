<?php defined('SYSPATH') or die('No direct script access.');

class TWB_API
{
    // API key used for auth with TWB services
    var $apiKey = '';

    // Currently active project ID
    var $projectID = '';

    // Base URL used for TWB services
    var $baseURL = 'https://twb.translationcenter.org/api/v1/';

    public function __construct()
    {
        $settings  = ORM::factory('twb_settings')->find(1);
        $this->apiKey = $settings->api_key;
    }

    public function postDocument($documentPath)
    {
        $endpoint = $this->baseURL . 'documents';
        $auth = $this->_getAuthString();
    }

    public function postOrder($documentID)
    {
        $endpoint = $this->baseURL . 'orders';
        $handle = curl_init($endpoint);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, 1);

        /// TODO Since we'll be retrieving this also, better make it a type
        $data = array();
        $data["contact_email"] = "robert@twentyideas.com";
        $data["title"] = "Test title";
        $data["source_lang"] =  "eng";
        $data["target_langs"] =  array("fra");
        $data["source_document_ids"] = array($documentID);
        $data["source_wordcount"] = 17;
        $data["instructions"] = "Sample instructions for this job.";
        $data["deadline"] =  "2015-06-01 00:00:00";
        $data["urgency"] = "high";

        $data_json = json_encode($data);

        curl_setopt($handle, CURLOPT_POSTFIELDS, $data_json);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Content-length: " . strlen($data_json);
        $headers[] = "X-Proz-API-Key: " . $this->apiKey;
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($handle);
        return $result;
    }
}
