<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->IncludeComponent(
    "my_project:super.list",
    "",
    [
        "IBLOCK_TYPE" => "vacancies",
        "FILTER"      => ["%NAME" => "Машинист"],
    ]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");