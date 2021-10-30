<?php

namespace App\Http\Controllers;

use App\Models\item;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    //
    public function showItems(Request $request) {
        $query = Item::query();
 
        // カテゴリで絞り込み
        if ($request->filled('category')) {
            list($categoryType, $categoryID) = explode(':', $request->input('category'));

            if ($categoryType === 'primary') {
                $query->whereHas('secondaryCategory', function ($query) use ($categoryID) {
                    $query->where('primary_category_id', $categoryID);
                });
            } else if ($categoryType === 'secondary') {
                $query->where('secondary_category_id', $categoryID);
            }
        }

        if($request->filled('keyword')){
            $keyword = '%'. $this->escape($request->input('keyword')). '%';
            $query->where(function($query)use($keyword){
                $query->where('name','LIKE',$keyword);
                $query->orWhere('description','LIKE',$keyword);
            });
        }

        $items = $query->orderBy('id', 'DESC')
           ->paginate(2);

       return view('items.items')
           ->with('items', $items);
    }

    public function showItemDetail(Item $item)
    {
        return view('items.item_detail')->with('item',$item);
    }

    public function escape(string $value) {
        return str_replace(
            ['\\', '%', '_'],
            ['\\\\', '\\%', '\\_'],
            $value
        );
    }

    public function showBuyItemForm(Item $item)
    {
        if($item->isStateSelling){
            abort('404');
        }

        return view('items.item_buy_form')->with('item', $item);
    }
}
