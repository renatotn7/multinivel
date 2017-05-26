<?php
class ModelToolSeoUrl extends Model {
	public function rewrite($link) {
		if ($this->config->get('config_seo_url')) {
			$url_data = parse_url(str_replace('&amp;', '&', $link));
		
			$url = ''; 
			
			$data = array();
		
			parse_str($url_data['query'], $data);
			
			foreach ($data as $key => $value) {
				if (($key == 'product_id') || ($key == 'manufacturer_id') || ($key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
				
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						
						unset($data[$key]);
					}					
				} elseif ($key == 'path') {
					$categories = explode('_', $value);
					// add blog
					$route = explode('/', $data['route']);
					if (empty($route[1])) $route[1] = '';
					$route[1] = preg_replace('/[^a-z]/i', '', $route[1]);
					if ($route[1] == 'information') {
						$route[1] = 'infocategory';
					} elseif ($route[1] == 'product') {
						$route[1] = 'category';
					}
					// end add blog
					
					foreach ($categories as $category) {
						// add blog
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '".$route[1]."_id=" . (int)$category . "'");
						// end add blog
				
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
						}							
					}
					
					unset($data[$key]);
				}
			}
		
			if ($url) {
				unset($data['route']);
			
				$query = '';
			
				if ($data) {
					foreach ($data as $key => $value) {
						$query .= '&' . $key . '=' . $value;
					}
					
					if ($query) {
						$query = '?' . trim($query, '&');
					}
				}

				return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $query;
			} else {
				return $link;
			}
		} else {
			return $link;
		}		
	}
}
?>