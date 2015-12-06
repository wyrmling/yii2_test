<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\News;

class NewsController extends Controller
{
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCreate() {
        $news = new News;

        if ($news->load(Yii::$app->request->post()) && $news->validate()) {
            $res = $news->save();
            return $this->redirect('/admin/news/edit/' . $news->news_id);
        } else {
            return $this->render('create', ['model' => $news, 'type' => 'create']);
        }
    }

    public function actionEdit($id)
    {
        if (!empty($id)) {
            $news = News::find()
                ->where(['news_id' => $id])
                ->one();

            if ($news->load(Yii::$app->request->post()) && $news->validate()) {
                $results = $news->save();
                return $this->render('edit', ['model' => $news, 'type' => 'create', 'result' => $results]);
            } else {
                return $this->render('edit', ['model' => $news, 'type' => 'edit']);
            }
        }
    }

    public function actionDelete($id) {
        if (News::deleteAll(['news_id' => $id]))
            return $this->redirect('/admin/news');
    }

}