<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Table;
use Illuminate\Support\Str;
use Auth;
use DB;
use App\TableDay;

class TableDayController extends Controller
{
    public function index(Request $request, $id)
    {
        $days = TableDay::where('table_id', $id)->get();
        $table_id = $id;
        return view('admin.table_day.index',compact('days', 'table_id'));
    }

    public function update(Request $request, $id)
    {
        TableDay::where('table_id', $id)->delete();

        foreach($request->day as $key=>$row){
            $days = new TableDay;
            $days->table_id = $id; 
            $days->status   = $request->status[$key]; 
            $days->open     = $request->opening[$key]; 
            $days->close    = $request->closeing[$key]; 
            $days->day      = strtolower($request->day[$key]); 
            $days->save();
        }

        return response()->json(['Update Success']);
    }
}
