<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

// Получаем список типов инфоблоков
$arIBlockTypes = CIBlockParameters::GetIBlockTypes();

// Получаем список инфоблоков
$arIBlocks = [];
$rsIBlock = CIBlock::GetList(
    ['SORT' => 'ASC'],
    ['ACTIVE' => 'Y']
);
while ($arIBlock = $rsIBlock->Fetch()) {
    $arIBlocks[$arIBlock['ID']] = '[' . $arIBlock['ID'] . '] ' . $arIBlock['NAME'];
}

$arComponentParameters = [
    'GROUPS' => [
        'SETTINGS' => [
            'NAME' => 'Настройки',
        ],
        'SORT' => [
            'NAME' => 'Сортировка',
        ],
    ],
    'PARAMETERS' => [
        'IBLOCK_TYPE' => [
            'PARENT' => 'SETTINGS',
            'NAME' => 'Тип инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockTypes,
            'REFRESH' => 'Y',
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'SETTINGS',
            'NAME' => 'Инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'REFRESH' => 'Y',
        ],
        'ELEMENT_COUNT' => [
            'PARENT' => 'SETTINGS',
            'NAME' => 'Количество элементов',
            'TYPE' => 'STRING',
            'DEFAULT' => '10',
        ],
        'SORT_BY' => [
            'PARENT' => 'SORT',
            'NAME' => 'Поле сортировки',
            'TYPE' => 'LIST',
            'VALUES' => [
                'ID' => 'ID',
                'NAME' => 'Название',
                'SORT' => 'Индекс сортировки',
                'ACTIVE_FROM' => 'Дата начала активности',
                'CREATED_DATE' => 'Дата создания',
            ],
            'DEFAULT' => 'SORT',
        ],
        'SORT_ORDER' => [
            'PARENT' => 'SORT',
            'NAME' => 'Порядок сортировки',
            'TYPE' => 'LIST',
            'VALUES' => [
                'ASC' => 'По возрастанию',
                'DESC' => 'По убыванию',
            ],
            'DEFAULT' => 'ASC',
        ],
        'CACHE_TIME' => [
            'DEFAULT' => 3600,
        ],
    ],
];
