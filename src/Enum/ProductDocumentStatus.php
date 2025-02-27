<?php

namespace App\Enum;

enum ProductDocumentStatus: string
{
    case New = 'new';
    case Processed = 'processed';
}
