<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'file_path',
        'company_name',
        'cnpj',
        'date',
        'total_value',
        'items',
        'category',
        'arquivo_path',
        'valor_total', 
        'data_emissao'
    ];
    
    protected $casts = [
        'items' => 'array',
    ];
}