<?php

require_once(DIR_SYSTEM . 'library/so_onepagecheckout/classes/so_utils.php');

class ModelExtensionModuleSoOnepageCheckout extends Model {
	
	private $order_id;
    private $order_data;

    private static $ADDRESS_FIELDS = array(
        'firstname',
        'lastname',
        'company',
        'address_id',
        'address_1',
        'address_2',
        'city',
        'postcode',
        'country_id',
        'zone_id',
        'custom_field'
    );

    public function __construct($registry) {
    	parent::__construct($registry);
        $this->load->model('checkout/order');
        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');
        $this->load->model('tool/image');
        $this->load->model('setting/setting');

        $this->order_id = $this->getProperty($this->session->data, 'order_id');
        if ($this->order_id) {
            $this->order_data = $this->model_checkout_order->getOrder($this->order_id);
            $order_query = $this->db->query("SELECT customer_group_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$this->order_id . "'");
            $this->order_data['customer_group_id'] = $this->getProperty($order_query->row, 'customer_group_id');
            if (isset($this->request->get['customer_group_id'])) {
                $this->order_data['customer_group_id'] = $this->request->get['customer_group_id'];
                if (!isset($this->session->data['guest'])) {
                    $this->session->data['guest'] = array();
                }
                $this->session->data['guest']['customer_group_id'] = $this->request->get['customer_group_id'];
                $this->config->set('config_customer_group_id', $this->session->data['guest']['customer_group_id']);
            }
        } else {
            $this->order_data = array();
        }
        
        $this->save();

        $this->session->data['comment'] = $this->getComment();
    }

    public function save($new_data = null) {
        if ($new_data === null) {
            $new_data = $this->order_data;
        }

        /* default values */
        $order_data = array(
            'invoice_prefix'            => $this->config->get('config_invoice_prefix'),
            'store_id'                  => $this->config->get('config_store_id'),
            'store_name'                => $this->config->get('config_name'),
            'store_url'                 => $this->config->get('config_store_id') ? $this->config->get('config_url') : HTTP_SERVER,

            'customer_id'               => $this->customer->isLogged() ? $this->customer->getId() : 0,
            'customer_group_id'         => $this->customer->isLogged() ? $this->customer->getGroupId() : $this->config->get('config_customer_group_id'),
            'firstname'                 => $this->customer->isLogged() ? $this->customer->getFirstName() : '',
            'lastname'                  => $this->customer->isLogged() ? $this->customer->getLastName() : '',
            'email'                     => $this->customer->isLogged() ? $this->customer->getEmail() : '',
            'telephone'                 => $this->customer->isLogged() ? $this->customer->getTelephone() : '',
            'fax'                       => '',
            'custom_field'              => '',

            'payment_firstname'         => '',
            'payment_lastname'          => '',
            'payment_company'           => '',
            'payment_company_id'        => '',
            'payment_address_1'         => '',
            'payment_address_2'         => '',
            'payment_city'              => '',
            'payment_postcode'          => '',
            'payment_country'           => '',
            'payment_country_id'        => '',
            'payment_tax_id'            => '',
            'payment_zone'              => '',
            'payment_zone_id'           => '',
            'payment_address_format'    => '',
            'payment_method'            => '',
            'payment_code'              => '',
            'payment_custom_field'      => array(),

            'shipping_firstname'        => '',
            'shipping_lastname'         => '',
            'shipping_company'          => '',
            'shipping_address_1'        => '',
            'shipping_address_2'        => '',
            'shipping_city'             => '',
            'shipping_postcode'         => '',
            'shipping_country'          => '',
            'shipping_country_id'       => '',
            'shipping_zone'             => '',
            'shipping_zone_id'          => '',
            'shipping_address_format'   => '',
            'shipping_method'           => '',
            'shipping_code'             => '',
            'shipping_custom_field'     => array(),

            'comment'                   => '',
            'total'                     => '',

            'affiliate_id'              => '',
            'commission'                => '',
            'marketing_id'              => '',
            'tracking'                  => ''
        );

        /* merge default values with order values */
        $this->order_data = array_replace($order_data, $new_data);

        /* update order data */
        $this->order_data = array_replace($this->order_data, array(
            'customer_id'               => $this->customer->isLogged() ? $this->customer->getId() : 0,
            'language_id'               => $this->config->get('config_language_id'),
            'currency_id'               => $this->currency->getId($this->session->data['currency']),
            'currency_code'             => $this->session->data['currency'],
            'currency_value'            => $this->currency->getValue($this->session->data['currency']),
            'ip'                        => $this->request->server['REMOTE_ADDR'],
            'forwarded_ip'              => $this->getProperty($this->request->server, 'HTTP_X_FORWARDED_FOR', $this->getProperty($this->request->server, 'HTTP_CLIENT_IP')),
            'user_agent'                => $this->getProperty($this->request->server, 'HTTP_USER_AGENT'),
            'accept_language'           => $this->getProperty($this->request->server, 'HTTP_ACCEPT_LANGUAGE'),
        ));

        /* overwrite some fields if not populated and customer is logged in */
        if ($this->customer->isLogged()) {
            $customer_data = array(
                'customer_id'               => $this->customer->getId(),
                'customer_group_id'         => $this->customer->getGroupId(),
                'firstname'                 => $this->customer->getFirstName(),
                'lastname'                  => $this->customer->getLastName(),
                'email'                     => $this->customer->getEmail(),
                'telephone'                 => $this->customer->getTelephone(),
                'fax'                       => ''
            );

            foreach ($customer_data as $k => $v) {
                $this->order_data[$k] = $v;
            }
        }

        // order totals
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        // Because __call can not keep var references so we put them into an array.
        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $sort_order = array();

        $this->load->model('setting/extension');
        $results = $this->model_setting_extension->getExtensions('total');
        
        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get('total_'.$value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get('total_'.$result['code'] . '_status')) {
                $this->load->model('extension/total/' . $result['code']);
                
                $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
            }
        }

        $sort_order = array();

        foreach ($totals as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $totals);
        $this->order_data['totals'] = $totals;
        $this->order_data['total'] = $total;

        /* products */
        $this->order_data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                $option_data[] = array(
                    'product_option_id'       => $option['product_option_id'],
                    'product_option_value_id' => $option['product_option_value_id'],
                    'option_id'               => $option['option_id'],
                    'option_value_id'         => $option['option_value_id'],
                    'name'                    => $option['name'],
                    'value'                   => $option['value'],
                    'option_value'            => $option['value'],
                    'type'                    => $option['type']
                );
            }

            $this->order_data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'download'   => $product['download'],
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $product['price'],
                'total'      => $product['total'],
                'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                'reward'     => $product['reward']
            );
        }

        /* vouchers */
        $this->order_data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $this->order_data['vouchers'][] = array(
                    'description'      => $voucher['description'],
                    'code'             => substr(md5(mt_rand()), 0, 10),
                    'to_name'          => $voucher['to_name'],
                    'to_email'         => $voucher['to_email'],
                    'from_name'        => $voucher['from_name'],
                    'from_email'       => $voucher['from_email'],
                    'voucher_theme_id' => $voucher['voucher_theme_id'],
                    'message'          => $voucher['message'],
                    'amount'           => $voucher['amount']
                );
            }
        }

        /* affiliates / marketing */
        if (isset($this->request->cookie['tracking'])) {
            $this->order_data['tracking'] = $this->request->cookie['tracking'];

            $subtotal = $this->cart->getSubTotal();

            // Affiliate
            $this->load->model('account/customer');

            $affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);
            
            if ($affiliate_info) {
                $this->order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
                $this->order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
            } else {
                $this->order_data['affiliate_id'] = 0;
                $this->order_data['commission'] = 0;
            }

            // Marketing
            $this->load->model('checkout/marketing');

            $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

            if ($marketing_info) {
                $this->order_data['marketing_id'] = $marketing_info['marketing_id'];
            } else {
                $this->order_data['marketing_id'] = 0;
            }
        } else {
            $this->order_data['affiliate_id'] = 0;
            $this->order_data['commission'] = 0;
            $this->order_data['marketing_id'] = 0;
            $this->order_data['tracking'] = '';
        }

        if ($this->order_id) {
            $this->editDBOrderOC2();
        } else {
            if ($this->customer->isLogged()) {
                $address_id = $this->customer->getAddressId();
                if ($address_id) {
                    $address_info = $this->model_account_address->getAddress($address_id);
                } else {
                    $addresses = $this->model_account_address->getAddresses();
                    $address_id = is_array($addresses) && count($addresses) ? array_get_first_key($addresses) : null;
                    $address_info = $address_id !== null ? $addresses[$address_id] : null;
                }
            } else {
                $address_info = null;
            }

            if ($address_info) {
                $this->setAddress('payment', $address_info);
                $this->setAddress('shipping', $address_info);
            } else {
                $this->order_data['payment_country_id']     = $this->config->get('config_country_id');
                $this->order_data['payment_zone_id']        = $this->config->get('config_zone_id');
                $this->order_data['shipping_country_id']    = $this->config->get('config_country_id');
                $this->order_data['shipping_zone_id']       = $this->config->get('config_zone_id');
            }
            $this->order_id = $this->model_checkout_order->addOrder($this->order_data);
            $this->session->data['order_id'] = $this->order_id;
        }
    }

    private function editDBOrderOC2() {
        $data = $this->order_data;
        $order_id = $this->order_id;
        $this->event->trigger('pre.order.edit', $data);
        $this->db->query("UPDATE `" . DB_PREFIX . "order` 
            SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "',
                store_id = '" . (int)$data['store_id'] . "',
                store_name = '" . $this->db->escape($data['store_name']) . "',
                store_url = '" . $this->db->escape($data['store_url']) . "',
                customer_id = '" . (int)$data['customer_id'] . "',
                customer_group_id = '" . (int)$data['customer_group_id'] . "',
                firstname = '" . $this->db->escape($data['firstname']) . "',
                lastname = '" . $this->db->escape($data['lastname']) . "',
                email = '" . $this->db->escape($data['email']) . "',
                telephone = '" . $this->db->escape($data['telephone']) . "',
                fax = '" . $this->db->escape($data['fax']) . "',
                custom_field = '" . $this->db->escape(json_encode($data['custom_field'])) . "',
                payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "',
                payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "',
                payment_company = '" . $this->db->escape($data['payment_company']) . "',
                payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "',
                payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "',
                payment_city = '" . $this->db->escape($data['payment_city']) . "',
                payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "',
                payment_country = '" . $this->db->escape($data['payment_country']) . "',
                payment_country_id = '" . (int)$data['payment_country_id'] . "',
                payment_zone = '" . $this->db->escape($data['payment_zone']) . "',
                payment_zone_id = '" . (int)$data['payment_zone_id'] . "',
                payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "',
                payment_custom_field = '" . $this->db->escape(json_encode($data['payment_custom_field'])) . "',
                payment_method = '" . $this->db->escape($data['payment_method']) . "',
                payment_code = '" . $this->db->escape($data['payment_code']) . "',
                shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "',
                shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "',
                shipping_company = '" . $this->db->escape($data['shipping_company']) . "',
                shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "',
                shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "',
                shipping_city = '" . $this->db->escape($data['shipping_city']) . "',
                shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "',
                shipping_country = '" . $this->db->escape($data['shipping_country']) . "',
                shipping_country_id = '" . (int)$data['shipping_country_id'] . "',
                shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "',
                shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "',
                shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "',
                shipping_custom_field = '" . $this->db->escape(json_encode($data['shipping_custom_field'])) . "',
                shipping_method = '" . $this->db->escape($data['shipping_method']) . "',
                shipping_code = '" . $this->db->escape($data['shipping_code']) . "',
                comment = '" . $this->db->escape($data['comment']) . "',
                total = '" . (float)$data['total'] . "',
                affiliate_id = '" . (int)$data['affiliate_id'] . "',
                commission = '" . (float)$data['commission'] . "',
                marketing_id = '" . (int)$data['marketing_id'] . "',
                tracking = '" . $this->db->escape($data['tracking']) . "',
                language_id = '" . (int)$data['language_id'] . "',
                currency_id = '" . (int)$data['currency_id'] . "',
                currency_code = '" . $this->db->escape($data['currency_code']) . "',
                currency_value = '" . (float)$data['currency_value'] . "',
                ip = '" . $this->db->escape($data['ip']) . "',
                forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "',
                user_agent = '" . $this->db->escape($data['user_agent']) . "',
                accept_language = '" . $this->db->escape($data['accept_language']) . "',
                date_modified = NOW() 
            WHERE order_id = '" . (int)$order_id . "'"
        );

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");

        // Products
        if (isset($data['products'])) {
            foreach ($data['products'] as $product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

                $order_product_id = $this->db->getLastId();

                foreach ($product['option'] as $option) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
                }
            }
        }

        // Gift Voucher
        $this->load->model('extension/total/voucher');
        $this->model_extension_total_voucher->disableVoucher($order_id);
        
        // Vouchers
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "voucher WHERE order_id = '" . (int)$order_id . "'");

        if (isset($data['vouchers'])) {
            foreach ($data['vouchers'] as $voucher) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

                $order_voucher_id = $this->db->getLastId();

                $voucher_id = $this->model_extension_total_voucher->addVoucher($order_id, $voucher);
                
                $this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
            }
        }

        // Totals
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");

        if (isset($data['totals'])) {
            foreach ($data['totals'] as $total) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            }
        }

        $this->event->trigger('post.order.edit', $data);
    }

    public function getComment() {
        return $this->getProperty($this->order_data, 'comment');
    }

	public function getTotal() {
        $totals = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $this->load->model('setting/extension');
        $results = $this->model_setting_extension->getExtensions('total');
        
        $sort_order = array();

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get('total_'.$value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get('total_'.$result['code'] . '_status')) {
                $this->load->model('extension/total/' . $result['code']);
                
                $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
            }
        }
        
        return $total_data['total'];
    }

    public function getTotals() {
        // order totals
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        // Because __call can not keep var references so we put them into an array.
        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $this->load->model('setting/extension');
        $results = $this->model_setting_extension->getExtensions('total');
        
        $sort_order = array();

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get('total_'.$value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get('total_'.$result['code'] . '_status')) {
                $this->load->model('extension/total/' . $result['code']);
                
                $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
            }
        }

        $sort_order = array();

        foreach ($totals as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $totals);

        $result = array();

        foreach ($totals as $total) {
            $result[] = array(
                'title' => $total['title'],
                'text'  => $this->registry->get('currency')->format($total['value'], $this->session->data['currency'])
            );
        }

        return $result;
    }

    public function getVouchers() {
        $result = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $result[] = array(
                    'description' => $voucher['description'],
                    'amount'      => $this->registry->get('currency')->format($voucher['amount'], $this->session->data['currency'])
                );
            }
        }

        return $result;
    }

    public function getProducts() {
        $result = array();

        $width  = $this->config->get($this->config->get('config_theme') . '_image_cart_width');
        $height = $this->config->get($this->config->get('config_theme') . '_image_cart_height');

        $setting_so_onepagecheckout                 = $this->model_setting_setting->getSetting('so_onepagecheckout');
        $setting_so_onepagecheckout_general         = $setting_so_onepagecheckout['so_onepagecheckout_general'];
        $setting_so_onepagecheckout_layout_setting  = $setting_so_onepagecheckout['so_onepagecheckout_layout_setting'];
        if ($setting_so_onepagecheckout_general['so_onepagecheckout_enabled'] && $setting_so_onepagecheckout_layout_setting['show_product_image_width']) {
            $width = $setting_so_onepagecheckout_layout_setting['show_product_image_width'];
        }
        if ($setting_so_onepagecheckout_general['so_onepagecheckout_enabled'] && $setting_so_onepagecheckout_layout_setting['show_product_image_height']) {
            $height = $setting_so_onepagecheckout_layout_setting['show_product_image_height'];
        }

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $width, $height);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', $width, $height);
            }

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
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            $recurring = '';

            if (version_compare(VERSION, '1.5.6', '>=') && $product['recurring']) {
                $frequencies = array(
                    'day'        => $this->language->get('text_day'),
                    'week'       => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month'      => $this->language->get('text_month'),
                    'year'       => $this->language->get('text_year'),
                );

                if ($product['recurring']['trial']) {
                    $recurring = sprintf($this->language->get('text_trial_description'), $this->registry->get('currency')->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                }

                if ($product['recurring']['duration']) {
                    $recurring .= sprintf($this->language->get('text_payment_description'), $this->registry->get('currency')->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                } else {
                    $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->registry->get('currency')->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                }
            }

            $result[] = array(
                'key'        => isset($product['key']) ? $product['key'] : '',
                'cart_id'    => isset($product['cart_id']) ? $product['cart_id'] : '',
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'thumb'      => $image,
                'model'      => $product['model'],
                'option'     => $option_data,
                'recurring'  => $recurring,
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $this->registry->get('currency')->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
                'total'      => $this->registry->get('currency')->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']),
                'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'], true)
            );
        }

        return $result;
    }

	public function getPaymentMethods() {
        $total = $this->getTotal();

        $method_data = array();

        $this->load->model('setting/extension');
        $results = $this->model_setting_extension->getExtensions('payment');
        
        if (version_compare(VERSION, '1.5.6', '>=')) {
            $recurring = $this->cart->hasRecurringProducts();
        } else {
            $recurring = false;
        }

        foreach ($results as $result) {
            if ($this->config->get('payment_'.$result['code'] . '_status')) {
                $this->load->model('extension/payment/' . $result['code']);

                $method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

                if ($method) {
                    if ($recurring) {
                        if (method_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
                            $method_data[$result['code']] = $method;
                        }
                    } else {
                        $method_data[$result['code']] = $method;
                    }
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->session->data['payment_methods'] = $method_data;

        return $method_data;
    }

    public function getPaymentMethodCode() {
        if ($value = $this->getProperty($this->session->data, 'payment_methods.' . $this->getProperty($this->session->data, 'payment_method.code'))) {
            $code = $value['code'];
            $this->session->data['payment_method'] = $value;
        } else {
            $code = array_get_first_key($this->getProperty($this->session->data, 'payment_methods', array()));
            if ($code !== false) {
	            $this->session->data['payment_method'] = $this->session->data['payment_methods'][$code];
            }
        }

        if (!$code) {
            unset($this->session->data['payment_method']);
        } else {
            $this->order_data['payment_method'] = $this->getProperty($this->session->data, 'payment_method.title');
            $this->order_data['payment_code'] = $this->getProperty($this->session->data, 'payment_method.code');
        }

        return $code;
    }

    public function getShippingMethods() {
        $method_data = array();

        $this->load->model('setting/extension');
        $results = $this->model_setting_extension->getExtensions('shipping');
        
        foreach ($results as $result) {
            if ($this->config->get('shipping_'.$result['code'] . '_status')) {
                $this->load->model('extension/shipping/' . $result['code']);

                $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

                if ($quote) {
                    $method_data[$result['code']] = array(
                        'title'      => $quote['title'],
                        'quote'      => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error'      => $quote['error']
                    );
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->session->data['shipping_methods'] = $method_data;

        return $method_data;
    }

    public function getShippingMethodCode() {
        $code = '';

        $parts = explode('.', $this->getProperty($this->session->data, 'shipping_method.code'));

        if (count($parts) > 1 && $value = $this->getProperty($this->session->data, 'shipping_methods.' . $parts[0] . '.quote.' . $parts[1])) {
            $code = $value['code'];
            $this->session->data['shipping_method'] = $value;
        } else {
            $part1 = array_get_first_key($this->getProperty($this->session->data, 'shipping_methods', array()));
            $part2 = array_get_first_key($this->getProperty($this->session->data, 'shipping_methods.' . $part1 . '.quote', array()));
            if ($value = $this->getProperty($this->session->data, 'shipping_methods.' . $part1 . '.quote.' . $part2)) {
                $code = $part1 . '.' . $part2;
                $this->session->data['shipping_method'] = $value;
            }
        }

        if (!$code) {
            unset($this->session->data['shipping_method']);
        } else {
            $this->order_data['shipping_method'] = $this->getProperty($this->session->data, 'shipping_method.title');
            $this->order_data['shipping_code'] = $this->getProperty($this->session->data, 'shipping_method.code');
        }

        return $code;
    }

    public function getAddress($type) {
        $result = array();
        foreach (self::$ADDRESS_FIELDS as $field) {
            $result[$field] = $this->getProperty($this->order_data, $type . '_' . $field);
        }
        $country_info = $this->model_localisation_country->getCountry($result['country_id']);
        if ($country_info) {
            $result['country_name'] = $country_info['name'];
            $result['country'] = $country_info['name'];
            $result['iso_code_2'] = $country_info['iso_code_2'];
            $result['iso_code_3'] = $country_info['iso_code_3'];
            $result['address_format'] = $country_info['address_format'];
        }

        $zone_info = $this->model_localisation_zone->getZone($result['zone_id']);
        if ($zone_info) {
            $result['zone'] = $zone_info['name'];
            $result['zone_name'] = $zone_info['name'];
            $result['zone_code'] = $zone_info['code'];
        } else {
            $result['zone'] = '';
            $result['zone_name'] = '';
            $result['zone_code'] = '';
        }
        $this->session->data[$type . '_address'] = $result;

        $this->session->data[$type . '_country_id'] = $this->getProperty($this->session->data, $type . '_address.country_id');
        $this->session->data[$type . '_zone_id'] = $this->getProperty($this->session->data, $type . '_address.zone_id');

        return $result;
    }

    public function setAddress($type, $address) {
        if (count($address)>0 && is_array($address)) {
            foreach ($address as $key => $value) {
                $this->order_data[$type . '_' . $key] = $value;
            }
        }
        if (count($this->getAddress($type))>0 && is_array($this->getAddress($type))) {
            foreach ($this->getAddress($type) as $key => $value) {
                $this->order_data[$type . '_' . $key] = $value;
            }
        }
    }

    public function getOrder() {
        return $this->order_data;
    }

    public function setOrderData ($order_data) {
        $this->save($order_data);
    }

    public function updateCustomer() {
        $customer_id = $this->customer->getId();
        $customer_group_id = $this->customer->getGroupId();
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET customer_id = '" . (int)$customer_id . "', customer_group_id = '" . (int)$customer_group_id . "' WHERE order_id = '" . (int)$this->order_id . "'");
    }

    public function getCustomerGroupId() {
        return $this->order_data['customer_group_id'];
    }

    public function getCustomFields($type = null) {
        $custom_fields = $this->model_account_custom_field->getCustomFields($this->getCustomerGroupId());

        foreach ($custom_fields as &$custom_field) {
            if ($type === null) {
                $custom_field['value'] = $this->getProperty($this->order_data, 'custom_field.' . $custom_field['custom_field_id']);
            } else {
                $custom_field['value'] = $this->getProperty($this->order_data, $type . '_custom_field.' . $custom_field['custom_field_id']);
            }
        }

        return $custom_fields;
    }

    public function getProperty($array, $property, $default_value = null) {
        $properties = explode('.', $property);
        foreach ($properties as $prop) {
            if (!is_array($array) || !isset($array[$prop])) {
                return $default_value;
            }
            $array = $array[$prop];
        }
        if (is_array($array)) {
            return $array;
        }
        $array = trim($array);
        return $array !== '' ? $array : $default_value;
    }
}