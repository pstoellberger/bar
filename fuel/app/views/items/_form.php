<?php echo Form::open(); ?>
	<p>
		<?php echo Form::label('Title', 'title'); ?>: 
<?php echo Form::input('title', Input::post('title', isset($item) ? $item->title : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Price', 'price'); ?>: 
<?php echo Form::input('price', Input::post('price', isset($item) ? $item->price : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Cost', 'cost'); ?>: 
<?php echo Form::input('cost', Input::post('cost', isset($item) ? $item->cost : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Event Price', 'event_price'); ?>: 
<?php echo Form::input('event_price', Input::post('event_price', isset($item) ? $item->event_price : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Packing Unit', 'packing_unit'); ?>: 
<?php echo Form::input('packing_unit', Input::post('packing_unit', isset($item) ? $item->packing_unit : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Max Usage', 'maxusage'); ?>: 
<?php echo Form::input('maxusage', Input::post('maxusage', isset($item) ? $item->maxusage : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Category', 'category'); ?>: 
		<?php 
		$id='none';
		if(isset($item)){$id=$item->category_id;}
		echo Form::select('category_id',$id, $categories);
	 ?>
	</p>
	<p>
		<?php echo Form::label('Status', 'status'); ?>: 
		<?php 
		$status='active';
		if(isset($item)){$status=$item->status;}
		echo Form::select('status',$status, array('active','disabled'));
	 ?>
	</p>
	<p>
		<?php echo Form::label('Custom Code', 'custom_code'); ?>: 
		<?php echo Form::input('custom_code', Input::post('custom_code', isset($item) ? $item->custom_code : '')); ?>
	</p>

	<div class="well">
	<?php echo Form::submit('save','Save',array('class'=>'btn primary')); ?>	
	<?php echo Html::anchor('admin/items', 'Back',array('class'=>'btn')); ?>
<!--
	<?php if(isset($item)){
		echo Html::anchor('admin/items/delete/'.$item->id, 'delete', array('class'=>'danger btn pull-right item-delete-btn','onclick' => "return confirm('Are you sure?')")); 
	
	} ?>
-->
	
	</div>

<?php echo Form::close(); ?>