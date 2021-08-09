<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Table;
use Illuminate\Support\Str;
use Auth;
use DB;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!empty($request->query)) {
            $req=$request->qry;
        }
        else{
            $req='';
        }
        return  view('admin.table.index',compact('req'));



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'name_en'   => 'required|max:100',
            'name_ar'   => 'required|max:100',
            'no_guest'  => 'required|integer',
            'status'    => 'required'
        ]);

        $auth_id=Auth::id();

        $table=new Table;
        $table->name_en         = $request->name_en;
        $table->name_ar         = $request->name_ar;
        $table->no_guest        = $request->no_guest;
        $table->status          = $request->status;
        $table->restaurant_id   = $auth_id;
        $table->save();
        return response()->json('Table Created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info=Table::find($id);
        return view('admin.table.edit',compact('info'));
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
            'name_en'   => 'required|max:100',
            'name_ar'   => 'required|max:100',
            'no_guest'  => 'required|integer',
            'status'    => 'required'
        ]);

        $table=Table::find($id);
        $table->name_en         = $request->name_en;
        $table->name_ar         = $request->name_ar;
        $table->no_guest        = $request->no_guest;
        $table->status          = $request->status;
        // $table->restaurant_id   = $auth_id;
        $table->save();
        return response()->json('Table Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if ($request->method=='delete') {
             if ($request->ids) {
                foreach ($request->ids as $id) {
                    Table::destroy($id);
                }
             }
        }


        return response()->json('Table Removed');
    }
}
