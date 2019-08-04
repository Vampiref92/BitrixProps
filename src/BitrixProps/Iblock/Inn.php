<?php namespace Vf92\BitrixProps\Iblock;

use WebArch\BitrixIblockPropertyType\Abstraction\IblockPropertyTypeBase;

/** @todo multiple */
class Inn extends IblockPropertyTypeBase
{
    protected static function validInn($inn)
    {
        if (preg_match('/\D/', $inn)) {
            return false;
        }
        $inn = (string)$inn;
        $len = strlen($inn);
        if ($len === 10) {
            return $inn[9] === (string)(((2 * $inn[0] + 4 * $inn[1] + 10 * $inn[2] + 3 * $inn[3] + 5 * $inn[4] + 9 * $inn[5] + 4 * $inn[6] + 6 * $inn[7] + 8 * $inn[8]) % 11) % 10);
        } elseif ($len === 12) {
            $num10 = (string)(((7 * $inn[0] + 2 * $inn[1] + 4 * $inn[2] + 10 * $inn[3] + 3 * $inn[4] + 5 * $inn[5] + 9 * $inn[6] + 4 * $inn[7] + 6 * $inn[8] + 8 * $inn[9]) % 11) % 10);
            $num11 = (string)(((3 * $inn[0] + 7 * $inn[1] + 2 * $inn[2] + 4 * $inn[3] + 10 * $inn[4] + 3 * $inn[5] + 5 * $inn[6] + 9 * $inn[7] + 4 * $inn[8] + 6 * $inn[9] + 8 * $inn[10]) % 11) % 10);
            return $inn[11] === $num11 && $inn[10] === $num10;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getPropertyType()
    {
        return self::PROPERTY_TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'Инн';
    }

    /**
     * @inheritdoc
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
    public function checkFields(array $property, array $value)
    {
        if (!static::validInn($value['VALUE'])) {
            return [];
        }

        return ['ИНН не валиден'];
    }
}