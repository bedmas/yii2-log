<?php
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\web\JsExpression;
use yii\web\View;
use yii\bootstrap\ActiveField;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use dosamigos\datetimepicker\DateTimePicker;


?>
<div class="log-search">
<?php
$categoriesArray = ArrayHelper::getColumn($categories, 'category');
$categoriesList = [''=>''] + array_combine( $categoriesArray, $categoriesArray  );
$levelsArray = ArrayHelper::getColumn($levels, 'level');
$levelsLabels = array_map( function($level){ return Logger::getLevelName($level);},$levelsArray );
$levelsList = [''=>''] + array_combine( $levelsArray, $levelsLabels  );

$this->registerJs("minDate = " . new JsExpression('new Date(' . number_format($minDate, 3, "", "") . ')') . ";", View::POS_HEAD);
$this->registerJs("maxDate = " . new JsExpression('new Date(' . number_format($maxDate, 3, "", "") . ')') . ";", View::POS_HEAD);


?>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'layout' => 'horizontal'
    ]); ?>
    <div class="row">
    <?= $form->field($searchModel, 'level', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']])->dropDownList($levelsList) ?>
    <?= $form->field($searchModel, 'category', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']])->dropDownList($categoriesList) ?>
    <?= $form->field($searchModel, 'log_time_begin')->widget(DateTimePicker::className(), [
        'clientOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
        ],
        'clientEvents' => [
            'changeDate' => 'function(e){                
                console.log(e.date.valueOf());
                jQuery("#logsearch-log_time_end").parent().datetimepicker("setStartDate", e.date);
            }',
        ]
    ]);?>

    <?= $form->field($searchModel, 'log_time_end')->widget(DateTimePicker::className(), [
        'clientOptions' => [
            'format' => 'yyyy-mm-dd hh:ii', 
        ],
        'clientEvents' => [
            'changeDate' => 'function(e){
                console.log(e.date.valueOf());
                jQuery("#logsearch-log_time_begin").parent().datetimepicker("setEndDate", e.date);
            }',
        ]
    ]);?>


    <?= $form->field($searchModel, 'prefix', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']]) ?>
    <?= $form->field($searchModel, 'message', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

