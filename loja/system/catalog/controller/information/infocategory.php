<?php

class ControllerInformationInfocategory extends Controller {

  public function index() {
    $this->language->load('information/infocategory');

    $this->document->breadcrumbs = array();

    $this->document->breadcrumbs[] = array(
        'href' => HTTP_SERVER . 'index.php?route=common/home',
        'text' => $this->language->get('text_home'),
        'separator' => FALSE
    );

    $this->load->model('catalog/infocategory');
    $this->load->model('tool/seo_url');

    if (isset($this->request->get['path'])) {
      $path = '';

      $parts = explode('_', $this->request->get['path']);

      foreach ($parts as $path_id) {
        $category_info = $this->model_catalog_infocategory->getCategory($path_id);

        if ($category_info) {
          if (!$path) {
            $path = $path_id;
          } else {
            $path .= '_' . $path_id;
          }

          $this->document->breadcrumbs[] = array(
              'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&path=' . $path),
              'text' => $category_info['name'],
              'separator' => $this->language->get('text_separator')
          );
        }
      }

      $category_id = array_pop($parts);
    } else {
      $category_id = 0;
    }

    $category_info = $this->model_catalog_infocategory->getCategory($category_id);

    if ($category_info) {
      $this->document->title = $category_info['name'];

      $this->document->description = $category_info['meta_description'];

      $this->data['heading_title'] = $category_info['name'];

      $this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

      $this->data['text_sort'] = $this->language->get('text_sort');

      if (isset($this->request->get['page'])) {
        $page = $this->request->get['page'];
      } else {
        $page = 1;
      }

      if (isset($this->request->get['sort'])) {
        $sort = $this->request->get['sort'];
      } else {
        $sort = 'i.date_added';
      }

      if (isset($this->request->get['order'])) {
        $order = $this->request->get['order'];
      } else {
        $order = 'DESC';
      }

      $this->data['sorts'] = array();

      $this->data['sorts'][] = array(
          'text' => $this->language->get('text_name_asc'),
          'value' => 'id.title-ASC',
          'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&path=' . $this->request->get['path'] . '&sort=id.title&order=ASC')
      );

      $this->data['sorts'][] = array(
          'text' => $this->language->get('text_name_desc'),
          'value' => 'id.title-DESC',
          'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&path=' . $this->request->get['path'] . '&sort=id.title&order=DESC')
      );

      $this->data['sorts'][] = array(
          'text' => $this->language->get('text_add_asc'),
          'value' => 'i.date_added-ASC',
          'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&path=' . $this->request->get['path'] . '&sort=i.date_added&order=ASC')
      );

      $this->data['sorts'][] = array(
          'text' => $this->language->get('text_add_desc'),
          'value' => 'i.date_added-DESC',
          'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&path=' . $this->request->get['path'] . '&sort=i.date_added&order=DESC')
      );


      $url = '';

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      $this->load->model('catalog/information');

      $this->load->model('tool/image');

      $category_total = $this->model_catalog_infocategory->getTotalInfoCategoriesByCategoryId($category_id);
      $information_total = $this->model_catalog_information->getTotalInformationsByCategoryId($category_id);

      if ($category_total || $information_total) {
        $this->data['categories'] = array();

        $results = $this->model_catalog_infocategory->getCategories($category_id);

        foreach ($results as $result) {
          if ($result['image']) {
            $image = $result['image'];
          } else {
            $image = 'no_image.jpg';
          }

          $this->data['categories'][] = array(
              'name' => $result['name'],
              'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url),
              'thumb' => $this->model_tool_image->resize($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
          );
        }

        $this->data['informations'] = array();

        $results = $this->model_catalog_information->getInformationsByCategoryId($category_id, $sort, $order, ($page - 1) * 6, 6);

        foreach ($results as $result) {
          $this->data['informations'][] = array(
              'information_id' => $result['information_id'],
              'title' => $result['title'],
              'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
              'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER
                      . 'index.php?route=information/information&path='
                      . $this->request->get['path'] . '&information_id=' . $result['information_id'])
          );
        }


        $url = '';

        if (isset($this->request->get['page'])) {
          $url .= '&page=' . $this->request->get['page'];
        }

        $pagination = new Pagination();
        $pagination->total = $information_total;
        $pagination->page = $page;
        $pagination->limit = 6;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&path=' . $this->request->get['path'] . $url . '&page={page}');

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;

        $this->data['order'] = $order;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/infocategory.tpl')) {
          $this->template = $this->config->get('config_template') . '/template/information/infocategory.tpl';
        } else {
          $this->template = 'default/template/information/infocategory.tpl';
        }

        $this->children = array(
            'common/header',
            'common/footer',
            'common/column_left',
            'common/column_right'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
      } else {
        $this->document->title = $category_info['name'];

        $this->document->description = $category_info['meta_description'];

        $this->data['heading_title'] = $category_info['name'];

        $this->data['text_error'] = $this->language->get('text_empty');

        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['continue'] = HTTP_SERVER . 'index.php?route=common/home';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
          $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
        } else {
          $this->template = 'default/template/error/not_found.tpl';
        }

        $this->children = array(
            'common/header',
            'common/footer',
            'common/column_left',
            'common/column_right'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
      }
    } else {
      $url = '';

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
      }

      if (isset($this->request->get['path'])) {
        $this->document->breadcrumbs[] = array(
            'href' => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/infocategory&path=' . $this->request->get['path'] . $url),
            'text' => $this->language->get('text_error'),
            'separator' => $this->language->get('text_separator')
        );
      }

      $this->document->title = $this->language->get('text_error');

      $this->data['heading_title'] = $this->language->get('text_error');

      $this->data['text_error'] = $this->language->get('text_error');

      $this->data['button_continue'] = $this->language->get('button_continue');

      $this->data['continue'] = HTTP_SERVER . 'index.php?route=common/home';

      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
        $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
      } else {
        $this->template = 'default/template/error/not_found.tpl';
      }

      $this->children = array(
          'common/header',
          'common/footer',
          'common/column_left',
          'common/column_right'
      );

      $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }
  }

}

?>