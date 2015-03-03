<h2>
<?php echo Html::anchor('admin/events/create', 'Add new Event',array("class"=>"btn primary")); ?>

<?php echo Html::anchor('admin/events/updateall', 'Update Consumption Count',array('class'=>'btn primary')); ?>


</h2>
<h1>Active Events</h1>
<table  >
	<tr>
		<th>Title</th>
		<th>Event Date</th>
		<th>Updated At</th>
		<th>Saldo</th>
		<th>Sponsoring</th>
		<th></th>
	</tr>

	<?php 
		$total=0;
	foreach ($events as $event): ?>	<tr class="event">
		<?php $total+=$event->saldo; ?>
		<td><?php echo $event->title; ?></td>
		<td><?php echo $event->event_date; ?></td>
		<td><?php 
			if($event->updated_at>0){
			 echo date("d.m.Y H:i",$event->updated_at);
			}else{
			 echo "-";	
			}
		 ?></td>
		 
		<td><?php echo $event->saldo; ?>€</td> 
		<td><?php echo $event->sponsoring; ?>€</td> 
		<td>
			<?php echo Html::anchor('admin/events/view/'.$event->id, 'View'); ?> |
			<?php echo Html::anchor('admin/events/edit/'.$event->id, 'Edit'); ?> |
			<?php echo Html::anchor('admin/events/delete/'.$event->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>		</td>
	</tr>
	<?php endforeach; ?></table>

<br /><br />

<h1>Done Events</h1>
<table  >
	<tr>
		<th>Title</th>
		<th>Event Date</th>
		<th>Updated At</th>
		<th>Saldo</th>
		<th>Sponsoring</th>
		<th></th>
	</tr>

	<?php 
		$total=0;
	foreach ($inactiveevents as $event): ?>	<tr class="event">
		<?php $total+=$event->saldo; ?>
		<td><?php echo $event->title; ?></td>
		<td><?php echo $event->event_date; ?></td>
		<td><?php 
			if($event->updated_at>0){
			 echo date("d.m.Y H:i",$event->updated_at);
			}else{
			 echo "-";	
			}
		 ?></td>
		 
		<td><?php echo $event->saldo; ?>€</td> 
		<td><?php echo $event->sponsoring; ?>€</td> 
		<td>
			<?php echo Html::anchor('admin/events/view/'.$event->id, 'View'); ?> |
			<?php echo Html::anchor('admin/events/edit/'.$event->id, 'Edit'); ?> |
			<?php echo Html::anchor('admin/events/delete/'.$event->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>		</td>
	</tr>
	<?php endforeach; ?></table>

<br /><br />

