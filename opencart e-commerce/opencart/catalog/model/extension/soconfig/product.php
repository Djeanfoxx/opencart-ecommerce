<?php
/**
 * @author Nick M. <webdev.nick@gmail.com>
 * @package welldone_opencart 
 */

class ModelExtensionSoconfigProduct extends Model {
  
  private static $labels = array();
  private static $new_products = null;
  
  public function __construct($registry) {
    parent::__construct($registry);
    $this->load->model('catalog/product');
    $this->load->model('tool/image');
  }
  
  public function getLabels($product_id) {
    
    //New product
    if (self::$new_products === null){
      self::$new_products = $this->model_catalog_product->getLatestProducts(10); 
    }
   
    if ($this->soconfig->get_settings('new_status') ){
		if (!$this->hasLabel($product_id, 'new') && is_array(self::$new_products)) {
			foreach (self::$new_products as $product) {
			  if ($product_id == $product['product_id']) {
				$this->addLabel($product_id, 'new', $this->soconfig->get_settings('new_text', 'New'));
				break;
			  }
			}
		}
    } 
    
    /*if ($this->welldone->get_settings('label_special_status', 'show') == 'show')
    { 
      //Get discount
      $product = $this->model_catalog_product->getProduct($product_id);
      
      if (!$this->hasLabel($product_id, 'discount')){
        if ((float)$product['special']) {
          if ($this->welldone->get_settings('label_special_type', 'percent') === 'percent') {
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = false;
            }
            
            $special_price = $this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax'));
            if ($price > 0) {
              $discount_price = round(($price - $special_price) / $price * 100); 
              $this->addLabel($product_id, 'discount', '-' .$discount_price.'%');
            }
            
          }
          else
          {
            $this->addLabel($product_id, 'discount', $this->welldone->get_settings('label_discount_text', 'Sale'));
          }
        }
      }
    }
    
    if ($this->welldone->get_settings('label_oos_status', 'show') == 'show')
    {
      //Get out of stock
      if (!$this->hasLabel($product_id, 'outofstock')){
        if ($product['quantity'] <= 0) {
            $this->addLabel($product_id, 'outofstock', $product['stock_status']);
        }
      }
    }  
    
    //Get countdown
    if (!$this->hasLabel($product_id, 'countdown') && $this->welldone->get_settings('product_count_down_status','show') == 'show'){
      $special = $this->getSpecialPriceEnd($product_id);
      
      if ($special !== false){
        $this->addLabel($product_id, 'countdown', $special['date_end']);
      }
    }
    
    //Get colors
    if (!$this->hasLabel($product_id, 'colors')){
      $color_options = $this->getColorsOptions($product_id);
      
      if (count($color_options))
      {
        $this->addLabel($product_id, 'colors', $color_options);
      }
    }
    
    //Get size
    if (!$this->hasLabel($product_id, 'sizes')){
      $size_options = $this->getSizeOptions($product_id);
      
      if (count($size_options))
      {
        $this->addLabel($product_id, 'sizes', $size_options);
      }
    }  */
    
    if (!isset(self::$labels[$product_id])) {
        return array();
    }
    else
      return self::$labels[$product_id];
  }
  
  private function getColorsOptions($product_id){
    $color_option_id = $this->welldone->get_settings('product_color_option_id',array());
    $product_options = $this->model_catalog_product->getProductOptions($product_id);
    $color_options = array();
    
    foreach($product_options as $option)
    {
      if (in_array($option['option_id'],$color_option_id))
      {
        foreach($option['product_option_value'] as &$opt)
        {
          $opt['image'] = $this->model_tool_image->resize($opt['image'], 100, 100);
        }
        return $option['product_option_value'];
      }
    }
    
    return $color_options;
  }
  
  private function getSizeOptions($product_id){
    $color_option_id = $this->welldone->get_settings('product_size_option_id',array());
    $product_options = $this->model_catalog_product->getProductOptions($product_id);
    $color_options = array();
    
    foreach($product_options as $option)
    {
      if (in_array($option['option_id'],$color_option_id))
      {
        return $option['product_option_value'];
      }
    }
    
    return $color_options;
  }
  
  private function getSpecialPriceEnd($product_id) {
    $sql = "select date_start,date_end from " . DB_PREFIX . "product_special where product_id='$product_id'";
    $query = $this->db->query($sql);
    
    if ($query->num_rows) {
      $date_start = (int)strtotime($query->row['date_start']);
      $date_end = (int)strtotime($query->row['date_end']);
      $now = time();
      $valid = true;
      
      if ($date_start > 0 && $date_start > $now)
      {
        $valid = false;
      }  
      elseif ($date_end > 0 && $date_end < $now)
        $valid = false;
      
      if ($valid)    
        return $query->row;
      else
        return false;  
    }
    else
      return false; 
  }
  
  private function hasLabel($product_id, $label) {
    if (!isset(self::$labels[$product_id])) {
        return false;
    }
    return in_array($label, self::$labels[$product_id]);
  }
  
  private function addLabel($product_id, $label, $text) {
    if (!isset(self::$labels[$product_id])) {
        self::$labels[$product_id] = array();
    }
    self::$labels[$product_id][$label] = $text;
  }
  
  public function getProductsByID($ids,$limit)
  {
    if (count($ids) == 0)
      return array();
    
    $sql_ids = array();
    foreach($ids as $k=>$v)
    {
      if ((int)$v > 0)
        $sql_ids[] = $v;
    }
    
    if (count($sql_ids) == 0)
      return array();  
    
    $this->load->model('catalog/product');  
    
    $product_data = array();
    $query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' and p.product_id in (".implode(',',$sql_ids).") ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);
    
    foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		} 
    
    $results = array();
    foreach($ids as $idx=>$id)
    {
      if (isset($product_data[$id]))
        $results[] = $product_data[$id];
    }
    
		return $results;  
  }
}

