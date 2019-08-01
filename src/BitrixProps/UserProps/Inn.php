<?php namespace Vf92\BitrixProps\UserProps;
use WebArch\BitrixUserPropertyType\Abstraction\Custom\CheckableValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\Custom\ConvertibleValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\DbColumnType\StringColTypeTrait;
use WebArch\BitrixUserPropertyType\Abstraction\UserTypeBase;

class Inn extends UserTypeBase implements ConvertibleValueInterface, CheckableValueInterface
{
    use StringColTypeTrait;

    /**
     * Эта функция вызывается перед сохранением значений в БД.
     *
     * <p>Вызывается из метода Update объекта $USER_FIELD_MANAGER.</p>
     * <p>Для множественных значений функция вызывается несколько раз.</p>
     *
     * @param array $userField Массив описывающий поле.
     * @param mixed $value     Значение.
     *
     * @return string значение для вставки в БД.
     */
    public static function onBeforeSave($userField, $value)
    {
        return $value;
    }

    /**
     * Вызывается после извлечения значения из БД.
     *
     * @param array $userField Массив описывающий поле.
     * @param array $rawValue  ['VALUE' => <актуальное значение>]
     *
     * @return array
     */
    public static function onAfterFetch($userField, $rawValue)
    {
        return $rawValue;
    }

    /**
     * Возвращает базовый тип на котором будут основаны операции фильтра (int, double, string, date, datetime)
     *
     * @return string
     */
    public static function getBaseType()
    {
        return 'string';
    }

    /**
     * Возвращает описание для показа в интерфейсе (выпадающий список и т.п.)
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Инн';
    }

    /**
     * Эта функция вызывается при выводе формы редактирования значения свойства.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.
     * в форму редактирования сущности (на вкладке "Доп. свойства")</p>
     * <p>Элементы $htmlControl приведены к html безопасному виду.</p>
     *
     * @param array $userField   Массив описывающий поле.
     * @param array $htmlControl Массив управления из формы. Содержит элементы NAME и VALUE.
     *
     * @return string HTML для вывода.
     */
    public static function getEditFormHTML($userField, $htmlControl)
    {
        // TODO: Implement getEditFormHTML() method.
    }

    /**
     * Эта функция вызывается при выводе значения свойства в списке элементов.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.</p>
     * <p>Элементы $arHtmlControl приведены к html безопасному виду.</p>
     *
     * @param array $arUserField   Массив описывающий поле.
     * @param array $arHtmlControl Массив управления из формы. Содержит элементы NAME и VALUE.
     *
     * @return string HTML для вывода.
     */
    public static function getAdminListViewHtml($userField, $htmlControl)
    {
        // TODO: Implement getAdminListViewHtml() method.
    }

    /**
     * Эта функция вызывается при выводе формы настройки свойства.
     *
     * <p>Возвращает html для встраивания в 2-х колоночную таблицу.
     * в форму usertype_edit.php</p>
     * <p>т.е. tr td bla-bla /td td edit-edit-edit /td /tr </p>
     *
     * @param array $userField
     * @param array $htmlControl
     * @param bool  $isVarsFromForm
     *
     * @return string
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
     * Эта функция вызывается перед сохранением метаданных свойства в БД.
     *
     * <p>Она должна "очистить" массив с настройками экземпляра типа свойства.
     * Для того что бы случайно/намеренно никто не записал туда всякой фигни.</p>
     *
     * @param array $userField Массив описывающий поле. <b>Внимание!</b> это описание поля еще не сохранено в БД!
     *
     * @return array Массив который в дальнейшем будет сериализован и сохранен в БД.
     */
    public static function prepareSettings($userField)
    {
        return [];
    }

    /**
     * Эта функция валидатор.
     *
     * <p>Вызывается из метода CheckFields объекта $USER_FIELD_MANAGER.</p>
     * <p>Который в свою очередь может быть вызван из меторов Add/Update сущности владельца свойств.</p>
     *
     * @param array $userField Массив описывающий поле.
     * @param array $value     значение для проверки на валидность
     *
     * @return array массив массивов ("id","text") ошибок. Если ошибок нет, должен возвращаться пустой массив.
     */
    public static function checkFields($userField, $value)
    {
        if(1 === $value['VALUE']){
            return [
                [1, 'Не корректный ИНН']
            ];
        }
        return [];
    }
}