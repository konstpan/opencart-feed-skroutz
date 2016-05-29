<?php

class ControllerFeedSkroutz extends Controller {
  
  public function index() {
    if ($this->config->get('skroutz_status')) {
      $output  = '<?xml version="1.0" encoding="UTF-8"?>';
      $output .= '<webstore>';
      $output .= '<created_at>' . date('Y-d-m h:i') . '</created_at>';
      $output .= '<products>';

      $this->load->model('catalog/product');
      $products = $this->model_catalog_product->getProducts();

      foreach ($products as $product) {
        $price = $product['special'] ? $product['special'] : $product['price'];
        $final_vat_price = 
                $this->tax->calculate($price, $product['tax_class_id'],
                                      $this->config->get('config_tax'));
        
        $output .= '<product>';
        $output .= '<UniqueID>' . $product['product_id'] . '</UniqueID>';
        $output .= '<name><![CDATA[' . $product['name'] . ']]></name>';
        $output .= '<price_with_vat>' . $final_vat_price . '</price_with_vat>';
        $output .= '<manufacturer><![CDATA[' . $product['manufacturer'] . ']]></manufacturer>';
        $output .= '</product>';
      }

      $output .= '</products>';
      $output .= '</webstore>';

      $this->response->addHeader('Content-type: text/xml; charset=utf-8');
      $this->response->setOutput($output);
    }
  }
  
}