<?php 
class Zota_Zotabox_Block_Adminhtml_Source_System_Config_Connectbutton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	private $_tokenKey;
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$this->setElement($element);

		$this->_tokenKey = $this->_createTokenKey();
		$accessKey = $this->_getAccessKey();

		$buttonUrl = Mage::helper('zotabox')->getDomainUrl()
			. "/customer/access?redirect=" . $this->_getRedirectUrl()
			. "&token=" . $this->_tokenKey
			. "&customer=" . $this->_getCustomerId()
			. "&access=" . $accessKey
		;
		$buttonBlock = $this->getLayout()->createBlock('zotabox/adminhtml_widget_button')
			->setId('connect-to-zotabox')
			->setClass('btn btn-fancy')
			->setTarget('_zotabox')
			->setHref($buttonUrl)
		;
		if (empty($accessKey)) {
			$buttonBlock->setLabel('Sign into Zotabox');
			$buttonBlock->setOnClick($this->_onClickScript());
		}else{
			$buttonBlock->setLabel('Configure your widgets');
		}

		$html = $buttonBlock->toHtml();
		$html .= $this->getLayout()->createBlock('zotabox/adminhtml_widget_asset')->toHtml();
		return $html;
	}

	protected function _getTokenKey() {
		return $this->_tokenKey;
	}

	protected function _getRedirectUrl() {
		return Mage::helper("adminhtml")->getUrl("adminhtml/zotabox/connect");
	}

	protected function _getAccessKey() {
		return Mage::getStoreConfig('zotabox/basic/access', 0);
	}

	protected function _getCustomerId() {
		return Mage::getStoreConfig('zotabox/basic/customer', 0);
	}

	protected function _onClickScript() {
		$tokenValidUrl = Mage::helper("adminhtml")->getUrl("adminhtml/zotabox/validToken");
		$tokenKey = $this->_getTokenKey();

		return <<<EOT
		var _checkValidToken = function(callback) {
			new Ajax.Request('{$tokenValidUrl}', {
				method: 'POST',
				parameters: { 'token': '{$tokenKey}' },
				onCreate: function(request) {
					return Ajax.Responders.unregister(varienLoaderHandler.handler);
				},
				onSuccess: function(transport) {
					return callback.call(this, (/^(true|1)$/i).test(transport.responseText));
				}
			});
		}
		var _retried = 0;
		var _retryChecking = function() {
			_checkValidToken(function(valid) {
				if (false !== valid || _retried >= 30) {
					return window.location.reload();
				}
				var _t = setTimeout(function() {
					return _retryChecking();
				}, 100);
				_retried++;
			});
		}
		_retryChecking();
EOT;
	}

	protected function _createTokenKey() {
		// $tokenKey = Mage::getStoreConfig('zotabox/basic/token', 0);
		// if (!isset($tokenKey) || empty($tokenKey)) {
			$tokenKey = $this->_generateTokenKey();
			$config = new Mage_Core_Model_Config();
			$config->saveConfig('zotabox/basic/token', $tokenKey, 'default', 0);
			Mage::app()->getCacheInstance()->cleanType('config');
		// }
		return $tokenKey;
	}

	protected function _generateTokenKey() {
		return Mage::helper('core')->getRandomString(32);
	}
}
?>