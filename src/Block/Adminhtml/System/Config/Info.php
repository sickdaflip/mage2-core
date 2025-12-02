<?php
/**
 * FlipDev Core Info Block
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
 * Information Display Block
 */
class Info extends Field
{
    /**
     * @var string
     */
    protected $_template = 'FlipDev_Core::system/config/info.phtml';

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
     * Get installed FlipDev modules
     *
     * @return array
     */
    public function getFlipDevModules(): array
    {
        return $this->dataHelper->getFlipDevModules();
    }

    /**
     * Render element value
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _renderValue(AbstractElement $element): string
    {
        $modules = $this->getFlipDevModules();
        
        $html = '<td class="value">';
        $html .= '<div style="padding: 15px; background: #ffffff; border: 1px solid #e3e3e3;">';
        
        $html .= '<h3 style="margin-top: 0; color: #1979c3;">Installed FlipDev Modules</h3>';
        $html .= '<table style="width: 100%; border-collapse: collapse;">';
        
        foreach ($modules as $moduleName => $version) {
            $html .= '<tr style="border-bottom: 1px solid #e3e3e3;">';
            $html .= '<td style="padding: 8px;"><strong>' . $moduleName . '</strong></td>';
            $html .= '<td style="padding: 8px; text-align: right;">v' . $version . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        $html .= '<div style="margin-top: 15px; padding-top: 15px;">';
        $html .= '<p><strong>Support:</strong> <a href="mailto:philippbreitsprecher@gmail.com">philippbreitsprecher@gmail.com</a></p>';
        $html .= '<p><strong>Documentation:</strong> <a href="https://github.com/sickdaflip/mage2-core" target="_blank">https://github.com/sickdaflip/mage2-core</a></p>';
        $html .= '</div>';
        
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
