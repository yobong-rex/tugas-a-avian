<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class Plancontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listPlant = DB::table('m_plant')
                    ->join('m_plant_product', 'm_plant.id', '=', 'm_plant_product.id_plant')
                    ->join('m_product', 'm_plant_product.id_product', '=', 'm_product.id', 'right')
                    ->groupBy('m_plant.kode')
                    ->select(DB::raw("if(m_plant.kode is null,'-',m_plant.kode) as plant, GROUP_CONCAT(m_product.name separator ',') as product"))
                    ->orderBy('m_plant.kode','desc')
                    ->get();

        $plants = DB::table('m_plant')->get();

        return view('plant', compact('listPlant','plants'));
        return view('plantLayout', compact('listPlant','plants'));
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
        //
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

    public function addPlant(Request $request){
        try {
            
            $insertId=DB::transaction(function () use ($request){
                DB::table('m_plant')->insert(['kode'=>$request->get('kode'), 'name'=>$request->get('name')]);
                $insertId = DB::getPdo()->lastInsertId();
                return $insertId;
            });
            return response()->json(array(
                'status'=>'success',
                'msg' => 'success add new plant',
                'id'=> $insertId
            ),200);
        } catch (\PDOException $e) {
            return response()->json(array(
                'status'=>'error',
                'msg' => 'fail add new plant, please try again'
            ),200);
        }
    }

    public function addProduct(Request $request){
        try {
            DB::transaction(function () use ($request){
                DB::table('m_product')->insert(['name'=>$request->get('name')]);
                $insertIdProduct = DB::getPdo()->lastInsertId();
                DB::table('m_plant_product')->insert(['id_plant'=>$request->get('id_plant'), 'id_product'=>$insertIdProduct]);
            });
            return response()->json(array(
                'status'=>'success',
                'msg' => 'success add new product',
            ),200);
        } catch (\PDOException $e) {
            return response()->json(array(
                'status'=>'error',
                'msg' => 'fail add new product, please try again'
            ),200);
        }
    }

    public function tamplate(){
        $listPlant = DB::table('m_plant')
        ->join('m_plant_product', 'm_plant.id', '=', 'm_plant_product.id_plant')
        ->join('m_product', 'm_plant_product.id_product', '=', 'm_product.id', 'right')
        ->groupBy('m_plant.kode')
        ->select(DB::raw("if(m_plant.kode is null,'-',m_plant.kode) as plant, GROUP_CONCAT(m_product.name separator ',') as product"))
        ->orderBy('m_plant.kode','desc')
        ->get();

        $plants = DB::table('m_plant')->get();

        return view('plantLayout', compact('listPlant','plants'));
    }
}
