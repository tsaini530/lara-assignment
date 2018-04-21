<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Shop extends Model
{
	use Sluggable;
	public function sluggable()
	{
		return [
			'db_name' => [
				'source' => 'shop_name',
				'separator' => '_'
			]
		];
	}
	public static function addNewShop($shop_name,$db_name){
		$shop= new Shop();
		$shop->shop_name=$shop_name;
		$shop->db_name=$db_name;
		$shop->save();
		return $shop;
	}
	public static function updteRequest($id){
		$shop = Shop::find($id);
		if($shop){
			$shop->requests++;
			$shop->save();
			return $shop->db_name;
		}
		return false;
	}
}
