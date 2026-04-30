<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<div class="super-list">
    <?php foreach ($arResult['ITEMS'] as $iblockId => $elements): ?>
        <div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 10px;">
            <h3>Инфоблок ID: <?php echo $iblockId; ?></h3>
            <ul>
                <?php foreach ($elements as $item): ?>
                    <li>[<?php echo $item['ID']; ?>] <?php echo $item['NAME']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>