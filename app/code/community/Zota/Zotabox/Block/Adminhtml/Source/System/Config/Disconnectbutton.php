<?php 
class Zota_Zotabox_Block_Adminhtml_Source_System_Config_Disconnectbutton extends Zota_Zotabox_Block_Adminhtml_Source_System_Config_Connectbutton
{
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$this->setElement($element);
		if (!Mage::helper('zotabox')->getConnectState()) {
			return '';
		}
		$tokenKey = $this->_getTokenKey();
		$accessKey = $this->_getAccessKey();
		
		$buttonBlock = $this->getLayout()->createBlock('zotabox/adminhtml_widget_button')
			// ->setId('connect-to-zotabox')
			// ->setClass('btn btn-fancy')
			// ->setHref($buttonUrl)
			->setLabel('Disconnect')
			->setTarget('_self')
			->setOnClick($this->_onClickScript() . ';return false;')
		;

		$html = $buttonBlock->toHtml();
		return $html;
	}

	protected function _getRedirectUrl() {
		return Mage::helper("adminhtml")->getUrl("adminhtml/zotabox/disconnect");
	}

	protected function _onClickScript() {
		$redirectUrl = $this->_getRedirectUrl();
		
		return <<<EOT
		(function() {
			var result = confirm('Do you want to disconnect from Zotabox?');
			if (result !== true) return;
			new Ajax.Request('{$redirectUrl}', {
				method: 'POST',
				parameters: {},
				// onCreate: function(request) {
				// 	return Ajax.Responders.unregister(varienLoaderHandler.handler);
				// },
				onSuccess: function(transport) {
					return window.location.reload();
				}
			});
		})();
		return false;
EOT;
	}
}
?>