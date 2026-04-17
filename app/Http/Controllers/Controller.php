<?php

namespace App\Http\Controllers;

use App\Models\Invoice; 
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        
        Invoice::create($request->all());

        return redirect()->back()->with('success', 'Nota processada com sucesso!');
    }
}