<?php

namespace HighIdeas\UsersOnline\Models;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

trait UsersOnline
{

    public function isOnline()
    {
         return Cache::has($this->getCacheKey());
    }

    public function setCache($minutes = 5)
    {
        $expiresAt = Carbon::now()->addMinutes($minutes);
        return Cache::put($this->getCacheKey(), $this, $expiresAt);
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
