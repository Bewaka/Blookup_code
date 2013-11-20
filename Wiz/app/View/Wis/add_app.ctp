





	<h1>Create New WisApp</h1></br>
	
	<?php
		
	echo $this->Form->create('Wisapp', array('enctype' => 'multipart/form-data'));
	
	echo $this->Form->file('file', array("label"=>"Data Files"));
	
	echo $this->Form->end('Upload');
	
	
	?>