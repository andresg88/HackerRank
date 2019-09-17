<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Cube Summation!</h1>

        <p class="lead">Aplicacion en yii2</p>

        <p><a class="btn btn-lg btn-success" href="https://www.hackerrank.com/challenges/cube-summation/problem">Prueba HackerRank PHP</a></p>
    </div>

    <div class="body-content">
    <center>
        Input<br>
        <?php
$textarea = '2
4 5
UPDATE 2 2 2 4
QUERY 1 1 1 3 3 3
UPDATE 1 1 1 23
QUERY 2 2 2 4 4 4
QUERY 1 1 1 3 3 3
2 4
UPDATE 2 2 2 1
QUERY 1 1 1 1 1 1
QUERY 1 1 1 2 2 2
QUERY 2 2 2 2 2 2';
            echo Html::textarea('input', $textarea, ['rows' => 13 , 'cols' => 50]); 
        ?>
        <br>
        <div class="form-group">
            <div class="col-lg-12">
                <?= Html::a('Cube', Url::to(['site/index']), [
                        'class'=>'btn btn-primary',
                        'data' => [
                            'method' => 'POST', 
                            'params' => [
                                'input' => $textarea
                            ]
                        ]
                    ]) 
                ?>
            </div>
        </div>
        <br>
        Output<br>
        <textarea id='output' rows="7" cols="50">
<?php 
if(!empty($output)){
// echo "<pre>";
// print_r($output);
// die;
$output = implode("\n", $output);
echo trim($output);
}
else{
echo ltrim("No hay datos aun");
}
?>
        </textarea>

        <br>
        OutPut Esperado resultado correcto<br>
        <textarea id='output' rows="7" cols="50">
4
4
27
0
1
1
        </textarea>
    </center>

    </div>
</div>
