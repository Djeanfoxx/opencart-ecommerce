<?php

class ControllerExtensionModuleSoTools extends Controller {
	public function index($setting) {
		static $module = 0;
		$this->load->language('extension/module/so_tools');
		$this->load->model('catalog/category');

		$data = array();
		$data['top']		= (int)$setting['top'];
		$data['position']	= $setting['position'];
		$data['settings']	= $setting;

		$data['categories']	= array();
		
		$categories			= $this->model_catalog_category->getCategories(0);
		foreach ($categories as $category) {
			$children_data = array();

			$children = $this->model_catalog_category->getCategories($category['category_id']);

			foreach($children as $child) {
				$subchildren_data = array();
				$subchildren = $this->model_catalog_category->getCategories($child['category_id']);

				foreach($subchildren as $subchild) {
					$subchildren_data[] = array(
						'category_id' => $subchild['category_id'],
						'name' => $subchild['name'],
						'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $subchild['category_id'])
					);
				}

				$children_data[] = array(
					'category_id' => $child['category_id'],
					'name' => $child['name'],
					'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
					'children'    => $subchildren_data
				);
			}
			
			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'],
				'href'        => $this->url->link('product/category', 'path=' . $category['category_id']),
				'children'    => $children_data
			);
		}

		$data['link_order']		= $this->url->link('account/order', '', true);
		$data['link_download']	= $this->url->link('account/download', '', true);
		$data['link_register']	= $this->url->link('account/register', '', true);
		$data['link_account']	= $this->url->link('account/account', '', true);
		$data['link_cart']		= $this->url->link('checkout/cart', '', true);
		$data['link_login']		= $this->url->link('account/login', '', true);

		$this->document->addStyle('catalog/view/javascript/so_tools/css/style.css');		
		$this->document->addScript('catalog/view/javascript/so_tools/js/script.js');

		// Currency
		$this->load->language('common/currency');
		$data['text_currency'] = $this->language->get('text_currency');
		$data['action_currency'] = $this->url->link('common/currency/currency', '', $this->request->server['HTTPS']);
		
		$data['code'] = $this->session->data['currency'];
		
		$this->load->model('localisation/currency');
		
		$data['currencies'] = array();
		$results = $this->model_localisation_currency->getCurrencies();
		foreach ($results as $result) {
			if ($result['status']) {
				$data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']
				);
			}
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect_currency'] = $this->url->link('common/home');
		} else {
			$url_data = $this->request->get;
			unset($url_data['_route_']);

			$route = $url_data['route'];
			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['redirect_currency'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
		}

		// Language
		$this->load->language('common/language');
		$data['text_language'] = $this->language->get('text_language');
		$data['action_language'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);
		$data['code_language'] = $this->session->data['language'];

		$this->load->model('localisation/language');
		$data['languages'] = array();
		$results = $this->model_localisation_language->getLanguages();
		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name' => $result['name'],
					'code' => $result['code']
				);
			}
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect_language'] = $this->url->link('common/home');
		} else {
			$url_data = $this->request->get;

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['redirect_language'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
		}

		// Shopping cart
		$this->load->language('common/cart');
		// Totals
		$this->load->model('setting/extension');

		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Because __call can not keep var references so we put them into an array.
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);
		// Display prices
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_'.$value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_'.$result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);
		}

		$data['text_empty'] 	= $this->language->get('text_empty');
		$data['text_cart'] 		= $this->language->get('text_cart');
		$data['text_checkout'] 	= $this->language->get('text_checkout');
		$data['text_recurring'] = $this->language->get('text_recurring');
		$data['text_items'] 	= sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
		$data['text_loading'] 	= $this->language->get('text_loading');

		$data['button_remove'] = $this->language->get('button_remove');

		$this->load->model('tool/image');
		$this->load->model('tool/upload');

		$data['products'] = array();

		foreach ($this->cart->getProducts() as $product) {
			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_'.$this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_cart_height'));
			} else {
				$image = '';
			}

			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type']
				);
			}

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
				
				$price = $this->currency->format($unit_price, $this->session->data['currency']);
				$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
			} else {
				$price = false;
				$total = false;
			}

			$data['products'][] = array(
				'cart_id'   => $product['cart_id'],
				'thumb'     => $image,
				'name'      => $product['name'],
				'model'     => $product['model'],
				'option'    => $option_data,
				'recurring' => ($product['recurring'] ? $product['recurring']['name'] : ''),
				'quantity'  => $product['quantity'],
				'price'     => $price,
				'total'     => $total,
				'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
			);
		}
		$data['text_items_product'] 	= sprintf($this->language->get('text_items_product'), $this->cart->countProducts());

		// Gift Voucher
		$data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency'])
				);
			}
		}

		$data['totals'] = array();

		foreach ($totals as $total) {
			$data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency']),
			);
		}

		$data['cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);

		// Recent Products
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$data['recent_products'] = array();
		if (isset($this->session->data['sorecentproduct'])) {
			$i = 0;
			foreach ($this->session->data['sorecentproduct'] as $product_id) {
				$product = $this->model_catalog_product->getProduct($product_id);
				if (isset($product['product_id']) && !empty($product['product_id'])) {
					$i++;
					if ($i > (int)$setting['limit_product']) continue;
					if ($product['image']) {
						$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_'.$this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_product_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_'.$this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_product_height'));
					}
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product['special']) {
						$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product['special'] ? $product['special'] : $product['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ((float)$product['special']) {
						$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						$discount = '-'.round((($product['price'] - $product['special'])/$product['price'])*100, 0).'%';
					} else {
						$special = false;
						$discount = false;
					}

					$datetimeNow 		= new DateTime();
					$datetimeCreate 	= new DateTime($product['date_available']);
					$interval 			= $datetimeNow->diff($datetimeCreate);
					$dateDay 			= $interval->format('%a');
					$productNew 		= $dateDay <= 10 ? 1 : 0;

					$data['recent_products'][] = array(
						'product_id' 		=> $product['product_id'],
						'product_name'		=> $product['name'],
						'product_image'		=> $image,
						'product_price'     => $price,
						'product_special'   => $special,
						'product_tax'       => $tax,
						'product_discount' 	=> $discount,
						'product_new' 		=> $productNew,
						'product_href'     	=> $this->url->link('product/product', 'product_id=' . $product['product_id'])
					);
				}
			}
		}
		

		//Text Language
		$data['text_history']			= $this->language->get('text_history');
		$data['text_shopping_cart']		= $this->language->get('text_shopping_cart');
		$data['text_register']			= $this->language->get('text_register');
		$data['text_account']			= $this->language->get('text_account');
		$data['text_download']			= $this->language->get('text_download');
		$data['text_login']				= $this->language->get('text_login');
		$data['text_recent_products']	= $this->language->get('text_recent_products');
		$data['text_my_account']		= $this->language->get('text_my_account');
		$data['text_new']				= $this->language->get('text_new');
		$data['button_cart']			= $this->language->get('button_cart');
		$data['text_search']			= $this->language->get('text_search');
		$data['text_all_categories']	= $this->language->get('text_all_categories');
		$data['text_head_categories']	= $this->language->get('text_head_categories');
		$data['text_head_cart']			= $this->language->get('text_head_cart');
		$data['text_head_account']		= $this->language->get('text_head_account');
		$data['text_head_search']		= $this->language->get('text_head_search');
		$data['text_head_recent_view']	= $this->language->get('text_head_recent_view');
		$data['text_head_gotop']		= $this->language->get('text_head_gotop');

		$data['module'] = $module++;
		
		return $this->load->view('extension/module/so_tools/layout', $data);
	}

	public function remove_cart() {
		$this->load->language('checkout/cart');

		$json = array();

		// Remove
		if (isset($this->request->post['key'])) {
			$this->cart->remove($this->request->post['key']);

			unset($this->session->data['vouchers'][$this->request->post['key']]);

			$json['success'] = $this->language->get('text_remove');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			// Totals
			$this->load->model('setting/extension');

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array. 			
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$sort_order = array();

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_'.$value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get('total_'.$result['code'] . '_status')) {
						$this->load->model('extension/total/' . $result['code']);

						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
					}
				}

				$sort_order = array();

				foreach ($totals as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $totals);
			}

			$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function info() {
		$this->response->setOutput($this->infocart());
	}

	public function infocart() {
		$this->load->language('common/cart');
		$this->load->language('extension/module/so_tools');

		// Totals
		$this->load->model('setting/extension');

		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Because __call can not keep var references so we put them into an array.
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);
			
		// Display prices
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_'.$value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_'.$result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);
		}

		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_recurring'] = $this->language->get('text_recurring');
		$data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_items_product'] 	= sprintf($this->language->get('text_items_product'), $this->cart->countProducts());

		$data['button_remove'] = $this->language->get('button_remove');

		$this->load->model('tool/image');
		$this->load->model('tool/upload');

		$data['products'] = array();

		foreach ($this->cart->getProducts() as $product) {
			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_'.$this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_cart_height'));
			} else {
				$image = '';
			}

			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type']
				);
			}

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
				
				$price = $this->currency->format($unit_price, $this->session->data['currency']);
				$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
			} else {
				$price = false;
				$total = false;
			}

			$data['products'][] = array(
				'cart_id'   => $product['cart_id'],
				'thumb'     => $image,
				'name'      => $product['name'],
				'model'     => $product['model'],
				'option'    => $option_data,
				'recurring' => ($product['recurring'] ? $product['recurring']['name'] : ''),
				'quantity'  => $product['quantity'],
				'price'     => $price,
				'total'     => $total,
				'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
			);
		}

		// Gift Voucher
		$data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency'])
				);
			}
		}

		$data['totals'] = array();

		foreach ($totals as $total) {
			$data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency']),
			);
		}

		$data['cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);

		return $this->load->view('extension/module/so_tools/infocart', $data);
	}
}