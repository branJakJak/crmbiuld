<?php
/* @var $this yii\web\View */
$this->title = "Users created by $userCreator";
?>
<h1>Users created by <?php echo $userCreator ?></h1>
<hr>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Users</h3>
	</div>
	<div class="panel-body">

		<table class="table table-hover table-bordered table-condensed">
			<thead>
				<tr>
					<th>Name</th>
					<th>Role</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($userHierarchy as $currentRecord): ?>
				<tr>
					<td>
						<?php echo $currentRecord['name'] ?>
					</td>
					<td>
						<?php echo $currentRecord['role'] ?>
					</td>

				</tr>
				<?php endforeach;?>

			</tbody>
		</table>

	</div>
</div>
