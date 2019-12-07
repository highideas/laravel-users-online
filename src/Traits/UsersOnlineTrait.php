<?php

namespace HighIdeas\UsersOnline\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait UsersOnlineTrait
{
    public static function allOnline()
    {
        $className = self::getClass();
        return (new $className())->all()->filter->isOnline();
    }

    public static function getClass()
    {
        return get_class();
    }

    public function isOnline()
    {
        return Cache::has($this->getCacheKey());
    }

    public static function leastRecentOnline()
    {
        return self::allOnline()
            ->sortBy(function ($user) {
                return $user->getCachedAt();
            });
    }

    public static function mostRecentOnline()
    {
        return self::allOnline()
            ->sortByDesc(function ($user) {
                return $user->getCachedAt();
            });
    }

    public function getCachedAt()
    {
        if (empty($cache = Cache::get($this->getCacheKey()))) {
            return 0;
        }

        return $cache['cachedAt'];
    }

    public function setCache($seconds = 300)
    {
        return Cache::put(
            $this->getCacheKey(),
            $this->getCacheContent(),
            $seconds
        );
    }

    public function getCacheContent()
    {
        if (!empty($cache = Cache::get($this->getCacheKey()))) {
            return $cache;
        }
        $cachedAt = Carbon::now();

        return [
            'cachedAt' => $cachedAt,
            'user'     => $this,
        ];
    }

    public function pullCache()
    {
        Cache::pull($this->getCacheKey());
    }

    public function getCacheKey()
    {
        return sprintf('%s-%s', 'UserOnline', $this->id);
    }
}
