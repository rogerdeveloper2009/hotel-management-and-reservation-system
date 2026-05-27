<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case MtnMobileMoney = 'mtn_mobile_money';
    case AirtelMoney = 'airtel_money';
    case BankTransfer = 'bank_transfer';
    case CardPayment = 'card_payment';
}

