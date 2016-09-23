<?php

namespace sylletka\log\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use sylletka\log\models\Log;

/**
 * LogSearch represents the model behind the search form of `sylletka\log\models\Log`.
 */
class LogSearch extends Log
{

    public $log_time_begin;
//    public $log_time_begin_datepicker;
    public $log_time_end;
//    public $log_time_end_datepicker;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'level'], 'integer'],
//            [['log_time_begin_datepicker', 'log_time_end_datepicker'], 'string'],
            [['log_time_begin', 'log_time_end', 'category', 'prefix', 'message'], 'safe'],
            [['log_time_begin', 'log_time_end'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Log::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'log_time'=>SORT_DESC
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'level' => $this->level,
//            'log_time' => $this->log_time,
            'category' => $this->category
        ]);
        if ($this->log_time_end){
            $query->andFilterWhere(['<=', 'log_time', strtotime($this->log_time_end)]);
        }
        if ($this->log_time_begin){
            $query->andFilterWhere(['>=', 'log_time', strtotime($this->log_time_begin)]);
        }

        $query->andFilterWhere(['like', 'prefix', $this->prefix])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
