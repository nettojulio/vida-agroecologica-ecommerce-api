<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banca;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $banca = $user->papel->banca;

        $produtos = $banca->produtos;
        if(!$produtos ||  sizeof($produtos) == 0 ){
            return response()->json(['erro' =>'Não foi encontrar os produtos ou a banca está vazia'],400);
        }
        return response()->json(['produtos' => $produtos],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $banca = $user->papel->banca;

        DB::beginTransaction();
        $produto = $banca->produtos()->create($request->all());

        if(!$produto){
            return response()->json(['erro' =>'Não foi possível criar o produto'],400);
        }
        $banca->save();
        $produto->banca;
        DB::commit();
        return response()->json(['produto' => $produto],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = Produto::find($id);
        if(!$produto){
            return response()->json(['erro' =>'Não foi encontrar o produto'],400);
        }
        return response()->json(['produto' => $produto],200);
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
        DB::beginTransaction();

        $produto = Produto::find($id);
        if(!$produto){

            return response()->json(['erro'=>'Não foi encontrar o produto'],404);
        }
        $produto->fill($request->all());
        $produto->save();
        $produto->banca;
        DB::commit();
        return response()->json(['produto'=>$produto],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $produto = Produto::find($id);
        $produto->delete();
        DB::commit();
        return response()->noContent();
    }
}