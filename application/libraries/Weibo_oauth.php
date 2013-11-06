<?php

require_once('Saetoauthv2.php');

class Weibo_oauth extends Saetoauthv2 {

    function __construct($params) {
        if (isset($params['access_token'])) {
            if(isset($params['refresh_token'])) {
                parent::__construct($params['client_id'], $params['client_secret'], $params['access_token'], $params['refresh_token']);
            }
            else {
                parent::__construct($params['client_id'], $params['client_secret'], $params['access_token']);
            }
        }
        else {
            parent::__construct($params['client_id'], $params['client_secret']);
        }
    }
}
