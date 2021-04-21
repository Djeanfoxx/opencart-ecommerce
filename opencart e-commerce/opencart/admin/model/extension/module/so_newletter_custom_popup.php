<?php

class ModelExtensionModuleSonewlettercustompopup extends Model {
	
	public function getModuleId() {
		$sql = " SHOW TABLE STATUS LIKE '" . DB_PREFIX . "module'" ;
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function createNewsletter()
	{

		$res0 = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."newsletter_custom_popup'");
		if($res0->num_rows == 0){
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `". DB_PREFIX. "newsletter_custom_popup` (
				  `news_id` int(11) NOT NULL AUTO_INCREMENT,
				  `news_email` varchar(255) NOT NULL,
				  `news_create_date` datetime NOT NULL,
				  `news_status` tinyint(1) NOT NULL,
				  `confirm_mail` tinyint(1) NOT NULL,
				  PRIMARY KEY (`news_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
		}


	}
	public function getListemail() {
		$sql = "SELECT ncp.* FROM " . DB_PREFIX . "newsletter_custom_popup ncp";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function mailing_all() {
		$sql = "SELECT ncp.* FROM " . DB_PREFIX . "newsletter_custom_popup ncp WHERE confirm_mail = 0";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function mailing_selected($subscribe_id) {
		$sql = "SELECT ncp.* FROM " . DB_PREFIX . "newsletter_custom_popup ncp WHERE news_id = '".$subscribe_id."' AND confirm_mail = 0";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function mailing_all_selected($data) {
		$item = [];
		foreach ($data['selected'] as $d)
		{
			$model = $this->db->query("SELECT * FROM " .DB_PREFIX. "newsletter_custom_popup ncp WHERE news_id = ".(int)$d." AND confirm_mail = 0");
			$item[] = $model->rows;
		}
		return $item;
	}
	public function mailing_all_not_notified() {
		$sql = "SELECT ncp.* FROM " . DB_PREFIX . "newsletter_custom_popup ncp WHERE news_status = 0 AND confirm_mail = 0";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function mailing_all_approved() {
		$sql = "SELECT ncp.* FROM " . DB_PREFIX . "newsletter_custom_popup ncp WHERE news_status = 1 AND confirm_mail = 0";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getconfirm_mailing_selected($subscribe_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "newsletter_custom_popup SET confirm_mail = 1 WHERE news_id = '".$subscribe_id."'");
	}
	public function getconfirm_mail_all() {
		$this->db->query("UPDATE " . DB_PREFIX . "newsletter_custom_popup SET confirm_mail = 1");
	}
	public function getconfirm_mail_all_selected($data) {
		$item = [];
		foreach ($data['selected'] as $d)
		{
			$item[] = $this->db->query("UPDATE " .DB_PREFIX. "newsletter_custom_popup SET confirm_mail = 1 WHERE news_id = '".(int)$d."'");
		}
		return $item;
	}
	public function getconfirm_mail_all_not_notified() {
		$this->db->query("UPDATE " . DB_PREFIX . "newsletter_custom_popup SET confirm_mail = 1 WHERE news_status = 0");
	}
	public function getconfirm_mail_all_approved() {
		$this->db->query("UPDATE " . DB_PREFIX . "newsletter_custom_popup SET confirm_mail = 1 WHERE news_status = 1");
	}
	public function revert_yet_send()
	{
		if ($this->db->query("UPDATE " . DB_PREFIX . "newsletter_custom_popup SET confirm_mail = 0"))
		{
			return "Update Successfull";
		}
	}
	public function approve_status($subscribe_id) {
		$res = $this->db->query("select * from ". DB_PREFIX ."newsletter_custom_popup where news_id='".$subscribe_id."'");
		if($res->row['news_status'] == 0)
		{
			if($this->db->query("UPDATE " . DB_PREFIX . "newsletter_custom_popup SET news_status = 1 WHERE news_id = '".$subscribe_id."'"))
			{
				return "Update Successfull";
			}
			else
			{
				return "Update Fail";
			}
		}else{
			if($this->db->query("UPDATE " . DB_PREFIX . "newsletter_custom_popup SET news_status = 0 WHERE news_id = '".$subscribe_id."'"))
			{
				return "Update Successfull";
			}
			else
			{
				return "Update Fail";
			}
		}

	}
	public function approve_all_selected($data) {
		$item = [];
		foreach ($data['selected'] as $d)
		{
			$item[] = $this->db->query("UPDATE " .DB_PREFIX. "newsletter_custom_popup SET news_status = 1 WHERE news_id = '".(int)$d."'");
		}
		return $item;
	}
	public function approve_all_not_approved() {
		if($this->db->query("UPDATE " . DB_PREFIX . "newsletter_custom_popup SET news_status = 1 WHERE news_status = 0"))
		{
			return "Update Successfull";
		}
		else
		{
			return "Update Fail";
		}
	}
	public function delete_item($subscribe_id) {
		if($this->db->query("DELETE FROM " . DB_PREFIX . "newsletter_custom_popup WHERE news_id = '".$subscribe_id."'"))
		{
			return "Delete Successfull";
		}
		else
		{
			return "Delete Fail";
		}
	}
	public function delete_all() {
		if($this->db->query("DELETE FROM " . DB_PREFIX . "newsletter_custom_popup"))
		{
			return "Delete Successfull";
		}
		else
		{
			return "Delete Fail";
		}
	}
	public function delete_all_selected($data) {
		$item = [];
		foreach ($data['selected'] as $d)
		{
			$item[] = $this->db->query("DELETE FROM " . DB_PREFIX . "newsletter_custom_popup WHERE news_id ='".(int)$d."'");
		}
		return $item;
	}
	public function delete_all_not_approved() {
		if($this->db->query("DELETE FROM " . DB_PREFIX . "newsletter_custom_popup WHERE news_status = 0"))
		{
			return "Delete Successfull";
		}
		else
		{
			return "Delete Fail";
		}
	}
}
?>