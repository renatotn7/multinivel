<?php  

class ControllerModuleCategory extends Controller {





	protected function index($setting) {

		
		
		
		$this->language->load('module/category');


    	$this->data['heading_title'] = $this->language->get('heading_title');
    	$this->data['btn_ofertas'] = $this->language->get('btn_ofertas');
    	$this->data['text_reatrei_seu_pedido'] = $this->language->get('text_reatrei_seu_pedido');
    	$this->data['text_receba_nossas_novidades'] = $this->language->get('text_receba_nossas_novidades');


              $this->children = array(                
                'module/specialhome', 
              );

		

		if (isset($this->request->get['path'])) {

			$parts = explode('_', (string)$this->request->get['path']);



		} else {

			$parts = array();

		}

		






		if (isset($parts[0])) {

			$this->data['category_id'] = $parts[0];

		} else {


			$this->data['category_id'] = 0;

		}











		








		if (isset($parts[2])) {

		  $this->data['child_id'] = $parts[2];		

		}else if (isset($parts[1])) {

			$this->data['child_id'] = $parts[1];

		} else {

			$this->data['child_id'] = 0;

		}


		

















			


							

		$this->load->model('catalog/category');



		$this->load->model('catalog/product');



		$this->data['categories'] = array();

                /* Adicionado para bloquear acesso ao Material de Apoio 14-11-14 Werlon */
                if(isset($_SESSION['distribuidor_log']) && $_SESSION['distribuidor_log']!=''){
                    $categories = $this->model_catalog_category->getCategories(0,0);
                }else{
                    $categories = $this->model_catalog_category->getCategories(0);
                }


		foreach ($categories as $category) {

			$total = $this->model_catalog_product->getTotalProducts(array('filter_category_id'  => $category['category_id']));







			$children_data = array();



			$children = $this->model_catalog_category->getCategories($category['category_id']);



           	

			foreach ($children as $child) {

				

				$sub_categoria_data = array();

				$sub_categorias = $this->model_catalog_category->getCategories($child['category_id']);

				

				

				##END SUBCATEGORIA

				foreach($sub_categorias as $subcat){

					

				 $data_sub = array(

					'filter_category_id'  => $subcat['category_id'],

					'filter_sub_category' => true

				);



				$product_total_sub = $this->model_catalog_product->getTotalProducts($data_sub);

					

					

						$sub_categoria_data[] = array(

						'category_id' => $subcat['category_id'],

						'name'        => $subcat['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total_sub . ')' : ''),

						'href'        => $this->url->link('product/category', 'path=' .$category['category_id'].'_'. $child['category_id'] . '_' . $subcat['category_id'])	

						);	

							

					}

					

				##END SUBCATEGORIA	

					

				

				$data = array(

					'filter_category_id'  => $child['category_id'],

					'filter_sub_category' => true

				);



				$product_total = $this->model_catalog_product->getTotalProducts($data);



				$total += $product_total;



				$children_data[] = array(

					'category_id' => $child['category_id'],

					'name'        => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),

					'subcats'     =>$sub_categoria_data,

					'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	

				);		

			}



   		    $this->data['categories'][] = array(

				'category_id' => $category['category_id'],

				'imagem' => $category['image'],

				'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $total . ')' : ''),

				'children'    => $children_data,

				'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])

			);	

		}




		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {

			$this->template = $this->config->get('config_template') . '/template/module/category.tpl';

		} else {

			$this->template = 'default/template/module/category.tpl';

		}

		



		$this->render();

  	}



}

?>