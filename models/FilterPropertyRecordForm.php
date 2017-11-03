<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 3:09 AM
 */

namespace app\models;



use app\components\LeadCreatorRetriever;
use DateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class FilterPropertyRecordForm extends Model
{
    public $filterQuery;
    public $date_created;
    public $appraisal_completed;
    public $address1;
    public $postcode;
    public $insulation_type;
    public $created_by_username;
    public $current_user_logged_in;
    public $latest_note;
    public $status;
    /**
     * @var $queryObject ActiveQuery
     */
    protected $queryObject;

    public function attributeLabels()
    {
        return [
            'filterQuery' => 'Filter',
            'date_created' => 'Date created',
            'appraisal_completed' => 'Appraisal completed',
            'address1' => 'Address',
            'postcode' => 'Postcode',
            'insulation_type' => 'Insulation',
            'created_by_username' => 'Created by',
            'latest_note' => 'Latest note',
            'status' => 'Status'
        ];
    }

    public function init()
    {
        parent::init();
        $this->current_user_logged_in = \Yii::$app->user->id;
        $this->queryObject = PropertyRecord::find();
    }


    public function rules()
    {
        return [
            [['filterQuery','date_created','appraisal_completed','address1','postcode','insulation_type','created_by_username','latest_note','status'],'string'],
            ['filterQuery','safe']
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        if ($this->status === 'All Jobs') {
            $this->status='';
        }
        $this->queryObject->groupBy('tbl_property_record.id');
        $this->queryObject->leftJoin('tbl_property_notes','tbl_property_notes.property_id = tbl_property_record.id');
        $this->queryObject->leftJoin('user','user.id = tbl_property_record.created_by');
        $this->queryObject->leftJoin('profile','profile.user_id = user.id');
        if ( $this->scenario === 'quick-filter-form') {
            $this->queryObject->andFilterWhere([
                'OR',
                ['like', 'tbl_property_record.date_created', $this->filterQuery],
                ['like', 'tbl_property_record.appraisal_completed', $this->filterQuery],
                ['like', 'tbl_property_record.status', $this->filterQuery],
                ['like', 'tbl_property_record.address1', $this->filterQuery],
                ['like', 'tbl_property_record.insulation_type', $this->filterQuery],
                ['like', 'tbl_property_record.postcode', $this->filterQuery],
                ['like', 'tbl_property_notes.content', $this->filterQuery],
                ['like', 'profile.name', $this->filterQuery]
            ]);
        } else if($this->scenario === 'fine-filter-form') {
            if(isset($this->appraisal_completed) && !empty($this->appraisal_completed)){
                $tempDt = new DateTime($this->appraisal_completed);
                $this->appraisal_completed = $tempDt->format("Y-m-d");
            }
            if( isset($this->date_created) && !empty($this->date_created)){
                $tempDt = new DateTime($this->date_created);
                $this->date_created = $tempDt->format("Y-m-d");
            }
            $this->queryObject->andFilterWhere([
                'or',
                ['like', 'tbl_property_record.status', $this->status],
                ['like', 'tbl_property_record.address1', $this->address1],
                ['like', 'tbl_property_record.insulation_type', $this->insulation_type],
                ['like', 'tbl_property_record.postcode', $this->postcode],
                ['like', 'tbl_property_notes.content', $this->latest_note],
                ['like', 'profile.name', $this->created_by_username]
            ]);
            $this->queryObject->orWhere(['date(tbl_property_record.appraisal_completed)' => $this->appraisal_completed]);
            $this->queryObject->orWhere(['date(tbl_property_record.date_created)' => $this->date_created]);
        } else if( $this->scenario === 'status-filter-form') {
            if(!empty($this->status)){
                $this->queryObject->andWhere(['tbl_property_record.status'=> $this->status ] );
            }
        }
        if (!Yii::$app->user->can('Admin') &&
            !Yii::$app->user->can('Senior Manager') &&
            !Yii::$app->user->can('admin')) {

            /*created by user and all its subordinate*/
            /**
             * @var $leadCreatorRetriever LeadCreatorRetriever
             */
            $leadCreatorRetriever = \Yii::$app->leadCreatorRetriever;
            $leadCreatorRetriever->retrieve($this->current_user_logged_in);
            $leadCreatorIdCollection = $leadCreatorRetriever->getLeadCreatorIdCollection();
            $this->queryObject->andWhere(['in', 'tbl_property_record.created_by', $leadCreatorIdCollection]);
        }
        if ($this->status === '') {
            $this->status='All Jobs';
        }

        return new ActiveDataProvider(['query'=>$this->queryObject,'pagination'=>['pageSize'=>15]]);
    }

    /**
     * @return ActiveQuery
     */
    public function getQueryObject()
    {
        return $this->queryObject;
    }

    /**
     * @param mixed $queryObject
     */
    public function setQueryObject($queryObject)
    {
        $this->queryObject = $queryObject;
    }


}