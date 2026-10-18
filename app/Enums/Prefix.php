<?php

namespace App\Enums;

enum Prefix: string
{
    case PURCHASE = 'PUR';
    case SALE = 'SAL';

    case PURCHASE_RETURN = 'PRET';
    case SALE_RETURN = 'SRET';

    case PURCHASE_PAYMENT = 'PPAY';
    case SALE_PAYMENT = 'SPAY';

    case CUSTOMER = 'CUS';
    case SUPPLIER = 'SUP';

    case PRODUCT = 'PRO';
    case VARIANT = 'VAR';
    case STOCK_ADJUSTMENT = 'ADJ';
    case STOCK_TRANSFER = 'TRF';

    case EXPENSE = 'EXP';
    case INCOME = 'INC';

    case QUOTATION = 'QUO';
    case SALES_ORDER = 'SO';
    case DELIVERY = 'DEL';

    case CREDIT_NOTE = 'CN';
    case DEBIT_NOTE = 'DN';
}
