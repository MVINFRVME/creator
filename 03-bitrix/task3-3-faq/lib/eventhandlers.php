<?php
namespace Task3_3;

use Bitrix\Main\Loader;
use Bitrix\Main\Mail\Event;

/**
 * Обработчики событий для FAQ модуля
 */
class EventHandlers
{
    /** @var array<int, string> DETAIL_TEXT до обновления */
    protected static $oldDetailText = [];

    /**
     * Запоминаем, был ли ответ до сохранения
     *
     * @param array $arFields
     */
    public static function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if (empty($arFields['ID'])) {
            return;
        }

        if (!Loader::includeModule('iblock')) {
            return;
        }

        $element = \CIBlockElement::GetByID($arFields['ID'])->Fetch();
        if (!$element) {
            return;
        }

        self::$oldDetailText[(int)$arFields['ID']] = (string)($element['DETAIL_TEXT'] ?? '');
    }

    /**
     * При первом заполнении детального описания (ответа) отправляет email
     *
     * @param array $arFields Поля элемента
     */
    public static function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        try {
            if (empty($arFields['ID'])) {
                return;
            }

            $elementId = (int)$arFields['ID'];

            if (!Loader::includeModule('iblock')) {
                return;
            }

            $element = \CIBlockElement::GetByID($elementId)->Fetch();
            if (!$element) {
                return;
            }

            $iblock = \CIBlock::GetByID($element['IBLOCK_ID'])->Fetch();
            if (!$iblock || $iblock['CODE'] !== 'faq') {
                return;
            }

            $oldDetail = self::$oldDetailText[$elementId] ?? '';
            unset(self::$oldDetailText[$elementId]);

            $detailText = '';
            if (array_key_exists('DETAIL_TEXT', $arFields) && $arFields['DETAIL_TEXT'] !== null) {
                $detailText = trim((string)$arFields['DETAIL_TEXT']);
            } else {
                $detailText = trim((string)($element['DETAIL_TEXT'] ?? ''));
            }

            // Ответ пустой или уже был раньше — не шлём
            if ($detailText === '' || trim($oldDetail) !== '') {
                return;
            }

            $dbProps = \CIBlockElement::GetProperty(
                $element['IBLOCK_ID'],
                $elementId,
                [],
                ['CODE' => 'EMAIL']
            );

            $userEmail = '';
            while ($prop = $dbProps->Fetch()) {
                if ($prop['CODE'] === 'EMAIL' && !empty($prop['VALUE'])) {
                    $userEmail = $prop['VALUE'];
                    break;
                }
            }

            if ($userEmail === '') {
                return;
            }

            $siteId = 's1';
            $iblockSites = \CIBlock::GetSite($element['IBLOCK_ID']);
            if ($site = $iblockSites->Fetch()) {
                $siteId = $site['SITE_ID'];
            }

            $arEventFields = [
                'USER_EMAIL' => $userEmail,
                'QUESTION' => $element['PREVIEW_TEXT'] ?: $element['NAME'],
                'ANSWER' => $detailText,
                'SITE_NAME' => \COption::GetOptionString('main', 'site_name', 'Сайт'),
            ];

            Event::send([
                'EVENT_NAME' => 'FAQ_ANSWER',
                'LID' => $siteId,
                'C_FIELDS' => $arEventFields,
            ]);

            AddMessage2Log(
                sprintf(
                    'Отправлен ответ на вопрос FAQ #%d на email: %s',
                    $elementId,
                    $userEmail
                ),
                'task3_3_faq'
            );

        } catch (\Exception $e) {
            AddMessage2Log('Ошибка отправки ответа FAQ: ' . $e->getMessage(), 'task3_3_faq');
        }
    }
}
