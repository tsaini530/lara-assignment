<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop;
use App\Product;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use Illuminate\Support\Facades\Validator;
use Artisan;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShopResource;

class ShopController extends Controller
{	
	public function __construct()
	{
		$this->content = array('success' => false);
	}
	public function index(Request $request){
		try{
			$this->content['data'] = ShopResource::collection(Shop::all());
			$this->content['success'] =  true;
			$status = 200;
			return response()->json($this->content, $status);
		}
		catch(ModelNotFoundException $ex){
			$this->content['message'] = "Shops not found.";
			$this->content['success'] =  false;
			$status = 404;
			return response()->json($this->content, $status);
		}
	}
	public function createShop(Request $request){
		$validator = Validator::make($request->all(),['name'=>'required|string|max:98']);
		if($validator->fails()) {
			$this->content['message'] = $validator->errors()->all();
			$this->content['success'] =  false;
			$status = 200;
			return response()->json($this->content, $status);
		}
		else 
		{
			try{

				$shop_name=$request->name;
				$db_name = SlugService::createSlug(Shop::class, 'db_name', $shop_name);
				$connection = DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);

				$hasDb = DB::connection($connection)->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "."'".$db_name."'");
				if(empty($hasDb)) {
					$shop=Shop::addNewShop($shop_name,$db_name);
					DB::connection($connection)->select('CREATE DATABASE '. $db_name);
					configureConnectionByName($db_name);
					Artisan::call('migrate', ['--database' => $db_name, '--path' => 'database/migrations/product']);
					$this->content['message'] =$shop_name ." shop created successfully !";	
					$this->content['data']= new ShopResource($shop);			
				}
				else {
					$this->content['message'] =$shop_name ." shop already exists !";
				}
				$this->content['success'] =  true;
				$status = 200;				
				return response()->json($this->content, $status);

			}
			catch (\ModelNotFoundException $e){
				$this->content['message'] ='something went wrong';
				$this->content['success'] =  false;
				$status = 404;
				return response()->json($this->content, $status);
			}
		}
	}
	public function allProduct($id,Request $request){
		$db_name=Shop::updteRequest($id);
		if(!$db_name){
			$this->content['message'] = "Shop not found.";
			$this->content['success'] =  false;
			$status = 404;
			return response()->json($this->content, $status);
		}
		configureConnectionByName($db_name);		
		$products = new Product();
		$products->setConnection($db_name);
		$products=$products->get();
		
		$this->content['data'] = ProductResource::collection($products) ;
		$this->content['success'] =  true;
		$status = 200;
		return response()->json($this->content, $status);
	}
	public function createProduct($id,Request $request){
		$db_name=Shop::updteRequest($id);
		if(!$db_name){
			$this->content['message'] = "Shop not found.";
			$this->content['success'] =  false;
			$status = 404;
			return response()->json($this->content, $status);
		}
		configureConnectionByName($db_name);
		$input=$request->only('category','product','discount','price');
		$validator = Validator::make($input,[
			'category'  =>  'required|string|max:200',
			'product'   =>  'required|string|max:200',
			'discount'  =>  'required|numeric|between:0,100',
			'price'     =>  'required|numeric|regex:/^\d*(\.\d{1,2})?$/'
		]);
		if($validator->fails()) {
			$errors = $validator->errors()->all();
			$this->content['message'] = $errors;
			$this->content['success'] =  false;
			$status = 400;
			return response()->json($this->content, $status);
		}
		else 
		{
			try{
				$product = new Product();
				$product->setConnection($db_name);
				$input['price']= $input['price']-($input['price']*$input['discount']/100);
				$newproduct=$product->create($input);
				$this->content['message'] = "Product Created Successfully.";
				$this->content['success'] =  true;
				$this->content['data']= new ProductResource($newproduct);	
				$status = 200;
			}
			catch(ModelNotFoundException $ex){
				$this->content['message'] = "Product not created.";
				$this->content['success'] =  false;
				$status = 404;
			}
			return response()->json($this->content, $status);
		}
		
		
	}
	public function updateProduct($id,$product_id,Request $request){
		$db_name=Shop::updteRequest($id);
		if(!$db_name){
			$this->content['message'] = "Shop not found.";
			$this->content['success'] =  false;
			$status = 404;
			return response()->json($this->content, $status);
		}
		configureConnectionByName($db_name);
		$input=$request->only('category','product','discount','price');
		$validator = Validator::make($input,[
			'category'  =>  'string|max:200',
			'product'   =>  'string|max:200',
			'discount'  =>  'numeric|between:0,100',
			'price'     =>  'numeric|regex:/^\d*(\.\d{1,2})?$/'
		]);
		if($validator->fails()) {
			$errors = $validator->errors()->all();
			$this->content['message'] = $errors;
			$this->content['success'] =  false;
			$status = 400;
			return response()->json($this->content, $status);
		}
		else 
		{
			try{
				$product = new Product();
				$product->setConnection($db_name);
				$product= $product->find($product_id);
				if(!$product){
					$this->content['message'] = "Product not found.";
					$this->content['success'] =  false;
					$status = 404;
					return response()->json($this->content, $status);

				}
				if($request->has('price') && $request->has('discount'))
				$input['price']= $input['price']-($input['price']*$input['discount']/100);
				$newproduct=$product->update($input);
				$this->content['message'] = "Product Updated Successfully.";
				$this->content['success'] =  true;
				$this->content['data']= new ProductResource($product);	
				$status = 200;
			}
			catch(ModelNotFoundException $ex){
				$this->content['message'] = "Product not found.";
				$this->content['success'] =  false;
				$status = 404;
			}
			return response()->json($this->content, $status);
		}
	}
	
	public function deleteProduct($id,$product_id,Request $request){
		$db_name=Shop::updteRequest($id);
		if(!$db_name){
			$this->content['message'] = "Shop not found.";
			$this->content['success'] =  false;
			$status = 404;
			return response()->json($this->content, $status);
		}
		configureConnectionByName($db_name);
		$products = new Product();
		$products->setConnection($db_name);
		$product= $products->find($product_id);
		if($product && $product->delete()){
			$this->content['message'] = "Product Deleted Successfully.";
			$this->content['success'] =  true;
			$status = 200;
			
		}else{
			$this->content['message'] = "Product not found.";
			$this->content['success'] =  false;
			$status = 404;
		}
		
		return response()->json($this->content, $status);

	}
}
