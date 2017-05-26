<?php
class ControllerInformationNews extends Controller {
 
	public function index() {
		$this->language->load('information/news');
		$this->load->model('extension/news');
	 
		$this->document->setTitle($this->language->get('heading_title')); 
	 
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/news'),
			'separator' => $this->language->get('text_separator')
		);
		  
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
			$url .= '&page=' . $this->request->get['page'];
		} else { 
			$page = 1;
		}
		
		$data = array(
			'page' => $page,
			'limit' => 10,
			'start' => 10 * ($page - 1),
		);
		
		$total = $this->model_extension_news->countNews();
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('extension/news', $url . '&page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();
	 
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_title'] = $this->language->get('text_title');
		$this->data['text_description'] = $this->language->get('text_description');
		$this->data['text_date'] = $this->language->get('text_date');
		$this->data['text_view'] = $this->language->get('text_view');
	 
		$all_news = $this->model_extension_news->getAllNews($data);
	 
		$this->data['all_news'] = array();
	 
		foreach ($all_news as $news) {
			$this->data['all_news'][] = array (
				'title' => $news['title'],
				'description' => (strlen(strip_tags(html_entity_decode($news['description']))) > 50 ? substr(strip_tags(html_entity_decode($news['description'])), 0, 50) . '...' : strip_tags(html_entity_decode($news['description']))),
				'view' => $this->url->link('information/news/news', 'news_id=' . $news['news_id']),
				'date_added' => date('d M Y', strtotime($news['date_added']))
			);
		}
	 
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/news_list.tpl';
		} else {
			$this->template = 'default/template/information/news_list.tpl'; 
		}
	 
		$this->children = array(
			'common/column_left', 
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer', 
			'common/header'
		);
	 
		$this->response->setOutput($this->render());
	}
 
   public function news() {
      $this->load->model('extension/news');
      $this->language->load('information/news');
 
      if (isset($this->request->get['news_id']) && !empty($this->request->get['news_id'])) {
         $news_id = $this->request->get['news_id'];
      } else {
         $news_id = 0;
      }
 
      $news = $this->model_extension_news->getNews($news_id);
 
      $this->data['breadcrumbs'] = array();
      $this->data['breadcrumbs'][] = array(
         'text' => $this->language->get('text_home'),
         'href' => $this->url->link('common/home'),
         'separator' => false
      );
      $this->data['breadcrumbs'][] = array(
         'text' => $this->language->get('heading_title'),
         'href' => $this->url->link('information/news'),
         'separator' => $this->language->get('text_separator')
      );
 
      if ($news) {
         $this->data['breadcrumbs'][] = array(
            'text' => $news['title'],
            'href' => $this->url->link('information/news/news', 'news_id=' . $news_id),
            'separator' => $this->language->get('text_separator')
         );
 
         $this->document->setTitle($news['title']);
 
         $this->data['heading_title'] = $news['title'];
         $this->data['description'] = html_entity_decode($news['description']);
 
         if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/information/news.tpl';
         } else {
            $this->template = 'default/template/information/news.tpl';
         }
 
         $this->children = array(
         'common/column_left',
         'common/column_right',
         'common/content_top',
         'common/content_bottom',
         'common/footer',
         'common/header'
         );
 
         $this->response->setOutput($this->render());
      } else {
         $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_error'),
            'href' => $this->url->link('information/news', 'news_id=' . $news_id),
            'separator' => $this->language->get('text_separator')
         );
 
         $this->document->setTitle($this->language->get('text_error'));
 
         $this->data['heading_title'] = $this->language->get('text_error');
         $this->data['text_error'] = $this->language->get('text_error');
         $this->data['button_continue'] = $this->language->get('button_continue');
         $this->data['continue'] = $this->url->link('common/home');
 
         if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
         } else {
            $this->template = 'default/template/error/not_found.tpl';
         }
 
         $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
         );
 
         $this->response->setOutput($this->render());
      }
   }
}
?>