<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock')) {
    return;
}

// Получаем все типы инфоблоков для выпадающего списка
$arTypes = CIBlockParameters::GetIBlockTypes();

// Получаем сами инфоблоки выбранного типа (если тип уже выбран)
$arIBlocks = [0 => "--- Все ---"];

$iblockFilter = [
    'ACTIVE' => 'Y',
];
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $iblockFilter['IBLOCK_TYPE_ID'] = $arCurrentValues['IBLOCK_TYPE'];
}

$dbIBlock = CIBlock::GetList(["SORT" => "ASC"], $iblockFilter);
while ($arr = $dbIBlock->Fetch()) {
    $arIBlocks[$arr["ID"]] = "[" . $arr["ID"] . "] " . $arr["NAME"];
}

$arComponentParameters = [
    "PARAMETERS" => [
        "IBLOCK_TYPE" => [
            "PARENT"  => "BASE",
            "NAME"    => "Тип инфоблока",
            "TYPE"    => "LIST",
            "VALUES"  => $arTypes,
            "REFRESH" => "Y",
        ],
        "IBLOCK_ID" => [
            "PARENT"             => "BASE",
            "NAME"               => "ID инфоблока",
            "TYPE"               => "LIST",
            "VALUES"             => $arIBlocks,
            "ADDITIONAL_VALUES"  => "Y",
        ],
        "CACHE_TIME" => [
            "DEFAULT" => 36000000,
        ],
    ],
];