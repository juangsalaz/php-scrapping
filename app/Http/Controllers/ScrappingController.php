<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataExport;

class ScrappingController extends Controller
{
    public function index() {
        return Excel::download(new DataExport(), 'data-internet-providers.xlsx');
    }
}