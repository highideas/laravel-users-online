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
        return Cache::driver('file')->has($this->getCacheKey());
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
        if (empty($cache = Cache::driver('file')->get($this->getCacheKey()))) {
            return 0;
        }

        return $cache['cachedAt'];
    }

    public function setCache($seconds = 300)
    {
        return Cache::driver('file')->put(
            $this->getCacheKey(),
            $this->getCacheContent(),
            $seconds
        );
    }

    public function getCacheContent()
    {
        if (!empty($cache = Cache::driver('file')->get($this->getCacheKey()))) {
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
        Cache::driver('file')->pull($this->getCacheKey());
    }

    public function getCacheKey()
    {
        return sprintf('%s-%s', 'UserOnline', $this->id);
    }
}
