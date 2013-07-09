<?php
/* @var $this UserDeviceController */
/* @var $model UserDevice */

$this->breadcrumbs=array(
	'User Devices'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserDevice', 'url'=>array('index')),
	array('label'=>'Create UserDevice', 'url'=>array('create')),
	array('label'=>'View UserDevice', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserDevice', 'url'=>array('admin')),
);
?>

<h1>Update UserDevice <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>