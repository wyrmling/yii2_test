<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\Products;
use app\models\Categories;
use yii\helpers\ArrayHelper;
use app\models\AttributesList;

class ProductsController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAdd()
    {
        $products = (new Products)->loadDefaultValues();

        if ($products->load(Yii::$app->request->post()) && $products->validate()) {
            $res = $products->save();
            $productParams = ArrayHelper::toArray($products);

            if ($productParams['status'] == Products::VISIBLE) {
                Categories::setCategoriesCounters($productParams['category_id'], 1, 0);
            }
            if ($productParams['status'] == Products::HIDDEN) {
                Categories::setCategoriesCounters($productParams['category_id'], 0, 1);
            }
            return $this->redirect('/admin/products/edit/' . $products->product_id);
        } else {
            return $this->render('add', ['model' => $products, 'type' => 'add']);
        }
    }

    public function actionEdit($id)
    {
        $products = Products::find()
                ->where(['product_id' => $id])
                ->one();
        $productOldParams = ArrayHelper::toArray($products);

        if ($products->load(Yii::$app->request->post()) && $products->validate()) {
            $results = $products->save();
            $productParams = ArrayHelper::toArray($products);

            if ((int) $productParams['status'] != (int) $productOldParams['status']
                    && (int) $productParams['category_id'] != (int) $productOldParams['category_id']) {
                if ($productParams['status'] == Products::VISIBLE) {
                    Categories::setCategoriesCounters($productOldParams['category_id'], 0, -1);
                    Categories::setCategoriesCounters($productParams['category_id'], 1, 0);
                }
                if ($productParams['status'] == Products::HIDDEN) {
                    Categories::setCategoriesCounters($productOldParams['category_id'], -1, 0);
                    Categories::setCategoriesCounters($productParams['category_id'], 0, 1);
                }
            } else {
                if ($productParams['status'] == Products::VISIBLE
                        && (int) $productParams['status'] != (int) $productOldParams['status']
                        && (int) $productParams['category_id'] = (int) $productOldParams['category_id']) {
                    Categories::setCategoriesCounters($productParams['category_id'], 1, -1);
                } elseif ($productParams['status'] == Products::HIDDEN
                        && (int) $productParams['status'] != (int) $productOldParams['status']
                        && (int) $productParams['category_id'] = (int) $productOldParams['category_id']) {
                    Categories::setCategoriesCounters($productParams['category_id'], -1, 1);
                } elseif ($productParams['status'] == Products::VISIBLE
                        && (int) $productParams['status'] = (int) $productOldParams['status']
                        && (int) $productParams['category_id'] != (int) $productOldParams['category_id']) {
                    Categories::setCategoriesCounters($productOldParams['category_id'], -1, 0);
                    Categories::setCategoriesCounters($productParams['category_id'], 1, 0);
                } else {
                    Categories::setCategoriesCounters($productOldParams['category_id'], 0, -1);
                    Categories::setCategoriesCounters($productParams['category_id'], 0, 1);
                }
            }
            return $this->render('edit', ['model' => $products, 'type' => 'edit', 'result' => $results]);
        } else {
            return $this->render('edit', ['model' => $products, 'type' => 'create']);
        }
    }

    public function actionDelete($id)
    {
        $productParams = Products::find()
                ->where(['product_id' => $id])
                ->asArray()
                ->one();

        if (Products::deleteAll(['product_id' => $id])) {

            if ($productParams['status'] == Products::VISIBLE) {
                Categories::setCategoriesCounters($productParams['category_id'], -1, 0);
            }
            if ($productParams['status'] == Products::HIDDEN) {
                Categories::setCategoriesCounters($productParams['category_id'], 0, -1);
            }
            return $this->redirect('/admin/products');
        }
    }

public function actionAttributes($id=1)
{
    $att = AttributesList::find()
                ->where(['product_id' => $id])
                ->asArray()
                ->all();
    
    $atributs_list[1] = (new AttributesList)->loadDefaultValues();
    $atributs_list[2] = (new AttributesList)->loadDefaultValues();
    $atributs_list[3] = (new AttributesList)->loadDefaultValues();
    
    if (AttributesList::loadMultiple($atributs_list, Yii::$app->request->post()) && 
        AttributesList::validateMultiple($atributs_list)) {
        $counter = 0;
        foreach ($atributs_list as $item) {
            if ($item->save()) {
                $counter++;
            }
        }
        Yii::$app->session->setFlash('success', "Processed {$counter} records successfully.");
        return $this->redirect(['index']);
    } else {
        return $this->render('attributes', [
            'atributs_list' => $atributs_list, 
            'product_id' => $id,
            'att' => $att,
        ]);
    }
}
   


}
