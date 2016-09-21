<?php

use yii\bootstrap\ActiveField;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\i18n\Formatter;
use yii\log\Logger;
use yii\web\JsExpression;
use yii\web\View;
use trntv\yii\datetime\DateTimeWidget;
use yii\helpers\FormatConverter;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logs');
$this->params['breadcrumbs'][] = $this->title;

$categoriesArray = ArrayHelper::getColumn($categories, 'category');
$categoriesList = array_merge([''=>''], array_combine( $categoriesArray, $categoriesArray  ));
$levelsArray = ArrayHelper::getColumn($levels, 'level');
$levelsLabels = array_map( function($level){ return Logger::getLevelName($level);},$levelsArray );
$levelsList = array_merge([''=>''], array_combine( $levelsArray, $levelsLabels  ));

$this->registerJs("minDate = " . new JsExpression('new Date(' . number_format($minDate, 3, "", "") . ')') . ";", View::POS_HEAD);
$this->registerJs("maxDate = " . new JsExpression('new Date(' . number_format($maxDate, 3, "", "") . ')') . ";", View::POS_HEAD);

?>
<div class="log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'layout' => 'horizontal'
    ]); ?>
    <div class="row">
    <?= $form->field($searchModel, 'level', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']])->dropDownList($levelsList) ?>
    <?= $form->field($searchModel, 'category', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']])->dropDownList($categoriesList) ?>
    <?= $form->field($searchModel, 'log_time_begin')->label(false)->hiddenInput() ?>
    <?= $form->field(
        $searchModel, 
        'log_time_begin_datepicker', 
        [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-6'
            ]
        ])->widget(
            'trntv\yii\datetime\DateTimeWidget',
            [
                'id' => 'logsearch-log_time_begin_widget',
                'showInputAddon' => false,
                'momentDatetimeFormat' => 'YYYY-MM-DDTHH:mm:ssZ',
                'clientEvents' => [
                    'dp.change' => 'function(e){
                        console.log(e.date);
                        if ( e.date === false ){
                            $("#logsearch-log_time_begin").val("");
                            $("#logsearch-log_time_end_widget").data("DateTimePicker").minDate("' . date( "c", $minDate) . '");
                        } else {
                            $("#logsearch-log_time_begin").val(e.date.unix());
                            $("#logsearch-log_time_end_widget").data("DateTimePicker").minDate(e.date);
                        }
                    }',
                ],
                'clientOptions' => [
                    'minDate' => new JsExpression('new Date(' . number_format($minDate, 3, "", "") . ')'),
                    'maxDate' =>  new JsExpression('new Date(' . number_format($maxDate, 3, "", "") . ')'),
                    'allowInputToggle' => true,
                    'sideBySide' => true,
                    'useCurrent' => false,
                    'format' => 'YYYY-MM-DDTHH:mm:ssZ',

                ]
            ]
        ); ?>
    <?= $form->field($searchModel, 'log_time_end' )->label(false)->hiddenInput() ?>
    <?= $form->field(
        $searchModel, 
        'log_time_end_datepicker', 
        [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-6'
            ]
        ])->widget(
            'trntv\yii\datetime\DateTimeWidget',
            [
                'id' => 'logsearch-log_time_end_widget',
                'showInputAddon' => false,
                'momentDatetimeFormat' => 'YYYY-MM-DDTHH:mm:ssZ',
                'clientEvents' => [
                    'dp.change' => 'function(e){
                        console.log(e.date);
                        if ( e.date === false ){ 
                            $("#logsearch-log_time_end").val("");
                            $("#logsearch-log_time_begin_widget").data("DateTimePicker").maxDate("' .  date( "c", $maxDate) . '");
                        } else {
                            $("#logsearch-log_time_end").val(e.date.unix());
                            $("#logsearch-log_time_begin_widget").data("DateTimePicker").maxDate(e.date);
                        }
                    }',
                ],
                'clientOptions' => [
                    'minDate' => new JsExpression('new Date(' . number_format($minDate, 3, "", "") . ')'),
                    'maxDate' => new JsExpression('new Date(' . number_format($maxDate, 3, "", "") . ')'),
                    'allowInputToggle' => true,
                    'sideBySide' => true,
                    'useCurrent' => false,
                    'format' => 'YYYY-MM-DDTHH:mm:ssZ',
                ]
            ]
        ); ?>
    <?= $form->field($searchModel, 'prefix', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']]) ?>
    <?= $form->field($searchModel, 'message', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel, 
        'columns' => [
            'id',
            [
                'attribute' => 'log_time',
                'content' => function($model, $key, $index, $column){
                    $splitSeconds = explode(".", $model->log_time);
                    $formatter = new Formatter;
                    return $formatter->asDatetime($splitSeconds[0]);
                },
            ],
            [
                'attribute' => 'category',
            ],
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
