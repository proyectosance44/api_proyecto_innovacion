<?php

declare(strict_types=1);

function fixSpaces(string $data): string
{
    return preg_replace("/\s+/", ' ', $data);
}
