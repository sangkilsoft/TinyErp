<?php
$this->breadcrumbs=array(
	'Sinvoice Payments',
);

$this->menu=array(
	array('label'=>'Create SinvoicePayment', 'url'=>array('create')),
	array('label'=>'Manage SinvoicePayment', 'url'=>array('admin')),
);
?>

<h1>Sinvoice Payments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
