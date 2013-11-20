<?php
echo $this->Html->link('English', array('language'=>'eng'));

echo $this->Html->link('Français', array('language'=>'fra'));
?>

<h1><?php echo __('Transer Requests'); ?></h1>
<?php $home =  __('Home'); ?>
<?php echo $this->Html->link( $home , array("controller"=>"wakis", "action"=>"index")); ?>

<table>
<?php


$usert = __('User');
$motif = __('Request Motif');
$amount = __('Amount');
$date = __('Date');
$transfer = __('Transfer');
echo $this->Html->tableHeaders(array($usert, $motif, $amount, $date, $transfer));

echo $this->Html->tableCells($requests);


?>
</tabel>