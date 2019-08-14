<?php

class Zota_Zotabox_Block_Adminhtml_Widget_Button extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getHref() {
        return ($href=$this->getData('href')) ? $href : 'javascript:void(0);';
    }

    public function getTarget() {
        return ($target=$this->getData('target')) ? $target : '_blank';
    }

    public function getOnClick()
    {
        if (!$this->getData('on_click')) {
            return $this->getData('onclick');
        }
        return $this->getData('on_click');
    }

    protected function _toHtml()
    {
        $html = $this->getBeforeHtml().'<a '
            . ($this->getId()?' id="'.$this->getId() . '"':'')
            . ($this->getElementName()?' name="'.$this->getElementName() . '"':'')
            . ' href="'.$this->getHref() .'"'
            . ' title="'
            . $this->quoteEscape($this->getTitle() ? $this->getTitle() : $this->getLabel())
            . '"'
            . ' class="scalable ' . $this->getClass() . ($this->getDisabled() ? ' disabled' : '') . '"'
            . ' onclick="'.$this->getOnClick().'"'
            . ' style="'.$this->getStyle() .'"'
            . ' target="'.$this->getTarget() .'"'
            . ($this->getValue()?' value="'.$this->getValue() . '"':'')
            . ($this->getDisabled() ? ' disabled="disabled"' : '')
            . '><span><span><span>' .$this->getLabel().'</span></span></span></a>'.$this->getAfterHtml();

        return $html;
    }

    /**
     * Escape quotes inside html attributes
     * Use $addSlashes = false for escaping js that inside html attribute (onClick, onSubmit etc)
     *
     * @param string $data
     * @param bool $addSlashes
     * @return string
     */
    public function quoteEscape($data, $addSlashes = false)
    {
        if ($addSlashes === true) {
            $data = addslashes($data);
        }
        return htmlspecialchars($data, ENT_QUOTES, null, false);
    }
}
