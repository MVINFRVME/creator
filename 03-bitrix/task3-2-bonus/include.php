<?php
/**
 * Модуль бонусной системы
 * Автозагрузка классов
 */

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'task3_2_bonus',
    [
        '\Task3_2\EventHandlers' => 'lib/eventhandlers.php',
    ]
);
