<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDocument extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'lot_no',
        'category_of_document',
        'work_order_no',
        'vendor_name',
        'vendor_movement_date',
        'file_barcode',
        'box_barcode',
        'date_added_to_vendor',
        'status',
        'document_id',
        'document_type',
        'document_unique_no',
        'dispatch_no',
    ];
}
