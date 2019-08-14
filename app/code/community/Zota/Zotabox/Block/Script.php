<?php
class Zota_Zotabox_Block_Script extends Mage_Core_Block_Template
{
	const XML_PATH_ZOTABOX_ENABLED = 'zotabox/basic/enabled';
	const XML_PATH_ZOTABOX_EMBED_SCRIPT = 'zotabox/advanced/embed_script';

	public function isEnable() {
		return Mage::helper('zotabox')->getEmbedState() ? true : false;
	}

    public function printScript() {
    	$script = Mage::getStoreConfig(self::XML_PATH_ZOTABOX_EMBED_SCRIPT);
    	if (isset($script) && !empty($script)) {
    		echo $script;
    	}
        return false;
    }
}