<?php

declare(strict_types=1);

namespace App\Enums;

enum LogAction: string
{
    case Creation = 'creación';
    case Modification = 'modificación';
    case Deleted = 'borrado';
    case Assignment = 'asignación';
    case Omission = 'omisión';
}
