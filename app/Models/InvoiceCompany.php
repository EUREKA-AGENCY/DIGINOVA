<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceCompany extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'bp', 'siege_social', 'address'];
}
