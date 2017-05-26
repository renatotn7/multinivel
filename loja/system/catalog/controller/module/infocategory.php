<?php  
class ControllerModuleInfocategory extends Controller {
	protected $category_id = 0;
	protected $path = array();
	
	protected function index() {
		$this->language->load('module/infocategory');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/infocategory');
		$this->load->model('tool/seo_url');
		
		if (isset($this->request->get['path'])) {
			$this->path = explode('_', $this->request->get['path']);
			
			$this->category_id = end($this->path);
		}
		$this->data['infocategory'] = $this->getCategories(0);
												
		$this->id = 'infocategory';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/infocategory.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/infocategory.tpl';
		} else {
			$this->template = 'default/template/module/infocategory.tpl';
		}
		
		$this->render();
  	}
	
	protected function getCategories($parent_id, $current_path = '') {
		$category_id = array_shift($this->path);
		
		$output = '';
		
		$results = $this->model_catalog_infocategory->getCategories($parent_id);
		
		if ($results) { 
			$output .= '<ul>';
    	}
		$route = '';
		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		}
		foreach ($results as $result) {	
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}
			
			$output .= '<li>';
			
			$children = '';
			if ($category_id == $result['category_id'] and $route == 'information/infocategory') {
				$children = $this->getCategories($result['category_id'], $new_path);
			}
			
			if ($this->category_id == $result['category_id'] and $route == 'information/infocategory') {
				$output .= '<a href="' . $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&amp;path=' . $new_path,true)  . '"><b>' . $result['name'] . '</b></a>';
			} else {
				$output .= '<a href="' . $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&amp;path=' . $new_path,true)  . '">' . $result['name'] . '</a>';
			}
			
        	$output .= $children;
        
        	$output .= '</li>'; 
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
	}		
}
?>