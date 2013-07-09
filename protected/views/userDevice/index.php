<?php
/* @var $this UserDeviceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Devices',
);

$this->menu=array(
	array('label'=>'Create UserDevice', 'url'=>array('create')),
	array('label'=>'Manage UserDevice', 'url'=>array('admin')),
);
?>

<h1>User Devices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
