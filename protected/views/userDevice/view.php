<?php
/* @var $this UserDeviceController */
/* @var $model UserDevice */

$this->breadcrumbs=array(
	'User Devices'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserDevice', 'url'=>array('index')),
	array('label'=>'Create UserDevice', 'url'=>array('create')),
	array('label'=>'Update UserDevice', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserDevice', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserDevice', 'url'=>array('admin')),
);
?>

<h1>View UserDevice #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'device_token',
		'add_date',
		'status',
	),
)); ?>
