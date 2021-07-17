<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MonthMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  str  $yearMonth('yyyy-mm')
     * @return \Illuminate\Http\Response
     */
    public function show($yearMonth)
    {
        $monthMenu = [];
        $data = Menu::whereDate('date','LIKE',"$yearMonth%")->get();
        if (!$data->isEmpty()) {
            foreach($data as $d) {
                $monthMenu[] = $d['date'];
            }
        }
        return response()->json(['monthmenu' => $monthMenu]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
