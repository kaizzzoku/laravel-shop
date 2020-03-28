<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Order extends Model
{
	public const SUCCEEDED = 'succeeded';
	public const PENDING = 'pending';

	protected $fillable = [
		'customer_id',
		'payment_id',
		'total_price',
	];

	/*|==========| Accessors |==========|*/

	public function scopeWherePayment($query, $payment_id)
	{
		return $query->where('payment_id', $payment_id);
	}

	/*|==========| Relationships |==========|*/

	public function customer()
	{
		return $this->belongsTo(User::class, 'customer_id', 'id');
	}

	public function products()
	{
		return $this->belongsToMany(
			Product::class,
			'order_product',
			'order_id',
			'product_id'
		)->withPivot('options');
	}

	/*|====================|*/

	public function getTotalPrice()
	{
		return $this->getAttributeValue('total_price');
	}

	public static function createFromCart(Cart $cart, $payment_id)
	{
		$order = static::create([
			'customer_id' => auth()->user()->getKey(),
			'total_price' => $cart->getTotalPrice(),
			'payment_id' => $payment_id,
		]);

		$products = [];
		array_map(function ($item) use (&$products) {
			$products[$item->getProduct()->getKey()] = [
				'options' => json_encode($item->getOptions()),
				'count' => $item->getCount(),
			];
		}, $cart->getItems());

		$order->products()->attach($products);

		return $order;
	}
}
