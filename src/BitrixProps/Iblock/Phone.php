<?php namespace Vf92\BitrixProps\Iblock;

use CFile;
use Exception;
use Vf92\BitrixUtils\Orm\Model\Image;
use Vf92\MiscUtils\Helpers\Exception\WrongPhoneNumberException;
use Vf92\MiscUtils\Helpers\PhoneHelper;
use WebArch\BitrixIblockPropertyType\Abstraction\IblockPropertyTypeBase;

/** @todo multiple */
class Phone extends IblockPropertyTypeBase
{
    /**
     * @inheritdoc
     */
    public function getPropertyType()
    {
        return self::PROPERTY_TYPE_STRING;
    }

    /**
     * Возвращает краткое описание. Будет выведено в списке выбора типа свойства при редактировании информационного
     * блока.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Телефон';
    }

    /**
     * @inheritDoc
     */
    public function getCallbacksMapping()
    {
        return [
            'GetAdminListViewHTML' => [$this, 'getAdminListViewHTML'],
            'GetPropertyFieldHtml' => [$this, 'getPropertyFieldHtml'],
            'ConvertToDB'          => [$this, 'convertToDB'],
            'ConvertFromDB'        => [$this, 'convertFromDB'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAdminListViewHTML(array $property, array $value, array $control)
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function getPropertyFieldHtml(array $property, array $value, array $control)
    {
        $return = '<input type="text" name="' . $control['VALUE'] . '" value="' . $value['VALUE'] . '" />';

        if (
            $this->getControlMode($control) !== self::CONTROL_MODE_EDIT_FORM
            && $property['WITH_DESCRIPTION'] === 'Y'
        ) {
            $return .= '<div><input type="text" size="'
                . $property['COL_COUNT']
                . '" name="'
                . $control['DESCRIPTION']
                . '" value="'
                . htmlspecialchars($value['DESCRIPTION'])
                . '" /></div>';
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function convertToDB(array $property, array $value)
    {
        try {
            $value['VALUE'] = PhoneHelper::normalizePhone($value['VALUE']);
            return $value;
        } catch (WrongPhoneNumberException $e) {
            return '';
        }
    }

    /**
     * @inheritdoc
     */
    public function convertFromDB(array $property, array $value)
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function checkFields(array $property, array $value)
    {
        if (PhoneHelper::isPhone($value['VALUE'])) {
            return [];
        }

        return ['Email не валиден'];
    }
}