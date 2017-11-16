<?php


class UserHierarchyRetrieverTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
    	/*agent*/
	    $userHierarchy = new \app\components\UserHierarchyRetriever(4);
	    $returnedValue = $userHierarchy->retrieve();
	    /*should only have two*/
	    \Codeception\Util\Debug::debug( $returnedValue );
	    
    }
}