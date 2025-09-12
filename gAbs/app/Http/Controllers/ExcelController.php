<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
class ExcelController extends Controller
{
    public function index(){
        return view('excel');
    }

    public function import(Request $request)
    {
        Excel::import(new UserImport, $request->file('excel_file'));
    }
}
