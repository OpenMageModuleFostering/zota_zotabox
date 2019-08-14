<?php

class Zota_Zotabox_Adminhtml_ZotaboxController extends Mage_Core_Controller_Varien_Action//Mage_Adminhtml_Controller_Action
{
    public function connectAction() {
		$redirectUrl = Mage::app()->getRequest()->getPost('redirect');
		if (!isset($redirectUrl) || empty($redirectUrl)) {
			$redirectUrl = Mage::helper("adminhtml")->getUrl("adminhtml/system_config/edit/section/zotabox");
		}

		$accessKey = Mage::app()->getRequest()->getPost('access');
		$customerId = Mage::app()->getRequest()->getPost('customer');
		$domainSecureId = Mage::app()->getRequest()->getPost('domain');

		if (isset($accessKey) && !empty($accessKey)
			&& isset($customerId) && !empty($customerId)
			&& isset($domainSecureId) && !empty($domainSecureId)
		) {
			$config = new Mage_Core_Model_Config();
			$config->saveConfig('zotabox/basic/customer', $customerId, 'default', 0);
			$config->saveConfig('zotabox/basic/domain', $domainSecureId, 'default', 0);
			$config->saveConfig('zotabox/basic/access', $accessKey, 'default', 0);

			$embedCode = Mage::helper('zotabox')->getEmbedCode($domainSecureId);
			$config->saveConfig('zotabox/advanced/embed_script', $embedCode, 'default', 0);
			Mage::app()->getCacheInstance()->cleanType('config');
		}
		$this->_destroyToken();
    }

    public function disconnectAction() {
		$this->removeAccessKeyAction();

    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Mage::helper('zotabox')->getDomainUrl()."/customer/access/disconnect");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        	'customer' => Mage::getStoreConfig('zotabox/basic/customer')
    	));
        $response = curl_exec($ch);
        curl_close($ch);

        Mage::getSingleton('core/cookie')
        	->set('zotabox_flash_message', __('Disconnected from Zotabox successfully.'))
        ;
    	$this->_destroyToken();

    	return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(true));
    }

    public function removeAccessKeyAction() {
    	$storeId = Mage::app()->getStore()->getStoreId();

    	$config = new Mage_Core_Model_Config();
		$config->deleteConfig('zotabox/basic/customer', 'default', $storeId);
		$config->deleteConfig('zotabox/basic/domain', 'default', $storeId);
		$config->deleteConfig('zotabox/basic/access', 'default', $storeId);
		$config->deleteConfig('zotabox/advanced/embed_script', 'default', $storeId);

		$config->deleteConfig('zotabox/basic/customer', 'default', 0);
		$config->deleteConfig('zotabox/basic/domain', 'default', 0);
		$config->deleteConfig('zotabox/basic/access', 'default', 0);
		$config->deleteConfig('zotabox/advanced/embed_script', 'default', 0);

		$config->deleteConfig('zotabox/basic/customer', 'default', 1);
		$config->deleteConfig('zotabox/basic/domain', 'default', 1);
		$config->deleteConfig('zotabox/basic/access', 'default', 1);
		$config->deleteConfig('zotabox/advanced/embed_script', 'default', 1);

		Mage::app()->getCacheInstance()->cleanType('config');
    }

    public function validTokenAction() {
    	$token = Mage::app()->getRequest()->getPost('token');
    	$isExpired = $this->_isValidToken($token) ? false : true;
    	$this->getResponse()
	    	->clearHeaders()
	    	->setHeader('Content-type', 'application/json', true)
    	;
    	if ($isExpired) {
    		Mage::getSingleton('core/cookie')
    			->set('zotabox_flash_message', __('Connect to Zotabox successfully.'))
			;
    	}
        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($isExpired));
    }

    private function _destroyToken() {
    	$config = new Mage_Core_Model_Config();
		$config->deleteConfig('zotabox/basic/token', 'default', 0);
		return Mage::app()->getCacheInstance()->cleanType('config');
    }

    private function _isValidToken($token) {
		$storeToken = Mage::getStoreConfig('zotabox/basic/token', 0);
    	if (!isset($storeToken) || !isset($token)) return false;
		return $storeToken === $token;
	}
}