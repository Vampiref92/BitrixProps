<?php namespace Vf92\BitrixProps\UserProps;

use WebArch\BitrixUserPropertyType\Abstraction\Custom\CheckableValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\Custom\ConvertibleValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\DbColumnType\IntegerColTypeTrait;
use WebArch\BitrixUserPropertyType\Abstraction\UserTypeBase;

/** @todo multiple */
class User extends UserTypeBase implements ConvertibleValueInterface, CheckableValueInterface
{
    use IntegerColTypeTrait;

    /**
     * @inheritDoc
     */
    public static function onBeforeSave($userField, $value)
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public static function onAfterFetch($userField, $rawValue)
    {
        $user = \Bitrix\Main\UserTable::getById($rawValue)->fetchObject();
        return ['id' => $rawValue, 'user' => $user];
    }

    /**
     * @inheritDoc
     */
    public static function getBaseType()
    {
        return self::BASE_TYPE_INT;
    }

    /**
     * @inheritDoc
     */
    public static function getDescription()
    {
        return 'Привязка к пользователю';
    }

    /**
     * @inheritDoc
     */
    public static function getEditFormHTML($userField, $htmlControl)
    {
        $user = $htmlControl['VALUE']['user'];
        return '';
    }

    /**
     * @inheritDoc
     */
    public static function getAdminListViewHtml($userField, $htmlControl)
    {
        /** @var \Bitrix\Main\EO_User $user */
        $user = $htmlControl['VALUE']['user'];
        return '[' . $user->getId() . '] ' . $user->getLastName() . ' ' . $user->getName();
    }

    /**
     * @inheritDoc
     */
    public static function getSettingsHTML($userField, $htmlControl, $isVarsFromForm)
    {
        return <<<END
        <tr>
            <td>&nbsp;</td>
            <td><p>Тип свойства "Привязка к пользователю" </p></td>
        </tr>
END;
    }

    /**
     * @inheritDoc
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
        if ((int)$val < 1) {
            return [
                ['id' => 1, 'text'=>'Не корректный id пользователя'],
            ];
        }
        $count = \Bitrix\Main\UserTable::getCount((new \Bitrix\Main\ORM\Query\Filter\ConditionTree())->where('ID',
            $val));
        if ($count === 0) {
            return [
                ['id' => 2, 'text'=>'Пользователь с данным id пользователя не найден'],
            ];
        }
        return [];
    }
}