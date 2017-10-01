<?php

namespace app\controllers;
use Yii;
use app\models\MyForm;


class MyController extends \yii\web\Controller
{
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionAuthor()
    {
        return $this->render('author');
    }

    
	public function actionIndex()
	{
		$model = new MyForm();

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				// form inputs are valid, do something here
				return;
			}
		}

		return $this->render('index', [
			'model' => $model,
		]);
	}


}
