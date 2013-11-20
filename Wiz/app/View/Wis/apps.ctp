


	<?php /*echo $this->Html->link('Create App', array("controller"=>"wis", "action"=>"create_app", $user['id']));  */ echo $this->Html->link('Add WisApp', array("controller"=>"wis", "action"=>"add_app", $user['id']));?> </br></br></br>
	
	<?php 
	
		if(isset($waki[0]['Wisapp']) )
		foreach($waki[0]['Wisapp'] as $wisapp){
		
			echo $this->Html->link( $wisapp['name'], array("controller"=>"wis", "action"=>"app", $wisapp['id']));
			echo '</br></br>';
		}
	
	?>
	