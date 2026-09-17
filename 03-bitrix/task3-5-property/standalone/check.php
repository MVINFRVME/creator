<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
header('Content-Type: text/plain; charset=utf-8');

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/CustomSortableProperty.php';

echo "class: " . (class_exists('\\Task3_5\\CustomSortableProperty') ? 'YES' : 'NO') . "\n";
$d = \Task3_5\CustomSortableProperty::GetUserTypeDescription();
echo "USER_TYPE: " . ($d['USER_TYPE'] ?? '-') . "\n";
echo "DESCRIPTION: " . ($d['DESCRIPTION'] ?? '-') . "\n";

$found = false;
foreach (GetModuleEvents('iblock', 'OnIBlockPropertyBuildList', true) as $ev) {
    $res = ExecuteModuleEventEx($ev);
    if (is_array($res) && ($res['USER_TYPE'] ?? '') === 'CustomSortableString') {
        $found = true;
        break;
    }
}
// также проверим runtime handlers после init — на этой странице init мог не подключить наш файл
AddEventHandler('iblock', 'OnIBlockPropertyBuildList', ['\\Task3_5\\CustomSortableProperty', 'GetUserTypeDescription']);
$found2 = false;
foreach (GetModuleEvents('iblock', 'OnIBlockPropertyBuildList', true) as $ev) {
    $res = ExecuteModuleEventEx($ev);
    if (is_array($res) && ($res['USER_TYPE'] ?? '') === 'CustomSortableString') {
        $found2 = true;
        break;
    }
}
echo "in handlers now: " . ($found2 ? 'YES' : 'NO') . "\n";
echo "OK — открой свойства инфоблока Новости и ищи «Строка с сортировкой»\n";
