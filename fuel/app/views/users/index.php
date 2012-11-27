<h2>
<?php echo Html::anchor('admin/users/create', 'Add new User',array("class"=>"btn primary")); ?>
</h2>

<table  >
	<tr>
		<th>Username</th>
		<th>Email</th>
		<th>Last login</th>
		<th>Unpaid</th>
		<th></th>
	</tr>

	<?php 
		$total=0;
	foreach ($users as $user): ?>	<tr class="user <?php echo $user->maxed ? 'maxed' :''; ?>">
		<?php $total+=$user->saldo; ?>
		<td><?php echo $user->username; ?></td>
		<td><?php echo $user->email; ?></td>
		<td><?php 
			if($user->last_login>0){
			 echo date("d.m.Y h:i",$user->last_login);
			}else{
			 echo "-";	
			}
		 ?></td>
		<td><?php echo $user->saldo; ?>€</td> 
		<td>
			<?php echo Html::anchor('admin/users/view/'.$user->id, 'View'); ?> |
			<?php echo Html::anchor('admin/users/edit/'.$user->id, 'Edit'); ?> |
			<?php echo Html::anchor('admin/users/delete/'.$user->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>		</td>
	</tr>
	<?php endforeach; ?></table>

<br />

