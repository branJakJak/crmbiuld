<?php
/* @var $this yii\web\View */
/* @var $parentNode \Tree\Node\Node */
$this->title = "User hierarchy";

/**
 * @param $node \Tree\Node\Node
 */
function printContent($node){
    /* @var $currentChild \Tree\Node\Node */
	if (!$node->isRoot()) {
		$nodeVal = $node->getValue();
		echo "<li>"  . sprintf(' <strong>%s</strong>(%s)' ,$nodeVal['name'], $nodeVal['role']) .  "</li>";
	}

	if ( count( $node->getChildren() ) > 0 ) {
		echo "<ol>";
		foreach ($node->getChildren() as $currentChild) {
			printContent( $currentChild );
		}
		echo "</ol>";
	}


}


?>
<hr>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Users</h3>
	</div>
	<div class="panel-body" id="hierarchy">
        <?php printContent($parentNode)?>
	</div>
</div>
