<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 xsteach.com all rights reserved.
 */
namespace choate\vsftpd\controllers;

use choate\vsftpd\generators\GeneratorUserConfig;
use choate\vsftpd\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class UserController
 * @package choate\vsftpd\controllers
 * @author Choate <choate.yao@gmail.com>
 */
class UserController extends Controller
{
    /**
     * @var \choate\vsftpd\Module
     * @author Choate <choate.yao@gmail.com>
     */
    public $module;

    public function actionIndex() {
        /* @var User $model */
        $model = new User();

        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $model->find()])]);
    }

    public function actionCreate() {
        /* @var User $model */
        $model = new User();
        if ($model->load($_POST) && $model->save()) {
            $generator = new GeneratorUserConfig(['module' => $this->module, 'model' => $model]);
            $generator->generate();

            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id) {
        /* @var User $model */
        $model = User::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        if ($model->load($_POST) && $model->save()) {
            $generator = new GeneratorUserConfig(['module' => $this->module, 'model' => $model]);
            $generator->generate();

            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id) {
        /* @var User $model */
        $model = User::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $generator = new GeneratorUserConfig(['module' => $this->module, 'model' => $model]);
        $generator->remove();
        $model->delete();

        return $this->redirect(['index']);
    }
}