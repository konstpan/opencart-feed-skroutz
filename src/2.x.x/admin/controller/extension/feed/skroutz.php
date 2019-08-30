<?php

class ControllerExtensionFeedSkroutz extends Controller {

  private $error 			= array();
	private $pluginpath		= 'feed/skroutz';
	private $plugintpl 		= 'skroutz.tpl'; 
	private $tokenstr		= '';
	private $pluginssl		= 'SSL';
	private $pluginurl		= 'extension/feed';
	private $pluginname		= 'skroutz';
	private $plugintype		= 'feed';

  public function __construct($registry) {
		parent::__construct($registry);
 		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3') { 
			$this->plugintpl 	= 'extension/feed/skroutz';
			$this->pluginpath 	= 'extension/feed/skroutz';
		} else if(substr(VERSION,0,3)=='2.2') {
			$this->plugintpl 	= 'feed/skroutz';
		} 
		
		if(substr(VERSION,0,3) >= '3.0') { 
			$this->pluginname 	= 'skroutz';
			$this->pluginurl 	= 'marketplace/extension'; 
			$this->tokenstr 	= 'user_token=' . $this->session->data['user_token'];
			$this->tokenstrtype	= 'user_token=' . $this->session->data['user_token'] . '&type=' . $this->plugintype;
		} else if(substr(VERSION,0,3) == '2.3') {
			$this->pluginurl 	= 'extension/extension';
			$this->tokenstr 	= 'token=' . $this->session->data['token'];
			$this->tokenstrtype	= 'token=' . $this->session->data['token'] . '&type=' . $this->plugintype;
		} else {
			$this->tokenstr 	= 'token=' . $this->session->data['token'];
			$this->tokenstrtype	= 'token=' . $this->session->data['token'] . '&type=' . $this->plugintype;
		}
		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') { 
			$this->pluginssl = true;
		} 
  } 
  
  public function index() {
		//Language Loading
		$data = array();
		$this->load->language($this->pluginpath);

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->pluginname, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link($this->pluginpath, $this->tokenstr, $this->pluginssl));
		}

    $data['heading_title'] = $this->language->get('heading_title');

    $data['text_edit']          = $this->language->get('text_edit');
    $data['text_enabled']       = $this->language->get('text_enabled');
    $data['text_disabled']      = $this->language->get('text_disabled');
    $data['text_module'] 				= $this->language->get('text_module');

    $data['text_available']     = $this->language->get('text_available');
    $data['text_1_to_3_days']   = $this->language->get('text_1_to_3_days');
    $data['text_4_to_10_days']  = $this->language->get('text_4_to_10_days');
    $data['text_upon_order'] 		= $this->language->get('text_upon_order');

    $data['entry_status']       = $this->language->get('entry_status');
    $data['entry_language']     = $this->language->get('entry_language');
    $data['entry_data_feed']    = $this->language->get('entry_data_feed');

    $data['button_save']        = $this->language->get('button_save');
    $data['button_cancel']      = $this->language->get('button_cancel');

		/**
		 * Get languages
		 */
		$this->load->model('localisation/language');

		$data['languages'] = array();

		$languages = $this->model_localisation_language->getLanguages();

		foreach($languages as $language) {
			if($language['status']) {
				$data['languages'][] = array(
					'language_id'	=> $language['language_id'],
					'name'			=> $language['name']
				);
			}
    }
    
		/**
		 * Get stock statuses
		 */
		$this->load->model('localisation/stock_status');

		$data['stock_statuses'] = array();

		$stock_statuses = $this->model_localisation_stock_status->getStockStatuses();

		foreach($stock_statuses as $stock_status) {
      $data['stock_statuses'][] = array(
        'stock_status_id'	  => $stock_status['stock_status_id'],
        'name'			        => $stock_status['name']
      );

      if( isset($this->request->post['skroutz_stock_status_' . $stock_status['stock_status_id']]) ){
        $data['skroutz_stock_status_' . $stock_status['stock_status_id']] = $this->request->post['skroutz_stock_status_' . $stock_status['stock_status_id']];
      } else {
        $data['skroutz_stock_status_' . $stock_status['stock_status_id']] = $this->config->get('skroutz_stock_status_' . $stock_status['stock_status_id']);
      }
    }

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
    }
    
    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

		/**
		 * Breadcrumbs initiliaze
		 */
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->tokenstr, $this->pluginssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link($this->pluginurl, $this->tokenstrtype, $this->pluginssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($this->pluginpath, $this->tokenstr, $this->pluginssl)
		);

		/**
		 * Buttons
		 */
		$data['action'] = $this->url->link($this->pluginpath, $this->tokenstr, $this->pluginssl);

		$data['cancel'] = $this->url->link($this->pluginurl, $this->tokenstrtype, $this->pluginssl);


    if (isset($this->request->post['skroutz_status'])) {
      $data['skroutz_status'] = $this->request->post['skroutz_status'];
    } else {
      $data['skroutz_status'] = $this->config->get('skroutz_status');
    }

    $data['data_feed'] = HTTP_CATALOG . 'index.php?route=extension/feed/skroutz';

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view($this->plugintpl, $data));
  }

  protected function validate() {
		if (!$this->user->hasPermission('modify', $this->pluginpath)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

    return !$this->error;
  }

}