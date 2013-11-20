





	<h1>Create New WisApp</h1></br>
	
	<?php
	
	$options = array( 1 => 'Online', 2 => 'Offline');
	
	echo $this->Form->create('Wisapp', array('enctype' => 'multipart/form-data'));
	
	echo $this->Form->input('name');
	
	echo $this->Form->input('description');
	
	echo $this->Form->input("tags");
	
	echo $this->Form->input("version");
	
	echo $this->Form->input('status', array(
	'options' => $options,
	'empty' => __('(choose one)'),
	'label' => __('Select Status')
	));
	
	echo $this->Form->file('logo', array("label"=>"Logo"));
	
	echo $this->Form->end('Create');
	
	
	?>