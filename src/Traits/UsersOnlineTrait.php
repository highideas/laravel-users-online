<?php

namespace HighIdeas\UsersOnline\Traits;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

trait UsersOnlineTrait
{

    public function allOnline()
    {
        return $this->all()->filter->isOnline();
    }

    public function isOnline()
    {
         return Cache::has($this->getCacheKey());
    }

    public function leastRecentOnline()
    {
        return $this->allOnline()
            ->sortBy(function ($user) {
                return $user->getCachedAt();
            });
    }

    public function mostRecentOnline()
    {
        return $this->allOnline()
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

    public function setCache($minutes = 5)
    {
        return Cache::put($this->getCacheKey(), $this->getCacheContent(), $minutes);
    }

    public function getCacheContent()
    {
        if (!empty($cache = Cache::get($this->getCacheKey()))) {
            return $cache;
        }
        $cachedAt = Carbon::now();
        return [
            'cachedAt' => $cachedAt,
            'user' => $this,
        ];
    }

    public function pullCache()
    {
        Cache::pull($this->getCacheKey());
    }

    public function getCacheKey()
    {
        return sprintf('%s-%s', "UserOnline", $this->id);
    }
}
