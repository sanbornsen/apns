<?php
/* @var $this UserDeviceController */
/* @var $model UserDevice */

$this->breadcrumbs=array(
	'User Devices'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserDevice', 'url'=>array('index')),
	array('label'=>'Manage UserDevice', 'url'=>array('admin')),
);
?>

<h1>Create UserDevice</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>