<?php

app::uses('Component', 'Controller');
class Wisapp01Component extends Component {
	
	
    public function display($id) {
		
		$model = ClassRegistry::init('Example');
		$result = $model->find('all', array('conditions'=>array('id'=>$id)));
		$other = $model->find('all', array('conditions'=>array('NOT'=>array('id'=>$id))));
		
		$results = array();
		
		$results['main'] = $result;
		$results['other'] = $other;
		
		return  $results;
		
        
    }
}