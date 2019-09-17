<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->request->post()) {
            // echo "<pre>";
            // print_r($_POST['input']);
            // die;
            $input = $_POST['input'];
            $my_file = 'cube.txt';
            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
            fwrite($handle, $input);
            $output = $this->cube();
            return $this->render('index', ['output' => $output]);
        }
        return $this->render('index');
    }

    public function cube(){
        $input = fopen('../web/cube.txt', "r");
        $t= fgets($input);
        $cube = [];
        for($i=0;$i<$t;$i++){
            $Matriz=fgets($input);
            $Matriz = explode(" ", $Matriz);
            $n=$Matriz[0];
            $m=$Matriz[1];
            // echo $n." ".$m;  die;
            $arr = array_fill(0, $n*$n*$n, 0);
            for($j=0; $j<$m; $j++){
                $operation=fgets($input);
                $operation = explode(" ", $operation);
                if($operation[0]=="UPDATE"){
                    $arr = $this->update($operation[1],$operation[2],$operation[3],$operation[4],$n,$arr);
                    // array_push($cube, (int) $arr);
                }else{
                    $val = $this->query($operation[1],$operation[2],$operation[3],$operation[4],$operation[5],$operation[6],$n,$arr);
                    array_push($cube, (int) $val);
                }
            }  
        }
        return $cube;
    }
    
    public function update($x, $y, $z, $value, $n, $arr){
        $arr[$this->getIndex($x, $y, $z,$n)] = (int) $value;
        return $arr;
    }

    public function query($x1,$y1,$z1,$x2,$y2,$z2,$n,$arr){
        $arr = (array) $arr;
        $a = $this->getIndex($x1, $y1, $z1, $n);
        $b = $this->getIndex($x2, $y2, $z2, $n) + 1;
        $newArr = array_slice($arr, $a, $b);
        $aux = array_sum($newArr)."\n";
        return $aux;
    }

    public function getIndex($x, $y, $z, $n){
        $x = (int) $x;
        $y = (int)  $y;
        $z = (int)  $z; 
        $n = (int)  $n;
        $result = (($n*$n)*($x-1) + ($n*($y-1)) + $z) - 1;
        // echo $result;  die;
        return $result;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
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

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
