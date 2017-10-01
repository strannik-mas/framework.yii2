<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\MyForm;
use app\models\Comments;
use yii\helpers\Html;
use yii\data\Pagination;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
	public function actionHello($message = 'Hello, World!')
	{
		return $this->render('hello', //hello - название представления
					['message' => $message]
		);
	}
	public function actionForm(){
		$form = new MyForm();
		if ($form->load(Yii::$app->request->post()) && $form->validate())
		{
			$name = Html::encode($form->name);
			$email = Html::encode($form->email);
			$form->file = UploadedFile::getInstance($form, 'file');
			$form->file->saveAs('photo/'.$form->file->baseName.'.'.$form->file->extension);
		}
		else
		{
			$name = '';
			$email = '';
		}
		return $this->render('form',
			['form'=>$form,
			'name'=> $name,
			'email'=> $email
			]
		);
		
	}
	public function actionComments()
	{
		$comments = Comments::find()/*->all()*/;
		
		$pagination = new Pagination([
			'defaultPageSize' => 2, //по 2 коммента на странице
			'totalCount' => $comments->count()			
		]);
		$comments = $comments->offset($pagination->offset)
						->limit($pagination->limit)
						->all();
		$cookies = Yii::$app->request->cookies;
		
		return $this->render('comments', [
			'comments' => $comments,
			'pagination' => $pagination,
			'name' => $cookies->getValue('name'),
			//'name' => Yii::$app->session->get('name'),
		]);
	}
	public function actionUser()
	{
		
		$name = Yii::$app->request->get('name');
		$cookies = Yii::$app->response->cookies;
		$cookies->add(new \yii\web\Cookie([
			'name' => 'name',
			'value' => $name,
		]));
		//$cookies->remove('name');
		/*
		$session = Yii::$app->session;
		$session->set('name', $name);
		*/
		//$session->remove('name');
		
		//$name = Yii::$app->request->get('name', "Guest"); 
		//это эквивалент следующей записи:
		/* if(isset($_GET["name"])) $name = $_GET["name"];
		else $name = "Guest"; */
		return $this->render('user', [
			'name' => $name
		]);
	}
}
