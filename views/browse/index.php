<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\i18n\Formatter;
use yii\log\Logger;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logs');
$this->params['breadcrumbs'][] = $this->title;

$categoriesArray = ArrayHelper::getColumn($categories, 'category');
$categoriesList = array_combine( $categoriesArray, $categoriesArray  );
$levelsArray = ArrayHelper::getColumn($levels, 'level');
$levelsLabels = array_map( function($level){ return Logger::getLevelName($level);},$levelsArray );
$levelsList = array_combine( $levelsArray, $levelsLabels  );

?>

<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel, 
        'columns' => [
            'id',
            [
                'attribute' => 'log_time',
                'content' => function($model, $key, $index, $column){
                    $splitSeconds = explode(".", $model->log_time);
                    $formatter = new Formatter;
                    return $formatter->asDatetime($splitSeconds[0]);
                }
            ],
            [
                'attribute' => 'category',
                'filter' => $categoriesList,
            ],
            [
                'attribute' => 'level',
                'filter' => $levelsList,
                'value' => function($model, $key, $index, $column){
                    return Logger::getLevelName($model->level);
                }
            ],

            'prefix:ntext',
            [
                'attribute' => 'message',
                'content' => function($model, $key, $index, $column){
                    $formatter = new Formatter;
                    $text =  substr($model->message, 0, 120);
                    $out = "<samp>" . $formatter->asNtext($text) . "</samp>";
                    $out .= (strlen($model->message) != strlen($text)) ? " " . Html::a('[...]', Url::to(['/log/browse/view', 'id' => $model->id])) : "";
                    return $out ;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => "{view} {delete}"
            ],
        ],
        'rowOptions' => function ($model, $key, $index, $grid){
            return [
                'class' => Logger::getLevelName($model->level)
            ];
        }
    ]); ?>

</div>
