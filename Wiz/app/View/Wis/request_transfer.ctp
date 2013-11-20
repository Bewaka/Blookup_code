<?php
echo $this->Html->link('English', array('language'=>'eng'));

echo $this->Html->link('Français', array('language'=>'fra'));
?>
<h1> Request Transfer </h1>

<?php $home =  __('Home'); ?>
<?php echo $this->Html->link( $home , array("controller"=>"wakis", "action"=>"index")); ?>
<?php
	
echo $this->Form->create('Requests');

echo $this->Form->input('waki_id', array("type"=>"hidden", "value"=>$user['id']));

echo $this->Form->input('request_date', array("type"=>"hidden"));

$options = array( 1 => 'WisApp Dev', 2 => 'Credit', 3 => 'None' );

echo $this->Form->input('type', array(
'options' => $options,
'empty' => '(choose one)',
'label' => 'Select Reason'
));

echo $this->Form->input('amount');

echo $this->Form->end('Request');


	
	
	
?>