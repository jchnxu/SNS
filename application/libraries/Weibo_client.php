<?php

require_once('Saetclientv2.php');

class Weibo_client {

    function __construct() {
    }

    public function build($params) {
        if (isset($params['refresh_token'])) {
            $client = new Saetclientv2($params['akey'], $params['skey'], $params['access_token'], $params['refresh_token']);
        }
        else {
            $client = new Saetclientv2($params['akey'], $params['skey'], $params['access_token']);
        }
        return $client;
    }
}
