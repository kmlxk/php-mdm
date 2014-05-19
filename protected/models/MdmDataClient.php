<?php

class MdmDataClient {

    protected $_baseUrl;

    public function __construct($baseUrl) {
        $this->_baseUrl = $baseUrl;
    }

    public function set($kv, $value) {
        $encodeValue = urlencode($value);
        $url = $this->_baseUrl . "?r=data/set&k={$kv['key']}&v={$encodeValue}&ver={$kv['version']}&h={$kv['hash']}";
        echo $url;
        $json = file_get_contents($url);
        $msg = json_decode($json, true);
        if (isset($msg['success']) &&
                $msg['success']) {
            return true;
        }
        return false;
    }

    public function get($namespace, $key) {
        $url = $this->_baseUrl . "?r=data/get&k={$key}";
        $json = file_get_contents($url);
        $msg = json_decode($json, true);
        if (isset($msg['success']) &&
                $msg['success']) {
            return $msg['data'];
        }
        return null;
    }

}

