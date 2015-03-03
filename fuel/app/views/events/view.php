<p>
	<strong>Title:</strong>
	<?php echo $event->title; ?></p>
<p>
	<strong>Saldo:</strong>
	<?php echo $event->saldo; ?></p>
<p>
	<strong>Sponsoring:</strong>
	<?php echo $event->sponsoring; ?></p>
<p>
	<strong>Updated At:</strong>
	<?php echo date("d.m.Y h:i",$event->updated_at); ?></p>
<p>
<?php echo Html::anchor('admin/events/edit/'.$event->id, 'Edit',array('class'=>'btn')); ?>

<?php 
$rows="";
$total=0;
$orderId=-1;
$orders=0;
$time=0;
if(sizeof($consumptions)>0){

$time=floor((end($consumptions)->created_at-$consumptions[0]->created_at)/(3600*24))+1;

foreach($consumptions as $consumption){
	if($consumption->order_id!=$orderId){
		$orders++;
		$orderId=$consumption->order_id;
	}
	$total+=$consumption->price;
	$rows.="<tr>
	<td>".$consumption->title."</td>
	<td>".$consumption->price."€</td>
	<td>".date("d.m.Y h:i",$consumption->created_at)."</td>
	<td>".$consumption->order_id."</td> 
	<td>".Html::anchor('admin/events/removeconsumption/'.$event->id."/".$consumption->id, 'Remove')."</td>
	<td>".Html::anchor('admin/events/payconsumption/'.$event->id."/".$consumption->id, 'Mark paid')."</td>
	</tr>";	
}
}
?>

<div class="well">
<span class="total"><?php echo $total ?>€</span>
<?php echo Html::anchor('admin/events/payment/'.$event->id, 'Mark consumptions as paid',array('class'=>'btn danger')); ?>

</div>

<table>
<tr>
	<th>Item</th>
	<th>Total (<?php echo $total; ?>€)</th>
	<th>Time (<?php echo $time." day"; if($time!=1){echo "s";} ?>)</th>
	<th>Order Id (<?php echo $orders." order"; if($orders!=1){echo "s";} ?>)</th>
	<th></th>
	<th></th>
</tr>
<?php
echo $rows
?>
 </table>
</p>

