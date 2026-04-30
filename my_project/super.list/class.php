<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\IblockTable;
use Exception;

class MySuperListComponent extends CBitrixComponent
{
    /**
     * Метод, который выполняется при запуске компонента.
     *
     * @return void
     */
    public function executeComponent()
    {
        try {
            $this->checkModules();
            $this->getResult();
            $this->includeComponentTemplate();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }

    /**
     * Проверяем, установлен ли модуль инфоблоков.
     *
     * @throws Exception
     * @return void
     */
    protected function checkModules()
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception('Модуль "Инфоблоки" не установлен');
        }
    }

    /**
     * Основная логика получения данных.
     *
     * @throws Exception
     * @return void
     */
    protected function getResult()
    {
        // Собираем ID инфоблоков, которые нам подходят
        $iblockIds = [];

        // Если передан конкретный ID — берем только его
        if (!empty($this->arParams['IBLOCK_ID'])) {
            $iblockIds[] = (int)$this->arParams['IBLOCK_ID'];
        } elseif (!empty($this->arParams['IBLOCK_TYPE'])) {
            // Если передан только тип — ищем все инфоблоки этого типа
            $res = IblockTable::getList([
                'filter' => [
                    'IBLOCK_TYPE_ID' => $this->arParams['IBLOCK_TYPE'],
                    'ACTIVE'         => 'Y',
                ],
                'select' => ['ID'],
            ]);
            while ($row = $res->fetch()) {
                $iblockIds[] = (int)$row['ID'];
            }
        }

        if (empty($iblockIds)) {
            throw new Exception('Инфоблоки не найдены');
        }

        $filter = [
            'IBLOCK_ID' => $iblockIds,
            'ACTIVE'    => 'Y',
        ];

        // Если в вызове компонента передан фильтр - объединяем его с базовым
        if (!empty($this->arParams['FILTER']) && is_array($this->arParams['FILTER'])) {
            $filter = array_merge($filter, $this->arParams['FILTER']);
        }

        $resElements = ElementTable::getList([
            'filter' => $filter,
            'select' => ['ID', 'NAME', 'IBLOCK_ID'],
            'order'  => ['SORT' => 'ASC'],
        ]);

        $this->arResult['ITEMS'] = [];
        while ($element = $resElements->fetch()) {
            $this->arResult['ITEMS'][$element['IBLOCK_ID']][] = $element;
        }
    }
}