<?php 
    class ModelExtensionModuleSimpleBlogInstall extends Model {
        public function addExtensionTables() {
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "simple_blog_article (
                    `simple_blog_article_id` int(16) NOT NULL AUTO_INCREMENT,
                    `simple_blog_author_id` int(16) NOT NULL,
                    `allow_comment` tinyint(1) NOT NULL,
                    `image` text NOT NULL,
                    `featured_image` text NOT NULL,
                    `article_related_method` varchar(64) NOT NULL,
                    `article_related_option` text NOT NULL,
                    `sort_order` int(8) NOT NULL,
                    `status` tinyint(1) NOT NULL,
                    `date_added` datetime NOT NULL,
                    `date_modified` datetime NOT NULL,
                    PRIMARY KEY (`simple_blog_article_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_article_description (
                  `simple_blog_article_description_id` int(16) NOT NULL AUTO_INCREMENT,
                  `simple_blog_article_id` int(16) NOT NULL,
                  `language_id` int(16) NOT NULL,
                  `article_title` varchar(256) NOT NULL,
                  `description` text NOT NULL,
                  `meta_description` varchar(256) NOT NULL,
                  `meta_keyword` varchar(256) NOT NULL,
                  PRIMARY KEY (`simple_blog_article_description_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_article_description_additional (
                  `simple_blog_article_id` int(16) NOT NULL,
                  `language_id` int(16) NOT NULL,
                  `additional_description` text NOT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_article_product_related (
                  `simple_blog_article_id` int(16) NOT NULL,
                  `product_id` int(16) NOT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_article_to_category (
                  `simple_blog_article_id` int(16) NOT NULL,
                  `simple_blog_category_id` int(16) NOT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_article_to_layout (
                  `simple_blog_article_id` int(16) NOT NULL,
                  `store_id` int(16) NOT NULL,
                  `layout_id` int(16) NOT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_article_to_store (
                  `simple_blog_article_id` int(16) NOT NULL,
                  `store_id` int(16) NOT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
            );
            
             $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_author (
                  `simple_blog_author_id` int(16) NOT NULL AUTO_INCREMENT,
                  `name` varchar(256) NOT NULL,
                  `image` text NOT NULL,
                  `status` tinyint(1) NOT NULL,
                  `date_added` datetime NOT NULL,
                  `date_modified` datetime NOT NULL,
                  PRIMARY KEY (`simple_blog_author_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_author_description (
                  `simple_blog_author_description_id` int(16) NOT NULL AUTO_INCREMENT,
                  `simple_blog_author_id` int(16) NOT NULL,
                  `language_id` int(16) NOT NULL,
                  `description` text NOT NULL,
                  `meta_description` varchar(256) NOT NULL,
                  `meta_keyword` varchar(256) NOT NULL,
                  `date_added` datetime NOT NULL,
                  PRIMARY KEY (`simple_blog_author_description_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
            );
            
             $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_category (
                  `simple_blog_category_id` int(16) NOT NULL AUTO_INCREMENT,
                  `image` text NOT NULL,
                  `parent_id` int(16) NOT NULL,
                  `top` tinyint(1) NOT NULL,
                  `blog_category_column` int(16) NOT NULL,
                  `external_link` text NOT NULL,
                  `column` int(8) NOT NULL,
                  `sort_order` int(8) NOT NULL,
                  `status` tinyint(1) NOT NULL,
                  `date_added` datetime NOT NULL,
                  `date_modified` datetime NOT NULL,
                  PRIMARY KEY (`simple_blog_category_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_category_description (
                  `simple_blog_category_description_id` int(16) NOT NULL AUTO_INCREMENT,
                  `simple_blog_category_id` int(16) NOT NULL,
                  `language_id` int(16) NOT NULL,
                  `name` varchar(256) NOT NULL,
                  `description` text NOT NULL,
                  `meta_description` varchar(256) NOT NULL,
                  `meta_keyword` varchar(256) NOT NULL,
                  PRIMARY KEY (`simple_blog_category_description_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_category_to_layout (
                  `simple_blog_category_id` int(16) NOT NULL,
                  `store_id` int(16) NOT NULL,
                  `layout_id` int(16) NOT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_category_to_store (
                  `simple_blog_category_id` int(16) NOT NULL,
                  `store_id` int(16) NOT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_comment (
                  `simple_blog_comment_id` int(16) NOT NULL AUTO_INCREMENT,
                  `simple_blog_article_id` int(16) NOT NULL,
                  `simple_blog_article_reply_id` int(16) NOT NULL,
                  `author` varchar(64) NOT NULL,
                  `comment` text NOT NULL,
                  `status` tinyint(1) NOT NULL,
                  `date_added` datetime NOT NULL,
                  `date_modified` datetime NOT NULL,
                  PRIMARY KEY (`simple_blog_comment_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_related_article (
                  `simple_blog_related_article_id` int(16) NOT NULL AUTO_INCREMENT,
                  `simple_blog_article_id` int(16) NOT NULL,
                  `simple_blog_article_related_id` int(16) NOT NULL,
                  `sort_order` int(8) NOT NULL,
                  `status` tinyint(1) NOT NULL,
                  `date_added` datetime NOT NULL,
                  PRIMARY KEY (`simple_blog_related_article_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
            );
            
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "simple_blog_view (
                  `simple_blog_view_id` int(16) NOT NULL AUTO_INCREMENT,
                  `simple_blog_article_id` int(16) NOT NULL,
                  `view` int(16) NOT NULL,
                  `date_added` datetime NOT NULL,
                  `date_modified` datetime NOT NULL,
                  PRIMARY KEY (`simple_blog_view_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
            );
        }
    }