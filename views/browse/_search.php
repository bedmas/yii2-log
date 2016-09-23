<?php
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\web\JsExpression;
use yii\web\View;
use yii\bootstrap\ActiveField;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


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
<pre>
<?php
echo $searchModel->log_time_begin;
?>
<?php
echo $searchModel->log_time_end;
?>


</pre>



    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'layout' => 'horizontal'
    ]); ?>
    <div class="row">
    <?= $form->field($searchModel, 'level', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']])->dropDownList($levelsList) ?>
    <?= $form->field($searchModel, 'category', ['horizontalCssClasses' => ['wrapper' => 'col-sm-6']])->dropDownList($categoriesList) ?>
    <?= $form->field(
        $searchModel, 
        'log_time_begin', 
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
                        if ( e.date === false ){
                            $("#logsearch-log_time_end_widget").data("DateTimePicker").minDate("' . date( "c", $minDate) . '");
                        } else {
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
                    'defaultDate' => ($searchModel->log_time_begin) ?  $searchModel->log_time_begin : false,
                ]
            ]
        ); ?>
    <?= $form->field(
        $searchModel, 
        'log_time_end', 
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
                        if ( e.date === false ){ 
                            $("#logsearch-log_time_begin_widget").data("DateTimePicker").maxDate("' .  date( "c", $maxDate) . '");
                        } else {
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
                    'defaultDate' => ($searchModel->log_time_end) ? $searchModel->log_time_end : false,
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

