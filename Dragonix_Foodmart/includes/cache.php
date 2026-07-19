<?php
/**
 * Memcache-compatible object backed by Redis (phpredis). Lets the legacy
 * `memcache_connect()->set()/get()` calls keep working while using Redis. If
 * Redis is unavailable it degrades to a no-op (cache always misses).
 */
class RedisCacheShim
{
    private ?Redis $redis = null;

    public function __construct()
    {
        if (class_exists('Redis')) {
            try {
                $r = new Redis();
                $r->connect(getenv('REDIS_HOST') ?: 'redis', (int) (getenv('REDIS_PORT') ?: 6379), 1.0);
                $this->redis = $r;
            } catch (\Throwable $e) {
                $this->redis = null;
            }
        }
    }

    public function set($key, $value, $flag = 0, $ttl = 0): bool
    {
        if (!$this->redis) return false;
        $data = serialize($value);
        return $ttl > 0 ? (bool) $this->redis->setex($key, (int) $ttl, $data)
                        : (bool) $this->redis->set($key, $data);
    }

    public function get($key)
    {
        if (!$this->redis) return false;
        $v = $this->redis->get($key);
        return $v === false ? false : unserialize($v);
    }
}

if (!function_exists('memcache_connect')) {
    function memcache_connect($host = null, $port = null)
    {
        return new RedisCacheShim();
    }
}
