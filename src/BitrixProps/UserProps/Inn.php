<?php namespace Vf92\BitrixProps\UserProps;

use WebArch\BitrixUserPropertyType\Abstraction\Custom\CheckableValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\Custom\ConvertibleValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\DbColumnType\StringColTypeTrait;
use WebArch\BitrixUserPropertyType\Abstraction\UserTypeBase;

class Inn extends UserTypeBase implements ConvertibleValueInterface, CheckableValueInterface
{
    use StringColTypeTrait;

    /**
     * @inheritdoc
     */
    public static function onBeforeSave($userField, $value)
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public static function onAfterFetch($userField, $rawValue)
    {
        return $rawValue;
    }

    /**
     * @inheritdoc
     */
    public static function getBaseType()
    {
        return self::BASE_TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public static function getDescription()
    {
        return 'Инн';
    }

    /**
     * @inheritdoc
     */
    public static function getEditFormHTML($userField, $htmlControl)
    {
        $html = <<<END
            <input type="text" 
                   name="{$htmlControl['NAME']}" 
                   value="{$htmlControl['VALUE']}" 
                   placeholder="ИНН" 
                   size="60">
END;

        return $html;
    }

    /**
     * @inheritdoc
     */
    public static function getAdminListViewHtml($userField, $htmlControl)
    {
        return $htmlControl['VALUE'];
    }

    /**
     * @inheritdoc
     */
    public static function getSettingsHTML($userField, $htmlControl, $isVarsFromForm)
    {
        return <<<END
        <tr>
            <td>&nbsp;</td>
            <td><p>Тип свойства Инн </p></td>
        </tr>
END;
    }

    /**
     * @inheritdoc
     */
    public static function prepareSettings($userField)
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function checkFields($userField, $value)
    {
        $val = $value;
        if (is_array($value)) {
            $val = $value['VALUE'];
        }
        if (!static::validInn($val)) {
            return [
                ['id' => 1, 'text' => 'Не корректный ИНН'],
            ];
        }
        return [];
    }

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
}