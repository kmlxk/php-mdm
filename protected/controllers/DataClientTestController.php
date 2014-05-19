<?php

class DataClientTestController extends Controller {

    public function actionGet() {
        $client = new MdmDataClient('http://127.0.0.1:9000/php-mdm/index.php');
        $obj = $client->get('', 'petwed.app');
        var_dump($obj);
        $ret = $client->set($obj, 'hello world');
        var_dump($ret);
    }

    public function actionIndex() {
        $this->render('index');
    }

}