<?php

namespace App\Http\Controllers;

use DB;
use App\Quotation;
use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }

    public function index()
    {
      $product = Product::all();

      return response()->json($product);
    }

    public function create(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|min:3',
        'price'=> 'required|integer',
        'description' => 'required'
      ]);

      $product = new Product;
      $product->name = $request->get('name');
      $product->price = $request->get('price');
      $product->description = $request->get('description');
      $product->save();

      return response()->json($product);
    }

    public function show($id)
    {
      $product = Product::find($id);

      return response()->json($product)->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    public function update(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|min:3',
        'price'=> 'required|integer',
        'description' => 'required'
      ]);

      $return = [];
      $returnNew = [];
      $product = $request->id;
      $productName = $request->get('name');
      $productPrice = $request->get('price');
      $productDescription = $request->get('description');

      // find data with iD $product
      $updateProduct = Product::find($product);

      // if data not found then return empty data
      if (!$updateProduct){
        $return = ['status' => 200, 'message' => 'failed', 'data' => []];
        return response()->json($return);
      }

      // validate for name
      if($updateProduct->name != $productName){
        $returnNew['name']= $updateProduct->name. ' has changes to '. $productName ;
      }
      // validate for price
      if($updateProduct->price != $productPrice){
        $returnNew['price']= $updateProduct->price. ' has changes to '. $productPrice;
      }
      // validate for description
      if($updateProduct->description != $productDescription){
        $returnNew['description']= $updateProduct->description. ' has changes to '. $productDescription;
      }

      // set data from request to replace variable $updateProduct
      $updateProduct->name = $productName;
      $updateProduct->price = $productPrice;
      $updateProduct->description = $productDescription;

      // if successfully has been saved then return json
      if($updateProduct->save()){
          $return = ['status' => 200, 'message' => 'ok', 'data' => $returnNew];
        }

      return response()->json($return);
    }

    public function destroy(Request $request)
    {
      $return =[];
      $productId = $request->id;
      $product = Product::find($productId);
      if (!$product){
        $return = ['status' => 200, 'message' => 'failed', 'data' => []];
        return response()->json($return);
      }
      if($product->delete()){
        $return = ['message'=>'Product Removed successfully', 'status'=>200];
      }

      return response()->json($return);
    }
}
