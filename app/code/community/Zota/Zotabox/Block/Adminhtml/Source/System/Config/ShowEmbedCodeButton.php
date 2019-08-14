<?php 
class Zota_Zotabox_Block_Adminhtml_Source_System_Config_ShowEmbedCodeButton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$this->setElement($element);
		$currentUrl = Mage::helper('core/url')->getCurrentUrl();
		$buttonBlock = $this->getLayout()->createBlock('zotabox/adminhtml_widget_button')
			// ->setId('connect-to-zotabox')
			// ->setClass('btn btn-fancy')
			->setTarget("_self")
			->setOnClick("Fieldset.toggleCollapse('zotabox_advanced', '{$currentUrl}'); return false;")
		;
		$buttonBlock->setLabel('Save your embed code manually');
		$html = $buttonBlock->toHtml();
		if (Mage::helper('zotabox')->getEmbedState()) {
			$html .= $this->_getEmbedStateHtml();
		}
		return $html;
	}

	private function _getEmbedStateHtml() {
		return <<<EOT
		<span>(</span>
		<span class="check-actived">&nbsp;</span>
		<span>activated</span>
		<span>)</span>
EOT;
	}
}
?>