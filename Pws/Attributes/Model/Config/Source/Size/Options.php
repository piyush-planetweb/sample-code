<?php

namespace Pws\Attributes\Model\Config\Source\Size;

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
                ['label' => __('Extra Small(XS)'), 'value'=>'1'],
                ['label' => __('Small(S)'), 'value'=>'2'],
                ['label' => __('Medium(M)'), 'value'=>'3'],
                ['label' => __('Large(L)'), 'value'=>'4'],
                ['label' => __('Extra Large(XL)'), 'value'=>'5'],
                ['label' => __('2XL'), 'value'=>'6']
                ['label' => __('F/S'), 'value'=>'7']
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
