<?php

/**
 * Description of StoreBuyProduct
 *
 * @author Ronyldo12
 */
class ControllerCatalogStoreBuyProduct extends Controller {

    public function index() {

        if ($this->user->isLogged()) {
            $this->load->model('setting/store');
            $customer = $this->model_setting_store->getCustomer($this->user->getStoreId());

            if (count($customer) > 0) {
                $_SESSION['customer_id'] = $customer['customer_id'];
                $this->redirect(APP_BASE_URL . APP_LOJA);
                exit;
            }
        }
            $this->redirect($this->url->link('common/home', 'token=' . $this->request->get['post']));
        
    }

}
