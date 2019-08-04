<?php namespace Vf92\BitrixProps\UserProps;

use Vf92\MiscUtils\Helpers\Exception\WrongPhoneNumberException;
use Vf92\MiscUtils\Helpers\PhoneHelper;
use WebArch\BitrixUserPropertyType\Abstraction\Custom\CheckableValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\Custom\ConvertibleValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\DbColumnType\StringColTypeTrait;
use WebArch\BitrixUserPropertyType\Abstraction\UserTypeBase;

class Phone extends UserTypeBase implements ConvertibleValueInterface, CheckableValueInterface
{
    use StringColTypeTrait;

    /**
     * @inheritdoc
     */
    public static function onBeforeSave($userField, $value)
    {
        try {
            return PhoneHelper::normalizePhone($value);
        } catch (WrongPhoneNumberException $e) {
            return '';
        }
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
        return 'Телефон';
    }

    /**
     * @inheritdoc
     */
    public static function getEditFormHTML($userField, $htmlControl)
    {
        $val = $htmlControl['VALUE'];
        if (is_array($htmlControl['VALUE'])) {
            $val = $htmlControl['VALUE']['VALUE'];
        }
        $html = <<<END
            <input type="text" 
                   name="{$htmlControl['NAME']}" 
                   value="{$val}" 
                   placeholder="Телефон" 
                   size="60">
END;

        return $html;
    }

    /**
     * @inheritdoc
     */
    public static function getAdminListViewHtml($userField, $htmlControl)
    {
        $val = $htmlControl['VALUE'];
        if (is_array($htmlControl['VALUE'])) {
            $val = $htmlControl['VALUE']['VALUE'];
        }
        return $val;
    }

    /**
     * @inheritdoc
     */
    public static function getSettingsHTML($userField, $htmlControl, $isVarsFromForm)
    {
        return <<<END
        <tr>
            <td>&nbsp;</td>
            <td><p>Тип свойства телефон </p></td>
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
        if(is_array($value)){
            $val=$value['VALUE'];
        }
        if(!PhoneHelper::isPhone($val)){
            return [
                ['id'=>1, 'text'=>'Не корректный телефон']
            ];
        }
        return [];
    }
}