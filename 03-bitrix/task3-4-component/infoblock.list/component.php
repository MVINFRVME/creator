<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * Задание 3.4: Компонент списка элементов инфоблока
 * Собственная реализация без копирования стандартных компонентов
 */

use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;

// Проверка модуля
if (!Loader::includeModule('iblock')) {
    ShowError('Модуль "Информационные блоки" не установлен');
    return;
}

// Параметры по умолчанию
$arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
$arParams['ELEMENT_COUNT'] = intval($arParams['ELEMENT_COUNT']) ?: 10;
$sortMap = [
    'ID' => 'ID',
    'NAME' => 'NAME',
    'SORT' => 'SORT',
    'ACTIVE_FROM' => 'ACTIVE_FROM',
    'CREATED_DATE' => 'DATE_CREATE',
];
$arParams['SORT_BY'] = $sortMap[$arParams['SORT_BY']] ?? 'SORT';
$arParams['SORT_ORDER'] = ($arParams['SORT_ORDER'] === 'DESC') ? 'DESC' : 'ASC';
$arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']) ?: 3600;

// Проверка ID инфоблока
if ($arParams['IBLOCK_ID'] <= 0) {
    ShowError('Не указан ID инфоблока');
    return;
}

// Кэширование
if ($this->startResultCache()) {
    try {
        // Получаем элементы через D7 API для эффективности
        $arResult['ITEMS'] = [];
        
        $rsElements = ElementTable::getList([
            'filter' => [
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'ACTIVE' => 'Y',
            ],
            'select' => [
                'ID',
                'NAME',
                'PREVIEW_TEXT',
                'DETAIL_TEXT',
                'IBLOCK_SECTION_ID',
            ],
            'order' => [
                $arParams['SORT_BY'] => $arParams['SORT_ORDER'],
            ],
            'limit' => $arParams['ELEMENT_COUNT'],
        ]);

        // Собираем ID разделов для пакетного запроса
        $sectionIds = [];
        $elements = [];

        while ($element = $rsElements->fetch()) {
            $elements[] = $element;
            if ($element['IBLOCK_SECTION_ID']) {
                $sectionIds[] = $element['IBLOCK_SECTION_ID'];
            }
        }

        // Получаем названия разделов одним запросом
        $sections = [];
        if (!empty($sectionIds)) {
            $rsSections = SectionTable::getList([
                'filter' => [
                    'ID' => array_unique($sectionIds),
                ],
                'select' => ['ID', 'NAME'],
            ]);

            while ($section = $rsSections->fetch()) {
                $sections[$section['ID']] = $section['NAME'];
            }
        }

        // Формируем результат
        foreach ($elements as $element) {
            $arResult['ITEMS'][] = [
                'ID' => $element['ID'],
                'NAME' => $element['NAME'],
                'DESCRIPTION' => $element['PREVIEW_TEXT'] ?: $element['DETAIL_TEXT'],
                'SECTION_NAME' => $element['IBLOCK_SECTION_ID'] 
                    ? ($sections[$element['IBLOCK_SECTION_ID']] ?? 'Без раздела')
                    : 'Без раздела',
            ];
        }

        // Проверка на пустой результат
        if (empty($arResult['ITEMS'])) {
            $this->abortResultCache();
            ShowNote('Нет элементов для отображения');
            return;
        }

        // Подключаем шаблон
        $this->includeComponentTemplate();

    } catch (\Exception $e) {
        $this->abortResultCache();
        ShowError('Ошибка получения данных: ' . $e->getMessage());
        return;
    }
}
