<?php

namespace think\cache;

use DateInterval;
use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
    /**
     * 缓存Key
     * @var string
     */
    protected $key;

    /**
     * 缓存内容
     * @var mixed
     */
    protected $value;

    /**
     * 过期时间
     * @var int|DateTimeInterface
     */
    protected $expire;

    /**
     * 缓存是否命中
     * @var bool
     */
    protected $isHit = false;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * 返回当前缓存项的「键」
     * @access public
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * 返回当前缓存项的有效期
     * @access public
     * @return DateTimeInterface|int|null
     */
    public function getExpire()
    {
        if ($this->expire instanceof DateTimeInterface) {
            return $this->expire;
        }
        return $this->expire ? $this->expire - time() : null;
    }

    /**
     * 凭借此缓存项的「键」从缓存系统里面取出缓存项
     * @access public
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * 确认缓存项的检查是否命中
     * @access public
     * @return bool
     */
    public function isHit()
    {
        return $this->isHit;
    }

    /**
     * 为此缓存项设置「值」
     * @access public
     * @param mixed $value
     * @return $this
     */
    public function set($value)
    {
        $this->value = $value;
        $this->isHit = true;
        return $this;
    }

    /**
     * 设置缓存项的准确过期时间点
     * @access public
     * @param DateTimeInterface $expiration
     * @return $this
     */
    public function expiresAt($expiration)
    {
        if ($expiration instanceof DateTimeInterface) {
            $this->expire = $expiration;
        } else {
            throw new InvalidArgumentException('not support datetime');
        }
        return $this;
    }

    /**
     * 设置缓存项的过期时间
     * @access public
     * @param int|DateInterval $timeInterval
     * @return $this
     * @throws InvalidArgumentException
     */
    public function expiresAfter($timeInterval)
    {
        if ($timeInterval instanceof DateInterval) {
            $this->expire = (int) DateTime::createFromFormat('U', time())->add($timeInterval)->format('U');
        } elseif (is_numeric($timeInterval)) {
            $this->expire = $timeInterval + time();
        } else {
            throw new InvalidArgumentException('not support datetime');
        }
        return $this;
    }
}
