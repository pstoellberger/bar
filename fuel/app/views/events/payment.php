<h2>Are you sure</h2>
<p>Confirm that at <b> <?php echo $event ?> </b> all sponsoring has been paid for <strong><?php echo $sum ?>€</strong> for <strong><?php echo $total ?> item(s)</strong>. </p>
<?php echo Form::open(); ?>
<div class="well">
<?php echo Form::submit("submit","Yes I am sure",array("class"=>"btn danger")); ?>	
<?php echo Html::anchor($return, 'Cancel',array('class'=>'btn primary')); ?>
</div>
<?php echo Form::close(); ?>

