<?php

class GreceiptHdrController extends Controller {

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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new GreceiptHdr;
        $modeldtl = new GreceiptLine;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_GET['msg']) && $_GET['msg'] == 'search' && !isset($_POST['yt1'])) {
            if (isset($_POST['GreceiptHdr'])) {
                print_r($_POST['GreceiptHdr']);
//                $registered = $model->model()->exists('do_num=:do_num', array(':do_num' => $_POST['PodeliveryHdr']['do_num'])) ? true : false;
//                if ($registered) {
//                    $model = $model->model()->find('do_num=:do_num', array(':do_num' => $_POST['PodeliveryHdr']['do_num']));
//                    $this->actionView($model->id_delivery);
//                    return;
//                }
            }
            //create new delivery
        } elseif (isset($_POST['GreceiptHdr'])&& isset($_POST['yt1'])) {
            $model->attributes = $_POST['GreceiptHdr'];
            $modeldtl->id_product = $_POST['items']['id_product'];
            $modeldtl->product = $_POST['items']['product'];
            $modeldtl->value_disc = $_POST['items']['value_disc'];

            $modeldtl->id_uoms = $_POST['items']['id_uoms'];
            $modeldtl->qty_trans = $_POST['items']['qty_trans'];
            $modeldtl->value_trans = $_POST['items']['value_trans'];
            $modeldtl->percent_tax = $_POST['items']['ppn'];
            $modeldtl->value_tax = 0;
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id_receipt));
        }

        $this->render('create', array(
            'model' => $model, 'modeldtl' => $modeldtl
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GreceiptHdr'])) {
            $model->attributes = $_POST['GreceiptHdr'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id_receipt));
        }

        $this->render('update', array(
            'model' => $model,
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
        $dataProvider = new CActiveDataProvider('GreceiptHdr');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GreceiptHdr('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GreceiptHdr']))
            $model->attributes = $_GET['GreceiptHdr'];

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
        $model = GreceiptHdr::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'greceipt-hdr-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
