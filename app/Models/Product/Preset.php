<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Option;
use App\Models\Product\Attribute;

class Preset extends Model
{
	private const DEFAULT_ID = 1;

    protected $table = 'products_presets';

    protected $fillable = [
    	'name',
    ];

    /*|==========| Relationships |==========|*/

    public function options()
    {
    	return $this->belongsToMany(
            Option::class,
            'products_presets_to_options_rel',
            'preset_id',
            'option_id'
        );
    }

    public function attributes()
    {
        return $this->belongsToMany(
            Attribute::class,
            'preset_id'
        );
    }

    /*|==========| Scopes |==========|*/

    public function scopeDefault($query)
    {
    	return $query->where($this->getKeyName(),self::DEFAULT_ID);
    }
    
    public static function getDefaultID() 
    {
    	return self::DEFAULT_ID;
    }
}
