<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Size;
use Illuminate\Support\Str;
use Auth;
use DB;

class SizeController extends Controller
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
        return  view('admin.size.index',compact('req'));
        


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
        $validatedData = $request->validate([
            'name_en' => 'required|max:100',
            'name_ar' => 'required|max:100'
        ]);

        $auth_id=Auth::id();

        $size=new Size;
        $size->name_en = $request->name_en;
        $size->name_ar = $request->name_ar;
        $size->restaurant_id = $auth_id;
        $size->status = 1;
        $size->save();
        return response()->json('Size Created');

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
        $info=size::find($id);
        return view('admin.size.edit',compact('info'));
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
            'name_en' => 'required|max:100',
            'name_ar' => 'required|max:100'
        ]);

        // $checktitle= Group::where('name_en',$request->name)->orWhere('name_ar',$request->name)->where('id','!=',$id)->first();

        // if (!empty($checktitle)) {
        //     return response()->json(['Group Name Must Be unique'],401);
        // }        

        $size=Size::find($id);
        $size->name_en=$request->name_en;
        $size->name_ar=$request->name_ar;
        $size->save();
        return response()->json('Size Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {   
        if ($request->method=='delete') {
             if ($request->ids) {
                foreach ($request->ids as $id) {
                    Size::destroy($id);
                }
             }
        }
       
        
        return response()->json('Size Removed');
    }
}
