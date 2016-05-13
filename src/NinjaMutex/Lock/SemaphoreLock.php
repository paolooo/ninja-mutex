<?php
/**
 * This file is part of ninja-mutex.
 *
 * (C) Kamil Dziedzic <arvenil@klecza.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NinjaMutex\Lock;

class SemaphoreLock extends LockAbstract
{
    /**
     * @var array
     */
    private $sem_identifiers = [];

    /**
     * @param  string $name
     * @param  bool   $blocking
     * @return bool
     */
    protected function getLock($name, $blocking)
    {
        if ($this->setupSemaphoreHandle($name) === false) {
            return false;
        }

        if (sem_acquire($this->sem_identifiers[$name]) === false) {
            return false;
        }

        return true;
    }

    /**
     * Release lock
     *
     * @param  string $name name of lock
     * @return bool
     */
    public function releaseLock($name)
    {
        if (isset($this->sem_identifiers[$name])) {
            $result = sem_release($this->sem_identifiers[$name]);
            unset($this->sem_identifiers[$name]);

            return $result;
        }

        return true;
    }

    /**
     * Check if lock is locked
     *
     * @param  string $name name of lock
     * @return bool
     */
    public function isLocked($name)
    {
        return !!sem_get($this->sem_identifiers[$name]);
    }

    /**
     * Create Semaphore
     *
     * @param  string $name Sem Key
     * @return bool
     */
    protected function setupSemaphoreHandle($name)
    {
        if (isset($this->sem_identifiers[$name])) {
            return true;
        }

        return $this->sem_identifiers[$name] = sem_get($name, 1);
    }
}
