<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11/16/2017
 * Time: 11:41 PM
 */

namespace app\components;


use app\models\UserCreator;
use dektrium\user\models\Profile;
use dektrium\user\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class UserHierarchyRetriever {

	private $user_id;
	private $leadCreatorIdCollection;

	/**
	 * UserHierarchyRetriever constructor.
	 *
	 * @param $user_id
	 */
	public function __construct() {

	}

	public function retrieve($user_id) {
		/* @var $user User */
		/* @var $profile Profile */

		if ( $this->userExists( $user_id ) ) {
			if ( UserCreator::isCreator( $user_id ) ) {
				// get the user that this guy created
				$userCreationsCollection = UserCreator::getCreatedUsers( $user_id );
				foreach ( $userCreationsCollection as $currentCreatedUser ) {
					$this->retrieve( $currentCreatedUser->agent_id );//pass the id of user that this user created
				}
			}
			$user    = $this->findUser( $user_id );
			$profile = $user->getProfile()->one();

			$roles    = Yii::$app->authManager->getRolesByUser( $user->id );
			$rolesArr = [];
			foreach ( $roles as $currentRole ) {
				$rolesArr[] = $currentRole->name;
			}
			$userRole                        = implode( ',', $rolesArr );
			$this->leadCreatorIdCollection[] = [
				'name' => $profile->name,
				'role' => $userRole
			];
		} else {
			throw new NotFoundHttpException( "This user doesnt exists" );
		}
		return $this->leadCreatorIdCollection;
	}

	/**
	 * @param $user_id integer
	 */
	private function findUser( $user_id ) {
		$user_id = intval( $user_id );

		return User::findOne( $user_id );
	}

	private function userExists( $user_id ) {
		$user_id = intval( $user_id );

		return User::find()->where( [ 'id' => $user_id ] )->exists();
	}

	/**
	 * @return mixed
	 */
	public function getUserId() {
		return $this->user_id;
	}

	/**
	 * @param mixed $user_id
	 */
	public function setUserId( $user_id ) {
		$this->user_id = $user_id;
	}

	/**
	 * @return mixed
	 */
	public function getLeadCreatorIdCollection() {
		return $this->leadCreatorIdCollection;
	}

	/**
	 * @param mixed $leadCreatorIdCollection
	 */
	public function setLeadCreatorIdCollection( $leadCreatorIdCollection ) {
		$this->leadCreatorIdCollection = $leadCreatorIdCollection;
	}


}