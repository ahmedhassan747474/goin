<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Group;
use Illuminate\Support\Str;
use Auth;
use DB;

class GroupController extends Controller
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
        return  view('admin.group.index',compact('req'));
        


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

        $group=new Group;
        $group->name_en=$request->name_en;
        $group->name_ar=$request->name_ar;
        $group->save();
        return response()->json('group created');

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
        $info=Group::find($id);
        return view('admin.group.edit',compact('info'));
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

        $group=Group::find($id);
        $group->name_en=$request->name_en;
        $group->name_ar=$request->name_ar;
        $group->save();
        return response()->json('Group Updated');
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
                    Group::destroy($id);
                }
             }
        }
       
        
        return response()->json('Group Removed');
    }
}
