<?php

namespace App\Jobs\Cache\Category;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Category;
use App\Cache\CacheManager;

class CacheList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cache_fields = [
        'id', 'name', 'slug', 'image', 'popularity', 'tree_depth',
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CacheManager $cache)
    {
        $categories = Category::get($this->cache_fields);
        $key = $categories->first()->getRouteKeyName();
        $name = Category::CACHED_LIST_NAME;

        $list = [];
        foreach ($categories as $category) {
            $list[$category->{$key}] = serialize($category);
        }
        
        $cache->forget($name);
        $cache->putArrayValues($name, $list);

        return $cache->getAllArrayValues($name);

        info('Categories list successfully cached');
    }
}
