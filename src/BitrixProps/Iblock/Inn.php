<?php namespace Vf92\BitrixProps\Iblock;

use WebArch\BitrixIblockPropertyType\Abstraction\IblockPropertyTypeBase;

/** @todo multiple */
class Inn extends IblockPropertyTypeBase
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
        // TODO: Implement getDescription() method.
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
        // TODO: Implement getCallbacksMapping() method.
    }
}