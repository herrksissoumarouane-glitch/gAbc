<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function AllAdmin(){
        $alladminuser = User::where('role','admin')->latest()->get();
        return view('Admin.all_admin',compact('alladminuser'));
    }// End Mehtod 
}
