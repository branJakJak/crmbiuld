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
use Tree\Node\Node;
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

	/**
	 * @param $user_id integer
	 * @param $parentNode Node
	 *
	 * @return array
	 * @throws NotFoundHttpException
	 */
	public function retrieve( $user_id, $parentNode ) {
		/* @var $user User */
		/* @var $profile Profile */
		/* @var $currentCreatedUser UserCreator */

		if ( $this->userExists( $user_id ) ) {
			$user    = $this->findUser( $user_id );
			$newNode = new Node();
			$newNode->setValue( [
				'name' => $this->getName($user),
				'role' => $this->getRole( $user->id ),
			]);
			$parentNode->addChild( $newNode );
			$userCreationsCollection = UserCreator::getCreatedUsers( $user_id );
			foreach ($userCreationsCollection as $currentCreatedUser) {
				$this->retrieve( $currentCreatedUser->agent_id, $newNode );
			}
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

	public function getRole( $id ) {
		$roles    = Yii::$app->authManager->getRolesByUser($id);
		$rolesArr = [];
		foreach ( $roles as $currentRole ) {
			$rolesArr[] = $currentRole->name;
		}
		return implode( ',', $rolesArr );
	}

	public function getName( $user) {
		$profile = $user->getProfile()->one();
		return $profile->name;
	}


}