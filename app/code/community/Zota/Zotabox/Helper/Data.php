<?php
class Zota_Zotabox_Helper_Data extends Mage_Core_Helper_Abstract
{
	const ZOTABOX_DOMAIN_URL = 'zotabox.com';

	public function getDomainUrl($subDomain = '', $protocol = 'https') {
		return "{$protocol}://" . (!empty($subDomain) ? "{$subDomain}." : "") . self::ZOTABOX_DOMAIN_URL;
	}

	public function getEmbedState() {
		$embedScript = Mage::getStoreConfig('zotabox/advanced/embed_script', 0);
		if (!isset($embedScript) || empty($embedScript)) return false;
		$matches = array();
		preg_match("/src\=\"(.+)\/([a-zA-Z0-9]{32})\/(.+)\"/", $embedScript, $matches);
		$domain = $matches[2];
		return (!isset($domain) || empty($domain)) ? false : true;
	}

	public function getConnectState() {
		$domain = Mage::getStoreConfig('zotabox/basic/domain', 0);
		return (!isset($domain) || empty($domain)) ? false : true;
	}

	public function getEmbedCode($domainSecureId) {
		$domainUrl =  Mage::helper('zotabox')->getDomainUrl('static');
		$ds1 = substr($domainSecureId, 0, 1);
		$ds2 = substr($domainSecureId, 1, 1);
		return <<<EOT
<script type="text/javascript">
(function(d,s,id){var z=d.createElement(s);z.type="text/javascript";z.id=id;z.async=true;z.src="{$domainUrl}/{$ds1}/{$ds2}/{$domainSecureId}/widgets.js";var sz=d.getElementsByTagName(s)[0];sz.parentNode.insertBefore(z,sz)}(document,"script","zb-embed-code"));
</script>
EOT;
	}
}
?>