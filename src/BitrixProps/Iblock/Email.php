<?php namespace Vf92\BitrixProps\Iblock;

use Exception;
use Vf92\BitrixUtils\Orm\Model\Image;
use WebArch\BitrixIblockPropertyType\Abstraction\IblockPropertyTypeBase;

/** @todo multiple */
class Email extends IblockPropertyTypeBase
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
        return 'Email';
    }

    /**
     * Возвращает маппинг реализованных для данного типа свойства методов.
     *
     * Неуказанные методы будут заменены на стандартную реализацию из модуля инфоблоков. Если же метод указан, но не
     * имеет конкретной реализации, будет выброшено исключение NotImplementedMethodException()
     *
     * @return array
     * @see IblockPropertyTypeInterface::getUserTypeDescription
     *
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
        return $value;
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
    public function checkFields(array $property, array $value){
        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false)
        {
            return [];
        }

        return ['Email не валиден'];
    }
}