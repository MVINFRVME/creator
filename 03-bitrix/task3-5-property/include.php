<?php
/**
 * Модуль кастомного типа свойства
 */

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'task3_5_property',
    [
        '\Task3_5\CustomSortableProperty' => 'lib/property.php',
    ]
);
