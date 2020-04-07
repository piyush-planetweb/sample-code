<?php

namespace Pws\Attributes\Model\Config\Source\Sleeve;

class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
    * Get all options
    *
    * @return array
    */
    public function getAllOptions()
    {
        $this->_options = [
                ['label' => __(''), 'value'=>'0'],
                ['label' => __('Half sleeve'), 'value'=>'1'],
                ['label' => __('Sleeveless'), 'value'=>'2'],
                ['label' => __('3/4 sleeve'), 'value'=>'3'],
                ['label' => __('Quarter Leave'), 'value'=>'4'],
                ['label' => __('Full Sleeve'), 'value'=>'5'],
                ['label' => __('Roll-Up'), 'value'=>'6'],
                ['label' => __('Short sleeve'), 'value'=>'7']
            ];

    return $this->_options;

    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}
