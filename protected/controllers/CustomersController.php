<?php

class CustomersController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $limit = CustLimitation::model()->find('id_customer=:cust', array(':cust' => $model->id_customer));
        $dtl = CustDetail::model()->find('id_customer=:cust', array(':cust' => $model->id_customer));

        $this->render('view', array(
            'model' => $model, 'limit' => $limit, 'dtl' => $dtl
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Customers;
        $limit = new CustLimitation;
        $dtl = new CustDetail;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Customers'])) {
            $conn = Yii::app()->db->beginTransaction();
            try {
                $model->attributes = $_POST['Customers'];
                $limit->attributes = $_POST['CustLimitation'];
                $dtl->attributes = $_POST['CustDetail'];

                if (!$model->save()) {
                    $upsalah = $model->getErrors();
                    foreach ($upsalah as $key => $value) {
                        Yii::app()->user->setFlash('error', $value[0]);
                    }
                } else {
                    $limit->id_customer = $model->id_customer;
                    $dtl->id_customer = $model->id_customer;
                    if (!$limit->save()) {
                        $upsalah = $limit->getErrors();
                        foreach ($upsalah as $key => $value) {
                            Yii::app()->user->setFlash('error', $value[0]);
                        }
                    } elseif (!$dtl->save()) {
                        $upsalah = $dtl->getErrors();
                        foreach ($upsalah as $key => $value) {
                            Yii::app()->user->setFlash('error', $value[0]);
                        }
                    } else {
                        $conn->commit();
                        Yii::app()->user->setFlash('success', 'Customer ' . $model->cd_cust . ' created..');
                    }
                }
            } catch (Exception $exc) {
                $conn->rollback();
                echo $exc->getTraceAsString();
            }
        }

        $this->render('create', array(
            'model' => $model, 'limit' => $limit, 'dtl' => $dtl
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $limit = CustLimitation::model()->find('id_customer=:cust', array(':cust' => $model->id_customer));
        $dtl = CustDetail::model()->find('id_customer=:cust', array(':cust' => $model->id_customer));

        if (isset($_POST['Customers'])) {
            $conn = Yii::app()->db->beginTransaction();
            try {
                $model->attributes = $_POST['Customers'];
                $limit->attributes = $_POST['CustLimitation'];
                $dtl->attributes = $_POST['CustDetail'];

                if (!$model->save()) {
                    $upsalah = $model->getErrors();
                    foreach ($upsalah as $key => $value) {
                        Yii::app()->user->setFlash('error', $value[0]);
                    }
                } else {
                    $limit->id_customer = $model->id_customer;
                    $dtl->id_customer = $model->id_customer;
                    if (!$limit->save()) {
                        $upsalah = $limit->getErrors();
                        foreach ($upsalah as $key => $value) {
                            Yii::app()->user->setFlash('error', $value[0]);
                        }
                    } elseif (!$dtl->save()) {
                        $upsalah = $dtl->getErrors();
                        foreach ($upsalah as $key => $value) {
                            Yii::app()->user->setFlash('error', $value[0]);
                        }
                    } else {
                        $conn->commit();
                        Yii::app()->user->setFlash('success', 'Customer ' . $model->cd_cust . ' updated..');
                        $this->render('view', array(
                            'model' => $model, 'limit' => $limit, 'dtl' => $dtl
                        ));
                    }
                }
            } catch (Exception $exc) {
                $conn->rollback();
                echo $exc->getTraceAsString();
            }
        }

        $this->render('update', array(
            'model' => $model, 'limit' => $limit, 'dtl' => $dtl
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Customers');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Customers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customers']))
            $model->attributes = $_GET['Customers'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Customers::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'customers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
