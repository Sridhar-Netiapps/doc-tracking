<?php

namespace App\Exports\Concerns;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * Prevents precision loss for long numeric IDs (account numbers, CIF IDs, etc.).
 *
 * PhpSpreadsheet's DefaultValueBinder converts any is_numeric() string to float.
 * IEEE 754 double has only 15 significant decimal digits, so 16+ digit account
 * numbers lose their trailing digits (become 0) before the cell format is applied.
 *
 * Usage: export class must extend DefaultValueBinder and implement WithCustomValueBinder.
 */
trait ForceNumericStringAsText
{
    public function bindValue(Cell $cell, $value): bool
    {
        if (is_string($value) && preg_match('/^\d{16,}$/', trim($value))) {
            $cell->setValueExplicit(trim($value), DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
