


	<b><?php echo $app[0]['Wisapp']['name']; ?></b></b>
	
	<?php echo $this->Form->create('Wis', array("action"=>"upload_files")); 
	
	echo $this->Form->file('zip_file');
	
	echo $this->Form->end('Upload');
	
	echo $this->Form->input('wisapp', array("type"=>"hidden", "value"=>$app[0]['Wisapp']['id']));
	
	
	?>