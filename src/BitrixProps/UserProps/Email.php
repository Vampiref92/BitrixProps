<?php namespace Vf92\BitrixProps\UserProps;
use WebArch\BitrixUserPropertyType\Abstraction\Custom\CheckableValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\Custom\ConvertibleValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\DbColumnType\StringColTypeTrait;
use WebArch\BitrixUserPropertyType\Abstraction\UserTypeBase;

class Email extends UserTypeBase implements ConvertibleValueInterface, CheckableValueInterface
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
        return 'Email';
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
                   placeholder="Email" 
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
            <td><p>Тип свойства email </p></td>
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
        if (filter_var($val, FILTER_VALIDATE_EMAIL) === false){
            return [
                ['id'=>1, 'text'=>'Не корректный email']
            ];
        }
        return [];
    }
}