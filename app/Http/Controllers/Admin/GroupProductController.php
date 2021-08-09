<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Group;
use App\Terms;
use App\GroupProduct;
use App\User;
use Illuminate\Support\Str;
use Auth;
use DB;

class GroupProductController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info=Group::find($id);
        
        $items = Terms::where('type', 6)->get();
        foreach($items as $item) {
            $checkProduct = GroupProduct::where('group_id', $id)->where('product_id', $item->id)->count();
            if($checkProduct){
                $item->is_selected = 1;
            } else {
                $item->is_selected = 0;
            }
            $getRestaurantName = User::where('id', $item->auth_id)->first();
            $item->rest_name = $getRestaurantName->name;
        }
        return view('admin.group_product.edit',compact('info', 'items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'items[]' => 'nullable|array' 
        ]);

        $deleteItems = GroupProduct::where('group_id', $id)->delete();
        
        foreach($request['items'] as $item) {
            $insert = GroupProduct::create([
                'group_id'  => $id,
                'product_id'    => $item
            ]);
        }     

        return response()->json('Group Product Updated');
    }
}
