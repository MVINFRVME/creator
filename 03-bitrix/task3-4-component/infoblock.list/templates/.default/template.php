<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * Шаблон компонента списка элементов инфоблока
 * @var array $arResult
 * @var array $arParams
 */
?>

<div class="custom-iblock-list">
    <?php if (!empty($arResult['ITEMS'])): ?>
        <div class="iblock-items">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <div class="iblock-item">
                    <div class="item-header">
                        <h3 class="item-name"><?= htmlspecialchars($item['NAME']) ?></h3>
                        <span class="item-section"><?= htmlspecialchars($item['SECTION_NAME']) ?></span>
                    </div>
                    
                    <?php if ($item['DESCRIPTION']): ?>
                        <div class="item-description">
                            <?= htmlspecialchars($item['DESCRIPTION']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-items">Нет элементов для отображения</p>
    <?php endif; ?>
</div>
