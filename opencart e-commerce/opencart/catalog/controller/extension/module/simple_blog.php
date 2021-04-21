<?php
	class ControllerExtensionModuleSimpleBlog extends Controller {
		public function index($setting) {

			$this->language->load('extension/module/simple_blog');

			$data['heading_title'] = $this->language->get('heading_title');
            $this->document->addStyle('catalog/view/javascript/simple_blog/css/style.css');
            $this->load->model('extension/simple_blog/article');
            $this->load->model('tool/image');

            $data['articles'] = array();

            $data['simple_blog_module'] = $this->config->get('simple_blog_module');


            if (isset($data['simple_blog_module'])){
                $settings = $data['simple_blog_module'];
                foreach ($settings as $setting) {
                    $category_id = $setting['category_id'];

                    if (isset($setting['image_width'])) {
                        $simple_blog_image_width = $setting['image_width'];
                    } else {
                        $simple_blog_image_width = '100';
                    }
                    if (isset($setting['image_height'])) {
                        $simple_blog_image_height = $setting['image_height'];
                    } else {
                        $simple_blog_image_height = '100';
                    }

                }


                if ($category_id == 'all') {
                    $data['heading_title'] = $this->language->get('text_latest_all');
                    $data['article_link'] = $this->url->link('extension/simple_blog/article');
                } elseif($category_id == 'popular') {
                    $data['heading_title'] = $this->language->get('text_popular_all');
                    $data['article_link'] = $this->url->link('extension/simple_blog/article');
                } else {
                    $category_info = $this->model_extension_simple_blog_article->getCategory($category_id);
                    $data['heading_title'] = $category_info['name'];
                    $data['article_link'] = $this->url->link('extension/simple_blog/simple_category', 'simple_blog_category_id=' . $category_id);
                }

                if ($category_id == 'all') {
                    $filter_data = array(
                        'start'           => 0,
                        'limit'           => $setting['article_limit']
                    );

                    $results = $this->model_extension_simple_blog_article->getArticleModuleWise($filter_data);

                } else if($category_id == 'popular') {
                    $filter_data = array(
                        'start'           => 0,
                        'limit'           => $setting['article_limit']
                    );

                    $results = $this->model_extension_simple_blog_article->getPopularArticlesModuleWise($filter_data);

                } else {
                    $filter_data = array(
                        'filter_category_id' => $category_id,
                        'start'           => 0,
                        'limit'           => $setting['article_limit']
                    );

                    $results = $this->model_extension_simple_blog_article->getArticleModuleWise($filter_data);
                }

                if ($setting['status']) {


                    foreach($results as $result) {

                        if ($result['image']) {
                            $image = $this->model_tool_image->resize($result['image'], $simple_blog_image_width, $simple_blog_image_height);
                            $image2x = $this->model_tool_image->resize($result['image'], $simple_blog_image_width*2, $simple_blog_image_height*2);

                        } else if($result['featured_image']) {
                            $image = $this->model_tool_image->resize($result['featured_image'], $simple_blog_image_width, $simple_blog_image_height);
                            $image2x = $this->model_tool_image->resize($result['featured_image'], $simple_blog_image_width*2, $simple_blog_image_height*2);

                        } else {
                            $image = $this->model_tool_image->resize('no_image.jpg', $simple_blog_image_width, $simple_blog_image_height);
                            $image2x = $this->model_tool_image->resize('no_image.jpg', $simple_blog_image_width*2, $simple_blog_image_height*2);

                        }

                        // get total comments
                        $total_comments = $this->model_extension_simple_blog_article->getTotalComments($result['simple_blog_article_id']);

                        if($total_comments != 1) {
                            $total_comments .= $this->language->get('text_comments');
                        } else {
                            $total_comments .= $this->language->get('text_comment');
                        }

                        $data['store_id'] = $this->config->get('config_store_id');
                        $data['customisation_general'] = $this->config->get('customisation_general_store');



                        $data['articles'][] = array(
                            'simple_blog_article_id'	=> $result['simple_blog_article_id'],
                            'article_title'		=> $result['article_title'],
                            'author_name'		=> $result['author_name'],
                            'image'				=> $image,

                            'width_settings'				=> $simple_blog_image_width,
                            'height_settings'				=> $simple_blog_image_height,


                            'short_description'	=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 120) . '..',

                            'image2x'			=> $image2x,
                            'featured_found'	=> '', // $featured_found
                            'date_added'		=> date($this->language->get('text_date_format'), strtotime($result['date_modified'])),
                            'allow_comment'		=> $result['allow_comment'],
                            'total_comment'		=> $total_comments,
                            'href'				=> $this->url->link('extension/simple_blog/article/view', 'simple_blog_article_id=' . $result['simple_blog_article_id'], true),
                            'author_href'		=> $this->url->link('extension/simple_blog/author', 'simple_blog_author_id=' . $result['simple_blog_author_id'], true),
                            'comment_href'		=> $this->url->link('extension/simple_blog/article/view', 'simple_blog_article_id=' . $result['simple_blog_article_id'] . '#comment-section', true)
                        );
                    }
                }

            }

            $data['show_all_href'] = $this->url->link('extension/simple_blog/article');
            $data['show_all_text'] = $this->language->get('show_all_text');

            $data['store_id'] = $this->config->get('config_store_id');
            $data['customisation_general'] = $this->config->get('customisation_general_store');


            $data['text_no_found'] = $this->language->get('text_no_result');
            return $this->load->view('extension/module/simple_blog', $data);
		}
	}