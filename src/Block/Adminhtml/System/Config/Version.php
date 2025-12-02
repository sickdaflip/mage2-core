<?php
/**
 * FlipDev Core Version Block
 *
 * @category  FlipDev
 * @package   FlipDev_Core
 * @author    FlipDev <dev@flipdev.com>
 * @copyright Copyright (c) 2024 FlipDev
 */

namespace FlipDev\Core\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use FlipDev\Core\Helper\Data as DataHelper;

/**
 * Version Display Block
 */
class Version extends Field
{
    /**
     * @var DataHelper
     */
    protected $dataHelper;

    /**
     * Constructor
     *
     * @param Context $context
     * @param DataHelper $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        DataHelper $dataHelper,
        array $data = []
    ) {
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Render element value
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _renderValue(AbstractElement $element): string
    {
        $version = $this->dataHelper->getModuleVersion('FlipDev_Core');
        
        $html = '<td class="value">';
        $html .= '<div style="padding: 10px; background: #f0f0f0; border-left: 3px solid #1979c3;">';
        $html .= '<strong style="font-size: 14px; color: #1979c3;">v' . $version . '</strong>';
        $html .= '</div>';
        $html .= '</td>';

        return $html;
    }

    /**
     * Render element
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
}
