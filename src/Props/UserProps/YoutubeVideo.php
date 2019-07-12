<?php namespace Vf92\BitrixProps\Props\UserProps;

use CFile;
use Exception;
use Vf92\BitrixUtils\Orm\Model\Image;
use WebArch\BitrixIblockPropertyType\Abstraction\IblockPropertyTypeBase;
use WebArch\BitrixUserPropertyType\Abstraction\Custom\ConvertibleValueInterface;
use WebArch\BitrixUserPropertyType\Abstraction\DbColumnType\StringColTypeTrait;
use WebArch\BitrixUserPropertyType\Abstraction\UserTypeBase;

class YoutubeVideo extends UserTypeBase implements ConvertibleValueInterface
{
    use StringColTypeTrait;
    /**
     * Возвращает базовый тип на котором будут основаны операции фильтра (int, double, string, date, datetime)
     *
     * @return string
     */
    public static function getBaseType()
    {
        return self::BASE_TYPE_STRING;
    }

    /**
     * Возвращает описание для показа в интерфейсе (выпадающий список и т.п.)
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Youtube видео';
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
        $preview = null;
        if (!empty($value['VALUE']['PREVIEW'])) {
            /** @var Image $preview */
            $preview = $value['VALUE']['PREVIEW'];
        }
        $return =
            '<label>ID видео: <input type="text" name="' . $htmlControl['VALUE'] . '[ID]" value="' . $value['VALUE']['ID'] . '" /></label><br/>' .
            '<label>Название видео: <input type="text" name="' . $htmlControl['NAME'] . '[NAME]" value="' . $value['VALUE']['NAME'] . '" /></label><br/>' .
            '<label>Превью картинка: <input type="hidden" name="' . $htmlControl['VALUE'] . '[PREVIEW]" value="' . ($preview !== null ? $preview->getId() : '') . '" /><input type="file" name="' . $htmlControl['VALUE'] . '[PREVIEW_FILE]"/></label>';

            $return .= '<br><small>Текущее значение: ' . static::getHumanValueRepresentation($htmlControl['VALUE']) . '</small>';

        if ($userField['WITH_DESCRIPTION'] === 'Y') {
            $return .= '<div><input type="text" size="'
                . $userField['COL_COUNT']
                . '" name="'
                . $userField['DESCRIPTION']
                . '" value="'
                . htmlspecialchars($htmlControl['VALUE']['DESCRIPTION'])
                . '" /></div>';
        }

        return $return;
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
        return static::getHumanValueRepresentation($htmlControl['VALUE']);
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
            <td><p>Тип свойства видео для youtube, где можно указать ID видеоб название и превью изображение </p></td>
        </tr>
END;
    }

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
        if (!empty($value['VALUE'])) {
            if (!empty($value['VALUE']['PREVIEW_FILE'])) {
                //Удаление старого файла
                if ((int)$value['VALUE']['PREVIEW'] > 0) {
                    CFile::Delete((int)$value['VALUE']['PREVIEW']);
                }
                //сохранение нового файла
                $res = CFile::SaveFile($value['VALUE']['PREVIEW_FILE'], '/video', true);
                if (is_numeric($res)) {
                    $value['VALUE']['PREVIEW'] = (int)$res;
                } else {
                    global $APPLICATION;
                    $APPLICATION->ThrowException('Ошибка при сохранении превью видео');
                }
            }
            if (isset($value['VALUE']['PREVIEW_FILE'])) {
                unset($value['VALUE']['PREVIEW_FILE']);
            }
            $value['VALUE'] = serialize($value['VALUE']);
        }

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
        $value = [];
        if (!empty($rawValue['VALUE'])) {
            $value['VALUE'] = unserialize($rawValue['VALUE']);
            if ((int)$value['VALUE']['PREVIEW'] > 0) {
                try {
                    $value['VALUE']['PREVIEW'] = Image::createFromPrimary((int)$value['VALUE']['PREVIEW']);
                } catch (Exception $e) {
                    $value['VALUE']['PREVIEW'] = '';
                }
            }
        } else {
            $value['VALUE'] = [
                'ID'      => '',
                'NAME'    => '',
                'PREVIEW' => '',
            ];
        }

        return $value;
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
     * Возвращает человеко-понятное представление значения.
     *
     * @param array $value
     *
     * @return string
     */
    protected static function getHumanValueRepresentation(array $value)
    {
        $preview = null;
        $previewHtml = 'Отсутствует';
        if (!empty($value['VALUE']['PREVIEW'])) {
            /** @var Image $preview */
            $preview = $value['VALUE']['PREVIEW'];
            $previewHtml = '<img src="' . $preview->getSrc() . '" width="100" height="100">';
        }
        return 'ID видео - ' . $value['VALUE']['ID'] . ' / Название - ' . $value['VALUE']['NAME'] . ' / <br/>Превью - ' . $previewHtml;
    }
}