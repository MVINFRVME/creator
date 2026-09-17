<?php
namespace Task3_5;

/**
 * Множественная строка с индексом сортировки (задание 3.5)
 */
class CustomSortableProperty
{
    public static function GetUserTypeDescription()
    {
        return [
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'CustomSortableString',
            'DESCRIPTION' => 'Строка с сортировкой',
            // Только Multy — иначе Битрикс может рисовать поля дважды
            'GetPropertyFieldHtmlMulty' => [__CLASS__, 'GetPropertyFieldHtmlMulty'],
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
            'ConvertToDB' => [__CLASS__, 'ConvertToDB'],
            'ConvertFromDB' => [__CLASS__, 'ConvertFromDB'],
            'GetSettingsHTML' => [__CLASS__, 'GetSettingsHTML'],
            'PrepareSettings' => [__CLASS__, 'PrepareSettings'],
            'GetAdminListViewHTML' => [__CLASS__, 'GetAdminListViewHTML'],
        ];
    }

    public static function PrepareSettings($arProperty)
    {
        $settings = is_array($arProperty['USER_TYPE_SETTINGS'] ?? null)
            ? $arProperty['USER_TYPE_SETTINGS']
            : [];

        $emptyFields = (int)($settings['EMPTY_FIELDS'] ?? 3);
        $showSort = ($settings['SHOW_SORT'] ?? 'Y') === 'Y' ? 'Y' : 'N';
        $fieldWidth = (int)($settings['FIELD_WIDTH'] ?? 200);

        return [
            'EMPTY_FIELDS' => $emptyFields > 0 ? $emptyFields : 3,
            'SHOW_SORT' => $showSort,
            'FIELD_WIDTH' => $fieldWidth > 0 ? $fieldWidth : 200,
        ];
    }

    public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
    {
        $arSettings = static::PrepareSettings($arProperty);
        $name = htmlspecialcharsbx($strHTMLControlName['NAME']);

        $html = '<tr><td>Количество пустых полей для нового элемента:</td>';
        $html .= '<td><input type="text" size="5" name="' . $name . '[EMPTY_FIELDS]" value="'
            . (int)$arSettings['EMPTY_FIELDS'] . '"></td></tr>';

        $html .= '<tr><td>Показывать поле сортировки:</td><td>';
        $html .= '<input type="hidden" name="' . $name . '[SHOW_SORT]" value="N">';
        $html .= '<input type="checkbox" name="' . $name . '[SHOW_SORT]" value="Y"'
            . ($arSettings['SHOW_SORT'] === 'Y' ? ' checked' : '') . '>';
        $html .= '</td></tr>';

        $html .= '<tr><td>Ширина поля ввода (px):</td>';
        $html .= '<td><input type="text" size="5" name="' . $name . '[FIELD_WIDTH]" value="'
            . (int)$arSettings['FIELD_WIDTH'] . '"></td></tr>';

        return $html;
    }

    public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        // Для одиночного свойства; для множественного Битрикс зовёт Multy
        return static::renderRow(
            $arProperty,
            is_array($value) ? $value : ['VALUE' => $value, 'DESCRIPTION' => 500],
            [
                'VALUE' => $strHTMLControlName['VALUE'],
                'DESCRIPTION' => $strHTMLControlName['DESCRIPTION'] ?? '',
            ]
        );
    }

    public static function GetPropertyFieldHtmlMulty($arProperty, $value, $strHTMLControlName)
    {
        $arSettings = static::PrepareSettings($arProperty);
        $html = '';

        if (!is_array($value)) {
            $value = [];
        }

        // убираем пустые хвосты из массива значений
        $filtered = [];
        foreach ($value as $id => $one) {
            if (!is_array($one)) {
                $one = ['VALUE' => $one, 'DESCRIPTION' => 500];
            }
            if (trim((string)($one['VALUE'] ?? '')) === '') {
                continue;
            }
            $filtered[$id] = $one;
        }
        $filtered = static::SortValues($filtered);

        foreach ($filtered as $valueId => $valueData) {
            $control = static::buildControlNames($strHTMLControlName, $valueId);
            $html .= static::renderRow($arProperty, $valueData, $control) . '<br>';
        }

        // Пустые поля: если уже есть значения — одно; иначе EMPTY_FIELDS
        $emptyCount = $filtered === [] ? (int)$arSettings['EMPTY_FIELDS'] : 1;
        for ($i = 0; $i < $emptyCount; $i++) {
            $control = static::buildControlNames($strHTMLControlName, 'n' . $i);
            $html .= static::renderRow(
                $arProperty,
                ['VALUE' => '', 'DESCRIPTION' => 500 + ($i * 10)],
                $control
            ) . '<br>';
        }

        return $html;
    }

    protected static function buildControlNames(array $strHTMLControlName, $key)
    {
        $valueName = $strHTMLControlName['VALUE'] . '[' . $key . '][VALUE]';

        // Как в ядре Битрикс: DESCRIPTION отдельно, без [DESCRIPTION] на конце
        if (!empty($strHTMLControlName['DESCRIPTION'])) {
            $descrName = $strHTMLControlName['DESCRIPTION'] . '[' . $key . ']';
        } else {
            $descrName = $strHTMLControlName['VALUE'] . '[' . $key . '][DESCRIPTION]';
        }

        return [
            'VALUE' => $valueName,
            'DESCRIPTION' => $descrName,
        ];
    }

    protected static function renderRow($arProperty, $value, $strHTMLControlName)
    {
        $arSettings = static::PrepareSettings($arProperty);
        $val = is_array($value) ? (string)($value['VALUE'] ?? '') : (string)$value;
        $sort = is_array($value)
            ? (int)($value['DESCRIPTION'] ?? $value['SORT'] ?? 500)
            : 500;

        $html = '<span class="custom-sortable-property">';
        if ($arSettings['SHOW_SORT'] === 'Y' && !empty($strHTMLControlName['DESCRIPTION'])) {
            $html .= '<input type="number" name="' . htmlspecialcharsbx($strHTMLControlName['DESCRIPTION'])
                . '" value="' . $sort . '" style="width:70px;margin-right:8px;" title="Индекс сортировки">';
        }
        $html .= '<input type="text" name="' . htmlspecialcharsbx($strHTMLControlName['VALUE'])
            . '" value="' . htmlspecialcharsbx($val) . '" style="width:' . (int)$arSettings['FIELD_WIDTH'] . 'px;">';
        $html .= '</span>';

        return $html;
    }

    public static function ConvertToDB($arProperty, $value)
    {
        if (!is_array($value)) {
            $value = ['VALUE' => $value, 'DESCRIPTION' => 500];
        }

        // DESCRIPTION с формы может прийти строкой отдельно
        $text = trim((string)($value['VALUE'] ?? ''));
        if ($text === '') {
            return false;
        }

        $sort = $value['DESCRIPTION'] ?? $value['SORT'] ?? 500;
        if (is_array($sort)) {
            $sort = 500;
        }

        return [
            'VALUE' => $text,
            'DESCRIPTION' => (string)(int)$sort,
        ];
    }

    public static function ConvertFromDB($arProperty, $value)
    {
        $sort = (int)($value['DESCRIPTION'] ?? 500);
        return [
            'VALUE' => $value['VALUE'] ?? '',
            'DESCRIPTION' => $sort,
            'SORT' => $sort,
        ];
    }

    public static function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
    {
        $text = is_array($value) ? (string)($value['VALUE'] ?? '') : (string)$value;
        $sort = is_array($value) ? (int)($value['DESCRIPTION'] ?? 500) : 500;
        if ($text === '') {
            return '';
        }
        return htmlspecialcharsbx($text) . ' <small>[' . $sort . ']</small>';
    }

    public static function SortValues($values)
    {
        if (!is_array($values) || empty($values)) {
            return $values;
        }

        uasort($values, static function ($a, $b) {
            $sortA = is_array($a) ? (int)($a['DESCRIPTION'] ?? $a['SORT'] ?? 500) : 500;
            $sortB = is_array($b) ? (int)($b['DESCRIPTION'] ?? $b['SORT'] ?? 500) : 500;
            return $sortA <=> $sortB;
        });

        return $values;
    }

    /**
     * Только сортировка на месте (ключи ID не трогаем — иначе Битрикс плодит дубли)
     */
    public static function onBeforeElementSave(&$arFields)
    {
        if (empty($arFields['PROPERTY_VALUES']) || !is_array($arFields['PROPERTY_VALUES'])) {
            return;
        }

        foreach ($arFields['PROPERTY_VALUES'] as $propId => $values) {
            if (!is_array($values) || $values === []) {
                continue;
            }

            $prop = \CIBlockProperty::GetByID($propId)->Fetch();
            if (!$prop || ($prop['USER_TYPE'] ?? '') !== 'CustomSortableString') {
                continue;
            }

            // Убираем пустые, ключи сохраняем
            foreach ($values as $key => $one) {
                if (!is_array($one)) {
                    $one = ['VALUE' => $one];
                    $values[$key] = $one;
                }
                if (trim((string)($one['VALUE'] ?? '')) === '') {
                    unset($values[$key]);
                }
            }

            $arFields['PROPERTY_VALUES'][$propId] = static::SortValues($values);
        }
    }
}
