<?php

	/**
	 * Class SixBySix_RealTimeDespatch_Model_Service_Validation
	 * Note that this class should be executed from the Magento-root/shell folder, as to bypass Magento cron in case it
	 * stalls.
	 */

	class SixBySix_RealTimeDespatch_Model_Service_Validation extends Mage_Core_Model_Abstract {

		/**
		 * Constructors that are used.
		 */
		public function __construct()
		{
			parent::__construct();
			$this->adminEmail = Mage::getStoreConfig('sixbysix_realtimedespatch/general/admin_email');
			$this->adminName = Mage::getStoreConfig('sixbysix_realtimedespatch/general/admin_name');
			$this->list = Mage::getStoreConfig('sixbysix_realtimedespatch/error_notification/list_users');

		}

		/**
		 * @return bool
		 * Author: Adam Hall (adamhall@dobell.co.uk)
		 * Checks exported orders via SQL query to make sure threshold level hasn't been exceeded.
		 */
		function checkExportedOrders()
		{
			$resource = Mage::getSingleton('core/resource');
			$db = $resource->getConnection('core_read');
			$enable = Mage::getStoreConfig('sixbysix_realtimedespatch/error_notification/is_enabled');
			$threshold = (int)Mage::getStoreConfig('sixbysix_realtimedespatch/error_notification/exceedsize');

			if($enable == false) {
				return true;
			}

			$list = $this->appendList();

			// Check the grid table, we could check the order flat table but either is fine. Count the results.
			$query = $db->select()->from(array('sales_flat_order_grid'),
			                             'count(*)')
				->where('is_exported = ?', false)
				->where('status = \'processing\'');

			$count = (int)$db->fetchOne($query);

			if($count >= $threshold)
			{
				$mail = new Zend_Mail('UTF-8');

				$mail->setBodyHtml($this->bodyHtmlExported($threshold))
					->setType(Zend_Mime::MULTIPART_MIXED)
					->setFrom('admin@' . Mage::app()->getRequest()->getHttpHost(), Mage::app()->getStore(0)->getName())
					->addTo($list)
					->setSubject('Order Export Failure, Threshold Exceeded');

				$mail->send();
				return false;
			}
			else {
				return true;
			}
		}

		/**
		 * Author: Adam Hall (adamhall@dobell.co.uk)
		 * Get admin users and create a list that can be inserted into Zend_Mail.
		 * @return array
		 */
		private function appendList()
		{
			$list = $this->list;
			// Add list of users from backend + admin user.
			if(strpos($list, ',')) {
				$list = explode(',', $list);
				$list[$this->adminName] = $this->adminEmail;
			}
			// If we just have one address in the admin users list.
			elseif(!empty($list)) {
				$list = array($list);
				$list[$this->adminName] = $this->adminEmail;
			}
			// Just use the admin users address.
			else {
				$list[$this->adminName] = $this->adminEmail;
			}
			return $list;
		}

		/**
		 * @param $threshold
		 * @return string Author: Adam Hall (adamhall@dobell.co.uk)
		 * Author: Adam Hall (adamhall@dobell.co.uk)
		 * Body HTML text for email to be sent.
		 */
		private function bodyHtmlExported($threshold)
		{
			return 'Dear Admin,<br><br>
			<strong>WARNING:</strong> There are now more than ' . $threshold .' orders that haven\'t been exported to Orderflow yet, please investigate the Magento cron.<br><br>
			This is an automated email, please do not reply.';
		}

	}
