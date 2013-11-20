<?php

app::uses('Component', 'Controller');
class Wisapp02Component extends Component {
	
	
    public function display($id) {
		
		$model = ClassRegistry::init('Example');
		$result = $model->find('all', array('conditions'=>array('Example.description LIKE'=> '%'.$id.'%')));
		
		return  $result;
		
        
    }
}