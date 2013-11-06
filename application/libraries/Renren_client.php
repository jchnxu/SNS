<?php
require_once ('RennClient.php');

class Renren_client extends RennClient {
    function __construct() {
    }

    public function build($params) {
        return new RennClient($params['client_id'], $params['client_secret']);
    }
}
