<?php

namespace App\Http\Controllers;

use App\Models\ItemCondition;
use App\Models\PrimaryCategory;
use Illuminate\Http\Request;

class SellController extends Controller
{
    //
    public function showSellForm(){
        $conditions = ItemCondition::orderBy('sort_no')->get();
        $categories = PrimaryCategory::orderBy('sort_no')->get();

        return view('sell')->with('conditions',$conditions)->with('categories',$categories);
    }
}
