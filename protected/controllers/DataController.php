<?php

function getJsonData($data, $success = true, $message = '' ) {
    return array(
        'data' => $data,
        'success' => $success,
        'message' => $message,
    );
}
function getJsonMessage($success, $message) {
    return array(
        'success' => $success,
        'message' => $message,
    );
}

class DataController extends Controller {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionGet() {
        try {
            $namespace = Yii::app()->request->getParam('ns');
            $key = Yii::app()->request->getParam('k');
            
            $data = MdmDataService::get($namespace, $key);
            if ($data == null) {
                $ret = getJsonData($data);
            } else {
                $ret = getJsonData($data->attributes);
            }
            echo json_encode($ret);
        } catch (Exception $ex) {

            $msg = getJsonMessage(false, $ex->getMessage());
            echo json_encode($msg);
        }
    }

    public function actionSet() {
        try {
            
            $namespace = Yii::app()->request->getParam('ns');
            $key = Yii::app()->request->getParam('k');
            $value = Yii::app()->request->getParam('v');
            $version = intval(Yii::app()->request->getParam('ver'));
            
            MdmDataService::set($namespace, $key, $value, $version);
            
            $msg = getJsonMessage(true, 'success');
            echo json_encode($msg);
        } catch (Exception $ex) {

            $msg = getJsonMessage(false, $ex->getMessage());
            echo json_encode($msg);
        }
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}