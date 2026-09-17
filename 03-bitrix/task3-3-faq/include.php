<?php
/**
 * Модуль FAQ с email-уведомлениями
 * Автозагрузка классов
 */

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'task3_3_faq',
    [
        '\Task3_3\EventHandlers' => 'lib/eventhandlers.php',
    ]
);
