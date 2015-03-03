<?php echo Form::open(); ?>
	<p>
		<?php echo Form::label('Title', 'title'); ?>: 
<?php echo Form::input('title', Input::post('title', isset($event) ? $event->title : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Event Date', 'event_date'); ?>: 
<?php echo Form::input('event_date', Input::post('event_date', isset($event) ? $event->event_date : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Sponsoring', 'sponsoring'); ?>: 
<?php echo Form::input('sponsoring', Input::post('sponsoring', isset($event) ? $event->sponsoring : '')); ?>
	</p>
	<p>
		<?php echo Form::label('Status', 'status'); ?>: 
		<?php 
		$status='active';
		if(isset($event)){$status=$event->status;}
		echo Form::select('status',$status, array('active','disabled'));
	 ?>
	</p>


	<div class="well">
		<?php echo Form::submit('save','Save',array('class'=>'btn primary')); ?>	
		<?php echo Html::anchor('admin/events', 'Back',array('class'=>'btn')); ?>
	</div>

<?php echo Form::close(); ?>