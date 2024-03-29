<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request) {

        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:50',
            'product_type' => 'required|in:snack,drink,fruit,drug,groceries,cigarette,make-up',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        Product::create([
            'product_name' => $payload['product_name'],
            'product_type' => $payload['product_type'],
            'product_price' => $payload['product_price'],
            'expired_at' => $payload['expired_at']
        ]);

        return response()->json([
            'msg' => 'Data Produk Berhasil Disimpan'
        ],201);
    }

    function showAll(){
        $products = Product::all();

        return response()->json([
            'msg' => 'Data Keseluruhan',
            'data' => $products
        ],200);
    }

    function showById($id){
        $product = Product::where('id', $id)->first();

        if($product) {
            return response()->json([
                'msg' => 'Data produk dengan ID: '.$id,
                'data' => $product
            ],200);
        }
        return response()->json([
            'msg' => 'Data Produk Dengan ID: '.$id. 'Tidak Ditemukan',

        ],404);
    }

    function showByName($product_name){
        $product = Product::where('product_name','LIKE','%'.$product_name.'%')->get();

        if($product->count() > 0) {
            return response()->json([
                'msg' => 'Data produk dengan nama yang mirip: '.$product_name,
                'data' => $product
            ],200);

        }

        return response()->json([
            'msg' => 'Data produk dengan namamn yang mirip '.$product_name. 'Tidak ditemukan',

        ],404);
    
    }

    public function update(Request $request,$id) {

        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:50',
            'product_type' => 'required|in:snack,drink,fruit,drug,groceries,cigarette,make-up',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        Product::where('id', $id)->update([
            'product_name' => $payload['product_name'],
            'product_type' => $payload['product_type'],
            'product_price' => $payload['product_price'],
            'expired_at' => $payload['expired_at']
        ]);

        return response()->json([
            'msg' => 'Data Produk Berhasil Diubah'
        ],201);
    }

    public function delete($id) {
        $product = Product::where('id',$id)->get();

        if($product){

            Product::where('id',$id)->delete();

            return response()->json([
                'msg' => 'Data produk dengan ID: '.$id.' berhasil dihapus',
            ],200);

        }

        return response()->json([
            'msg' => 'Data produk dengan ID: '.$id.' Tidak berhasil dihapus',

        ],404);
    }
}
