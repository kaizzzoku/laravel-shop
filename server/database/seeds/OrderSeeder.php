<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class OrderSeeder extends Seeder
{
	public const ORDERS_TO_USERS = 1.2;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$orders = [];
    	$users = User::get();
    	$orders_count = round($users->count() * self::ORDERS_TO_USERS);

    	for ($i=0; $i < $orders_count; $i++) { 
    		$orders[] = $this->makeRow(
    			$users->random(1)->first()->getKey()	
    		);
    	}

		DB::table('orders')->insert($orders);
    }

    private function makeRow(string $user_id)
    {
    	return [
    		'customer_id' => $user_id,
    		'created_at' => now(),
    		'updated_at' => now(),
    	];
    }
}
