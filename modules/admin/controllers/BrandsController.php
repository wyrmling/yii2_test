<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\Brands;

class BrandsController extends \yii\web\Controller
    {

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAdd()
    {
        $brands = (new Brands)->loadDefaultValues();

        if ($brands->load(Yii::$app->request->post()) && $brands->validate()) {
            $res = $brands->save();
            return $this->redirect('/admin/brands/edit/' . $brands->brand_id);
        } else {
            return $this->render('add', ['model' => $brands, 'type' => 'create']);
        }
    }

    public function actionEdit($id)
    {
        if (!empty($id)) {
            $brands = Brands::find()
                    ->where(['brand_id' => $id])
                    ->one();

            if ($brands->load(Yii::$app->request->post()) && $brands->validate()) {
                $results = $brands->save();
                return $this->render('edit', ['model' => $brands, 'type' => 'create', 'result' => $results]);
            } else {
                return $this->render('edit', ['model' => $brands, 'type' => 'edit']);
            }
        }
    }

    public function actionDelete($id)
    {
        if (Brands::deleteAll(['brand_id' => $id]))
            return $this->redirect('/admin/brands');
    }

    }
