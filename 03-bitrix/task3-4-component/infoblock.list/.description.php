<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * Задание 3.4: Кастомный компонент списка элементов инфоблока
 * Выводит название, описание и раздел элементов с кэшированием
 */

$arComponentDescription = [
    'NAME' => 'Список элементов инфоблока',
    'DESCRIPTION' => 'Выводит список элементов с названием, описанием и разделом',
    'ICON' => '/images/icon.gif',
    'SORT' => 10,
    'PATH' => [
        'ID' => 'custom',
        'NAME' => 'Кастомные компоненты',
        'CHILD' => [
            'ID' => 'infoblock',
            'NAME' => 'Инфоблоки',
        ],
    ],
];
