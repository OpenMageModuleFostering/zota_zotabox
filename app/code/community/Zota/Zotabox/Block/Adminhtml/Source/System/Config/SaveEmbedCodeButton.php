<?php 
class Zota_Zotabox_Block_Adminhtml_Source_System_Config_SaveEmbedCodeButton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$this->setElement($element);
		$buttonBlock = $this->getLayout()->createBlock('zotabox/adminhtml_widget_button')
		// 	// ->setId('connect-to-zotabox')
			->setClass('btn btn-fancy')
			->setTarget("_self")
			->setOnClick($this->_onClickScript())
		;
		$buttonBlock->setLabel('Save');
		$html = $buttonBlock->toHtml();
		return $html;
	}

	protected function _onClickScript() {
		$removeAccessKeyUrl = Mage::helper("adminhtml")->getUrl("adminhtml/zotabox/removeAccessKey");
		
		return <<<EOT
		new Ajax.Request('{$removeAccessKeyUrl}', {
			method: 'POST',
			parameters: {},
			onSuccess: function(transport) {
				return configForm.submit();
			}
		});
		return false;
EOT;
	}
}
?>