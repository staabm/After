<?php

namespace After;

/**
 * A SafePromise creates a Future contract that can *only* be fulfilled by calling methods
 * on the actual SafePromise instance. This provides an additional layer of API protection
 * over the standard Promise implementation whose Future can be resolved by any code holding
 * a reference to the Promise/Future.
 */
class SafePromise implements Promisor {
    private $value;
    private $error;
    private $future;
    private $resolver;

    public function __construct() {
        $resolver = function(\Exception $error = NULL, $value = NULL) {
            $this->resolve($error, $value);
        };
        $future = new Unresolved;
        $this->resolver = $resolver->bindTo($future, $future);
        $this->future = $future;
    }

    /**
     * Retrieve the Future value bound to this Promisor
     *
     * @return After\Future
     */
    public function getFuture() {
        return $this->future;
    }

    /**
     * Resolve the Promisor's bound Future as a success
     *
     * @param mixed $value
     * @return void
     */
    public function succeed($value = NULL) {
        if ($value instanceof Future) {
            $value->onComplete(function(Future $f) {
                $this->resolve($f->getError(), $f->getValue());
            });
        } else {
            call_user_func($this->resolver, $error = NULL, $value);
        }
    }

    /**
     * Resolve the Promisor's bound Future as a failure
     *
     * @param \Exception $error
     * @return void
     */
    public function fail(\Exception $error) {
        call_user_func($this->resolver, $error, $value = NULL);
    }

    /**
     * Resolve the Promisor's bound Future
     *
     * @param \Exception $error
     * @param mixed $value
     * @return void
     */
    public function resolve(\Exception $error = NULL, $value = NULL) {
        call_user_func($this->resolver, $error, $value);
    }

    /**
     * Resolve the Promisor's Future but only if it has not already resolved
     *
     * This method allows promise holders to resolve the bound Future without throwing an exception
     * if the Future has previously resolved.
     *
     * @param \Exception $error
     * @param mixed $value
     * @return bool Returns TRUE if the Future was resolved by this operation, FALSE otherwise
     */
    public function resolveSafely(\Exception $error = NULL, $value = NULL) {
        if ($this->future->isComplete()) {
            return FALSE;
        } else {
            $this->resolve($error, $value);
            return TRUE;
        }
    }
}
