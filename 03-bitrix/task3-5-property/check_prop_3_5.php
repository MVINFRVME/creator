<?php
/**
 * Диагностика: открыть в браузере http://site1.local:8080/local/check_prop_3_5.php
 */
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

header('Content-Type: text/plain; charset=utf-8');

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

echo "module installed: " . (ModuleManager::isModuleInstalled('task3_5_property') ? 'YES' : 'NO') . "\n";
echo "module include: " . (Loader::includeModule('task3_5_property') ? 'YES' : 'NO') . "\n";
echo "class exists: " . (class_exists('\\Task3_5\\CustomSortableProperty') ? 'YES' : 'NO') . "\n\n";

if (class_exists('\\Task3_5\\CustomSortableProperty')) {
    echo "description:\n";
    print_r(\Task3_5\CustomSortableProperty::GetUserTypeDescription());
}

echo "\n--- handlers OnIBlockPropertyBuildList ---\n";
$i = 0;
foreach (GetModuleEvents('iblock', 'OnIBlockPropertyBuildList', true) as $arEvent) {
    $i++;
    echo "#$i ";
    print_r($arEvent);
    $res = ExecuteModuleEventEx($arEvent);
    if (is_array($res) && ($res['USER_TYPE'] ?? '') === 'CustomSortableString') {
        echo ">>> FOUND CustomSortableString\n";
    }
}
echo "total handlers: $i\n";
