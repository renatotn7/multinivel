<?php
/******************************************************
 * @package Pav Megamenu module for Opencart 1.5.x
 * @version 1.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

class ModelMenuMegamenu extends Model {		
	
	private $children;
	private $shopUrl ;
	public function getChilds( $id=null ){
		$sql = ' SELECT m.*, md.title,md.description FROM ' . DB_PREFIX . 'megamenu m LEFT JOIN '
								.DB_PREFIX.'megamenu_description md ON m.megamenu_id=md.megamenu_id AND language_id='.(int)$this->config->get('config_language_id') ;
		$sql .= ' WHERE m.`published`=1 ';
		if( $id != null ) {						
			$sql .= ' AND parent_id='.(int)$id;						
		}
		$sql .= ' ORDER BY `position`  ';
		$query = $this->db->query( $sql );						
		return $query->rows;
	}
	
	public function hasChild( $id ){
		return isset($this->children[$id]);
	}	
	
	public function getNodes( $id ){
		return $this->children[$id];
	}
	
	public function getTree( $parent=1 ){
	
		$childs = $this->getChilds( null );
		foreach($childs as $child ){
			$this->children[$child['parent_id']][] = $child;	
		}
		$parent = 1 ;
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->shopUrl = $this->config->get('config_ssl') ;
		} else {
			$this->shopUrl = $this->config->get('config_url') ;
		}
	 
		if( $this->hasChild($parent) ){
			$data = $this->getNodes( $parent );
			// render menu at level 0
			$output = '<ul class="nav megamenu">';
			foreach( $data as $menu ){
				if( $this->hasChild($menu['megamenu_id']) || $menu['type_submenu'] == 'html'){
					$output .= '<li class="parent dropdown '.$menu['menu_class'].'">
					<a class="dropdown-toggle" data-toggle="dropdown" href="'.$this->getLink( $menu ).'">';
					
					if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
					
					$output .= '<span class="menu-title">'.$menu['title']."</span>";
					if( $menu['description'] ){
						$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
					}
					$output .= "<b class=\"caret\"></b></a>";
					if( $menu['image']){  $output .= '</span>'; }
					
					$output .= $this->genTree( $menu['megamenu_id'], 1, $menu );
					$output .= '</li>';
				}else {
					$output .= '<li class="'.$menu['menu_class'].'">
					<a href="'.$this->getLink( $menu ).'">';
					
					if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
					
					$output .= '<span class="menu-title">'.$menu['title']."</span>";
					if( $menu['description'] ){
						$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
					}
					if( $menu['image']){ $output .= '</span>';	}
					$output .= '</a></li>';
				}
			}
			$output .= '</ul>';
			
		}

		 return $output;
	
	}
	
	public function getColumnSpans(){
	
	}
	
	
	public function genTree( $parentId, $level,$parent ){
		$class = $parent['is_group']?"dropdown-mega":"dropdown-menu";
		
		if( $parent['type_submenu'] == 'html' ){
			$output = '<div class="'.$class.'"><div class="menu-content">';
			$output .= html_entity_decode($parent['submenu_content']);
			$output .= '</div></div>';
			return $output;
		}elseif( $this->hasChild($parentId) ){
			$data = $this->getNodes( $parentId );			
			$parent['colums'] = (int)$parent['colums'];
			if( $parent['colums'] > 1  ){
				$output = '<div class="'.$class.' menu-content mega-cols cols'.$parent['colums'].'"><div class="row-fluid">';
				$cols = array_chunk( $data, ceil(count($data)/$parent['colums'])  );
				$oSpans = $this->getColWidth( $parent, (int)$parent['colums'] );
			
				foreach( $cols as $i =>  $menus ){

					$output .='<div class="mega-col '.$oSpans[$i+1].' col-'.($i+1).'"><ul>';
						foreach( $menus as $menu ) {
							$output .= $this->renderMenuContent( $menu );
						}
					$output .='</ul></div>';
				}
				$output .= '</div></div>';
				return $output;
			}else {
				$output = '<ul class="'.$class.' level'.$level.'">';

				foreach( $data as $menu ){
					$output .= $this->renderMenuContent( $menu );
				}	
				
				$output .= '</ul>';
			}
			return $output;
		}
		return ;
	}
	
	public function renderMenuContent( $menu ){
		
		$output = '';
		$class = $menu['is_group']?"mega-group":"";
		
		
		$menu['menu_class'] = ' '.$class;
		if( $menu['type'] == 'html' ){ 
			$output .= '<li class="'.$menu['menu_class'].'">';	
			$output .= '<div class="menu-content">'.html_entity_decode($menu['content_text']).'</div>'; 
			$output .= '</li>';
			return $output;
		}
		if( $this->hasChild($menu['megamenu_id']) ){
			$output .= '<li class="parent dropdown-submenu'.$menu['menu_class'].'">';
			if( $menu['show_title'] ){
				$output .= '<a class="dropdown-toggle" data-toggle="dropdown" href="'.$this->getLink( $menu ).'">';
				$t = '%s';
				if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
				$output .= '<span class="menu-title">'.$menu['title']."</span>";
				if( $menu['description'] ){
					$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
				}
				$output .= "<b class=\"caret\"></b>";
				if( $menu['image']){ 
					$output .= '</span>';
				}
				$output .= '</a>';
			}	
			$output .= $this->genTree( $menu['megamenu_id'], 1, $menu );
			$output .= '</li>';
		}else {
			$output .= '<li class="'.$menu['menu_class'].'">';
			if( $menu['show_title'] ){ 
				$output .= '<a href="'.$this->getLink( $menu ).'">';
			
				if( $menu['image']){ $output .= '<span class="menu-icon" style="background:url(\''.$this->shopUrl."image/".$menu['image'].'\') no-repeat;">';	}
				$output .= '<span class="menu-title">'.$menu['title']."</span>";
				if( $menu['description'] ){
					$output .= '<span class="menu-desc">' . $menu['description'] . "</span>";
				}
				if( $menu['image']){ 
					$output .= '</span>';
				}

				$output .= '</a>';
			}
			$output .= '</li>';
		}
		return $output;
	}
	
	
	public function getLink( $menu ){
		$id = (int)$menu['item'];
		switch( $menu['type'] ){
			case 'category'     :
				return $this->url->link('product/category', 'path=' . $id );
				;
			case 'product'      :
				return  $this->url->link('product/product', 'product_id=' . $id);
				;
			case 'information'  :
		
				return   $this->url->link('information/information', 'information_id=' . $id);
				;
			case 'manufacturer' :;
				return  $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $id);
			default: 
				return $menu['url'];
		}
	}
	
	public function getColWidth( $menu, $cols ){
		$output = array();
		
		$split = preg_split('#\s+#',$menu['submenu_colum_width'] );
		if( !empty($split) && !empty($menu['submenu_colum_width']) ){
			foreach( $split as $sp ) {
				$tmp = explode("=",$sp);
				if( count($tmp) > 1 ){
					$output[trim(preg_replace("#col#","",$tmp[0]))]=(int)$tmp[1];
				}
			}
		}
		$tmp = array_sum($output);
		$spans = array();
		$t = 0; 
		for( $i=1; $i<= $cols; $i++ ){
			if( array_key_exists($i,$output) ){
				$spans[$i] = 'span'.$output[$i];
			}else{		
				if( (12-$tmp)%($cols-count($output)) == 0 ){
					$spans[$i] = "span".((12-$tmp)/($cols-count($output)));
				}else {
					if( $t == 0 ) {
						$spans[$i] = "span".( ((11-$tmp)/($cols-count($output))) + 1 ) ;
					}else {
						$spans[$i] = "span".( ((11-$tmp)/($cols-count($output))) + 0 ) ;
					}
					$t++;
				}					
			}
		}
		return $spans;
	}
	
	public function getResponsiveTree(){
	
	}
	
}
?>