<?php
echo $this->Html->link('English', array('language'=>'eng'));

echo $this->Html->link('Français', array('language'=>'fra'));
?>

<h1> <?php echo __('Edit Profile'); ?></h1>
<div style="float:right;">
	<?php if(!isset($user)) echo $this->Html->link(__('Login'), array("controller"=>"wis", "action"=>"login")); else echo $this->Html->link(__('Logout'), array("controller"=>"wakis", "action"=>"logout"));
	?>
	
	<?php $home =  __('Home'); ?>
	<?php echo $this->Html->link( $home , array("controller"=>"wis", "action"=>"index")); ?>
</div>
<?php

	echo $this->Form->create('Wis');
	
	$options = array( 1 => 'WakiDat', 2 => 'WakiDev', 3 => __('Both') );
	
	if(isset($user) && $user['type'] == 4)
		$options[4] = __('Admin');
	
	echo $this->Form->input('type', array(
	'options' => $options,
	'empty' => __('(choose one)'),
	'label' => __('Select User Type')
	));
	
	echo $this->Form->input('first_name', array('label'=>__('First Name')));
	echo $this->Form->input('last_name', array('label'=>__('Last Name')));
	echo $this->Form->input('id',array("type"=>"hidden"));
	echo $this->Form->input('city', array('label'=>__('City')));
	echo $this->Form->input('phone', array('label'=>__('Phone Number')));
	
	
	echo $this->Form->end(__('Update'));

?>