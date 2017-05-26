<?php
/******************************************************
 * @package Pav Megamenu module for Opencart 1.5.x
 * @version 1.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

/**
 * class ControllerModulePavmegamenu 
 */
class ControllerModulePavmegamenu extends Controller {


	private $error = array(); 
	
	public function index() {   
		
		$this->language->load('module/pavmegamenu');
		
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/pavmegamenu.css');
 
		$this->document->addScript('view/javascript/pavmegamenu/jquery.nestable.js');
		$this->load->model('menu/megamenu');
		// check tables created or not
		$this->model_menu_megamenu->install();
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST')  && !empty($this->request->post) ) {
				
				$this->load->model('menu/megamenu');
				$data = array();
				$data['pavmegamenu_module'] = $this->request->post["pavmegamenu_module"];
				
				$this->model_setting_setting->editSetting('pavmegamenu', $data);	
				
				$this->request->post['megamenu']['position'] = '99';
				if(  $this->validate() ) {
					$id = $this->model_menu_megamenu->editData( $this->request->post );				
				}
				
			$this->session->data['success'] = $this->language->get('text_success');
			if( $this->request->post['save_mode']=='save-edit'){
				$this->redirect($this->url->link('module/pavmegamenu', 'id='.$id.'&token=' . $this->session->data['token'], 'SSL'));
			}	else {
				$this->redirect($this->url->link('module/pavmegamenu', 'token=' . $this->session->data['token'], 'SSL'));
			}

			
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');

		$this->data['entry_banner'] = $this->language->get('entry_banner');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension'); 
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['positions'] = array( 'mainmenu',
										  'slideshow',
										  'promotion',
										  'content_top',
										  'column_left',
										  'column_right',
										  'content_bottom',
										  'footer_top',
										  'footer_center',
										  'footer_bottom'
		);
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['dimension'])) {
			$this->data['error_dimension'] = $this->error['dimension'];
		} else {
			$this->data['error_dimension'] = array();
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/pavmegamenu', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/pavmegamenu', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['actionGetTree'] = $this->url->link('module/pavmegamenu/gettree', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['actionDel'] = $this->url->link('module/pavmegamenu/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['actionGetInfo'] = $this->url->link('module/pavmegamenu/info', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['updateTree'] = $this->url->link('module/pavmegamenu/update', 'root=1&token=' . $this->session->data['token'], 'SSL');
	
		
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['pavmegamenu_module'])) {
			$this->data['modules'] = $this->request->post['pavmegamenu_module'];
		} elseif ($this->config->get('pavmegamenu_module')) { 
			$this->data['modules'] = $this->config->get('pavmegamenu_module');
		}	
		$tmp = array('layout_id'=>'','position'=>'','status'=>'','sort_order'=>'');				
		if( count($this->data['modules']) ){
			$tmp = array_merge($tmp, $this->data['modules'][0] );
		}
		$this->data['module'] = $tmp;
		$this->load->model('design/layout');
		
		

		$this->data['tree'] = $this->model_menu_megamenu->getTree(  );
		
		$this->info();
		$this->data['layouts'] = array();
		$this->data['layouts'][] = array('layout_id'=>99999, 'name' => $this->language->get('all_page') );
		
		$this->data['layouts'] = array_merge($this->data['layouts'],$this->model_design_layout->getLayouts());

		$this->load->model('design/banner');
		
		$this->data['banners'] = $this->model_design_banner->getBanners();
		
		$this->template = 'module/pavmegamenu/pavmegamenu.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function delete(){
		if( isset($this->request->get['id']) ){
			$this->load->model('menu/megamenu');
			$this->model_menu_megamenu->delete( (int)$this->request->get['id'] );
			
		}
		$this->redirect($this->url->link('module/pavmegamenu', 'token=' . $this->session->data['token'], 'SSL'));
	}
	public function update(){
		$data =  ( ($this->request->post['list']) );
		$root = $this->request->get['root'];
	
		$this->load->model('menu/megamenu');
		$this->model_menu_megamenu->massUpdate( $data, $root  );
		
 
	// 	echo json_decode( $this->request->post['data'] ); die;
	}
	public function info(){
		$id=0;
		if( isset($this->request->post) && isset($this->request->post['id']) ){
			$id = (int)$this->request->post['id'] ;
		}else if( isset($this->request->get["id"]) ){
			$id = (int)$this->request->get['id'];
		}
		$default = array(
			'megamenu_id'=>'',
			'title' => '',
			'parent_id'=> '',
			'image' => '',
			'is_group'=>'',
			'width'=>'12',
			'menu_class'=>'',
			'submenu_colum_width'=>'',
			'is_group'=>'',
			'submenu_width'=>'12',
			'column_width'=>'200',
			'submenu_column_width'=>'',
			'colums'=>'1',
			'type' => '',
			'item' => '',
			'is_content'=>'',
			'show_title'=>'1',
			'type_submenu'=>'',
			'level_depth'=>'',
			'status'    => '',
			'position'  => '',
			'show_sub' => '',
			'url' => '',
			'targer' => '',
			'level'=> '',
			'content_text'=>'',
			'submenu_content'=>'',
			'megamenu-information'=>'',
			'megamenu-product'=>'',
			'megamenu-category'=>'',
			'published' => 1,
			'megamenu-manufacturer'=>''
		);
		
		$this->language->load('module/pavmegamenu');
		$this->load->model('menu/megamenu');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/information');
		$this->load->model('localisation/language');
		$this->load->model('tool/image');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 16, 16);
	
		$this->data['entry_image'] = 'Image:';
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_clear'] = $this->language->get('text_clear');		
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['tab_module'] = $this->language->get('tab_module');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['yesno'] = array( '0' => $this->language->get('text_no'),'1'=> $this->language->get('text_yes') );
		$this->data['token'] = $this->session->data['token'];
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['informations'] = $this->model_catalog_information->getInformations();
		
		$menu = $this->model_menu_megamenu->getInfo( $id );
		$menu = array_merge( $default, $menu );
		
		
		
		$this->data['menu'] = $menu;  
		$this->data['menus'] = $this->model_menu_megamenu->getDropdown(null, $menu['parent_id'] );
		$this->data['thumb'] = $this->model_tool_image->resize($menu['image'], 32, 32);
		$this->data['menu_description'] = array();
		$descriptions  = $this->model_menu_megamenu->getMenuDescription( $id );
		$this->data['menu_description'] = array();
		
		$this->data['megamenutypes'] = array(
			'url' => 'URL',
			'category' => 'Category',
			'information' => 'information',
			'product' => 'Product',
			'manufacturer' => 'Manufacturer',
			'html'  => "HTML"
		);
		
		if( $menu['item'] ){
			switch( $menu['type'] ){
				case 'category':
					$category = $this->model_catalog_category->getCategory( $menu['item'] );
					$menu['megamenu-category'] = isset($category['name'])?$category['name']:"";
					
					break;
				case 'product':
					$product = $this->model_catalog_product->getProduct( $menu['item'] );
					$menu['megamenu-product'] = isset($product['name'])?$product['name']:"";
					break;
				case 'information':
						$menu['megamenu-information'] = $menu['item'] ;
					break;
				case 'manufacturer':
					$manufacturer = $this->model_catalog_manufacturer->getManufacturer( $menu['item'] );
					$menu['megamenu-manufacturer'] = isset($manufacturer['name'])?$manufacturer['name']:"";
					break;					
			}
		}
		foreach( $descriptions as $d ){
			$this->data['menu_description'][$d['language_id']] = $d;
		}

		if( empty($this->data['menu_description']) ){
			foreach(  $this->data['languages'] as $language ){
				$this->data['menu_description'][$language['language_id']]['title'] = '';
				$this->data['menu_description'][$language['language_id']]['description'] = '';
			}
		}
		
		if( isset($this->request->post['megamenu']) ){
			$menu = array_merge($menu, $this->request->post['megamenu'] );
		}
		$this->data['menu'] = $menu;
		
		
		$this->data['submenutypes'] = array('menu'=>'Menu', 'html'=>'HTML');
		$this->data['text_edit_menu'] = $this->language->get('text_edit_menu');
		$this->data['text_create_new'] = $this->language->get('text_create_new');
		$this->template = 'module/pavmegamenu/pavmegamenu_form.tpl';
		$this->response->setOutput($this->render());
	
	}
 
	protected function validate() {
	
		if (!$this->user->hasPermission('modify', 'module/pavmegamenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['pavmegamenu_module'])) { 
	
			foreach ($this->request->post['pavmegamenu_module'] as $key => $value) {
				if (!$value['position'] || !$value['layout_id']) { 
					$this->error['dimension'][$key] = $this->language->get('error_dimension');
				}				
			}
			$languageId = (int)$this->config->get('config_language_id');
			$d = isset($this->request->post['megamenu_description'][$languageId]['title'])?$this->request->post['megamenu_description'][$languageId]['title']:"";
			if( empty($d) ){  
				$this->error['missing_title'][]=$this->language->get('error_missing_title');
			}
			foreach ( $this->request->post['megamenu_description'] as $key => $value) {
				if( empty($value['title']) ){ 
					$this->request->post['megamenu_description'][$key]['title'] = $d; 
				}
				
			}
			if( isset($this->error['missing_title']) ){
				$this->error['warning'] = implode( "<br>", $this->error['missing_title'] );
			}
		}	
						
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>