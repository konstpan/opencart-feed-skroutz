<?php

class ControllerFeedSkroutz extends Controller {
  
  public function index() {
    if ($this->config->get('skroutz_status')) {
      $this->load->model('catalog/category');
      $this->load->model('catalog/product');
      
      $output  = '<?xml version="1.0" encoding="utf-8"?>';
      $output .= '<webstore>';
      $output .= '<created_at>' . date('Y-d-m h:i') . '</created_at>';
      $output .= '<products>';

      $products = $this->model_catalog_product->getProducts();

      foreach ($products as $product) {
        $price = $product['special'] ? $product['special'] : $product['price'];
        $final_vat_price = 
                $this->tax->calculate($price, $product['tax_class_id'],
                                      $this->config->get('config_tax'));
        
        $in_stock = '';
        $availability = '';
        
        $path = '';
        $path_name = '';
        
        $categories = $this->model_catalog_product->getCategories($product['product_id']);
        
        foreach ($categories as $category) {
          if (!$path) {
            $path .= $category['category_id'];
          }
          else {
            $path .= '_' . $category['category_id'];
          }
          
          $category_info = $this->model_catalog_category->getCategory($category['category_id']);
                  
          if (!$path_name) {
            $path_name = $category_info['name'];
          }
          else {
            $path_name .= ' - ' . $category_info['name'];
          }
        }
        if ($product['quantity'] <= 0) {
          $in_stock = 'N';
          $availability = $product['stock_status'];
        }
        else {
          $in_stock = 'Y';
          $availability = 'Άμεση παραλαβή';
        }
                        
        $output .= '<product>';
        $output .= '<id>' . $product['product_id'] . '</id>';
        $output .= '<name><![CDATA[' . $product['name'] . ']]></name>';
        $output .= '<image><![CDATA[' . HTTP_SERVER . 'image/' .$product['image'] . ']]></image>';
        if ($product['mpn']) $output .= '<mpn>' . $product['mpn'] . '</mpn>';
        if ($product['ean']) $output .= '<ean>' . $product['ean'] . '</ean>';
        $output .= '<price_with_vat>' . number_format($final_vat_price, 2, '.', '') . '</price_with_vat>';
        $output .= '<manufacturer><![CDATA[' . $product['manufacturer'] . ']]></manufacturer>';
        $output .= '<url><![CDATA[' . $this->url->link('product/product', 'path=' . $path . '&product_id=' . $product['product_id']) . ']]></url>';
        $output .= '<category><![CDATA[' . $path_name . ']]></category>';
        $output .= '<instock>' . $in_stock . '</instock>';
        $output .= '<availability>' . $availability . '</availability>';
        $output .= '</product>';
      }

      $output .= '</products>';
      $output .= '</webstore>';

      $this->response->addHeader('Content-type: text/xml; charset=utf-8');
      $this->response->setOutput($output);
    }
  }
  
}