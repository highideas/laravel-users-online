<?php

namespace HighIdeas\UsersOnline\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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
        $sorted = $this->allOnline()
            ->sortBy(function ($user) {
                return $user->getCachedAt();
            });

        return $sorted->values()->all();
    }

    public function mostRecentOnline()
    {
        $sorted =  $this->allOnline()
            ->sortByDesc(function ($user) {
                return $user->getCachedAt();
            });

        return $sorted->values()->all();
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
