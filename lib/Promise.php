<?php

namespace After;

class Promise implements Future, Promisor {
    use Resolvable;

    /**
     * Retrieve the Future value bound to this Promisor
     *
     * This implementation acts as both Promisor and Future so we simply return the
     * current instance. If users require a Promisor that can only be resolved by
     * the promise-holder they may instead use After\SafePromise.
     *
     * @return After\Future
     */
    public function getFuture() {
        return $this;
    }

    /**
     * Resolve the Promisor's bound Future as a success
     *
     * @param mixed $value
     * @throws LogicException If the Future has already resolved
     * @return void
     */
    public function succeed($value = NULL) {
        if ($this->isResolved) {
            throw new \LogicException(
                'Cannot succeed: Future already resolved'
            );
        }

        if ($value instanceof Future) {
            $value->onResolution(function(Future $f) {
                $this->resolve($f->getError(), $f->getValue());
            });
            return;
        }

        $this->isResolved = TRUE;
        $this->value = $value;
        if ($this->onResolution) {
            foreach ($this->onResolution as $onResolution) {
                call_user_func($onResolution, $this);
            }
        }
    }

     /**
     * Resolve the Promisor's bound Future as a failure
     *
     * @param \Exception $error
     * @throws LogicException If the Future has already resolved
     * @return void
     */
    public function fail(\Exception $error) {
        if ($this->isResolved) {
            throw new \LogicException(
                'Cannot fail: Future already resolved'
            );
        }

        $this->isResolved = TRUE;
        $this->error = $error;
        if ($this->onResolution) {
            foreach ($this->onResolution as $onResolution) {
                call_user_func($onResolution, $this);
            }
        }
    }

    /**
     * Resolve the Promisor's bound Future
     *
     * @param \Exception $error
     * @param mixed $value
     * @throws LogicException If the Future has already resolved
     * @return void
     */
    public function resolve(\Exception $error = NULL, $value = NULL) {
        if ($this->isResolved) {
            throw new \LogicException(
                'Cannot resolve: Future already resolved'
            );
        }

        $this->isResolved = TRUE;
        if ($error) {
            $this->error = $error;
        } else {
            $this->value = $value;
        }

        if ($this->onResolution) {
            foreach ($this->onResolution as $onResolution) {
                call_user_func($onResolution, $this);
            }
        }
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
        if ($this->isResolved) {
            return FALSE;
        } else {
            $this->resolve($error, $value);
            return TRUE;
        }
    }
}
