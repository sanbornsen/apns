<?php
/* @var $this NotificationController */
/* @var $model Notification */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notification-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'notification_body'); ?>
		<?php echo $form->textArea($model,'notification_body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'notification_body'); ?>
	</div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Push' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
