<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 9/7/2017
 * Time: 12:42 AM
 */

namespace app\components;


use app\models\UserCreator;
use dektrium\user\models\User;

class LeadCreatorRetriever
{
    protected $leadCreatorIdCollection;

    /**
     * LeadCreatorRetriever constructor.
     */
    public function __construct()
    {
        $this->leadCreatorIdCollection = [];
    }

    /**
     * Retrieves id of users together with its subordinates.
     * @param $user_id
     * @return array
     * @internal param User $user
     */
    public function retrieve($user_id){
        if(UserCreator::isCreator($user_id)){
            // get the user that this guy created
            $userCreationsCollection = UserCreator::getCreatedUsers($user_id);
            foreach ($userCreationsCollection as $currentCreatedUser) {
                $this->retrieve($currentCreatedUser->agent_id);//pass the id of user that this user created
            }
        }
        $this->leadCreatorIdCollection[] = $user_id;
    }

    /**
     * @return mixed
     */
    public function getLeadCreatorIdCollection()
    {
        return $this->leadCreatorIdCollection;
    }

    /**
     * @param mixed $leadCreatorIdCollection
     */
    public function setLeadCreatorIdCollection($leadCreatorIdCollection)
    {
        $this->leadCreatorIdCollection = $leadCreatorIdCollection;
    }

}