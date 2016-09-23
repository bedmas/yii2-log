<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\i18n\Formatter;
use trntv\yii\datetime\DateTimeWidget;
use yii\helpers\FormatConverter;
use yii\log\Logger;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logs');
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_search', [
        'searchModel' => $searchModel, 
        'categories' => $categories, 
        'levels' => $levels, 
        'minDate' => $minDate, 
        'maxDate' => $maxDate,
    ]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'log_time',
                'content' => function($model, $key, $index, $column){
                    return date( "c", $model->log_time);
                },
            ],
            'category',
            [
                'attribute' => 'level',
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
