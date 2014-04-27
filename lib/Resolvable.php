<?php

namespace After;

trait Resolvable {
    private $onResolution = [];
    private $isResolved = FALSE;
    private $value;
    private $error;

    /**
     * Pass the Future to the specified callback upon completion regardless of success or failure
     *
     * @param callable $onResolution
     * @return Future Returns the current object instance
     */
    public function onResolution(callable $onResolution) {
        if ($this->isResolved) {
            call_user_func($onResolution, $this);
        } else {
            $this->onResolution[] = $onResolution;
        }

        return $this;
    }

    /**
     * Has the Future completed (succeeded/failure is irrelevant)?
     *
     * @return bool
     */
    public function isResolved() {
        return $this->isResolved;
    }

    /**
     * Has the Future value been successfully resolved?
     *
     * @throws \LogicException If the Future is still pending
     * @return bool
     */
    public function succeeded() {
        if ($this->isResolved) {
            return empty($this->error);
        } else {
            throw new \LogicException(
                'Cannot retrieve success status: Future still pending'
            );
        }
    }

    /**
     * Retrieve the value that successfully fulfilled the Future
     *
     * @throws \LogicException If the Future is still pending
     * @throws \Exception If the Future failed the exception that caused the failure is thrown
     * @return mixed
     */
    public function getValue() {
        if (!$this->isResolved) {
            throw new \LogicException(
                'Cannot retrieve value: Future still pending'
            );
        } elseif ($this->error) {
            throw $this->error;
        } else {
            return $this->value;
        }
    }

    /**
     * Retrieve the Exception responsible for Future resolution failure
     *
     * @throws \LogicException If the Future succeeded or is still pending
     * @return \Exception
     */
    public function getError() {
        if ($this->isResolved) {
            return $this->error;
        } else {
            throw new \LogicException(
                'Cannot retrieve error: Future still pending'
            );
        }
    }

    private function resolve(\Exception $error = NULL, $value = NULL) {
        if ($this->isResolved) {
            throw new \LogicException(
                'Cannot succeed: Future already resolved'
            );
        }

        $this->isResolved = TRUE;
        $this->error = $error;
        $this->value = $value;

        if ($this->onResolution) {
            foreach ($this->onResolution as $onResolution) {
                call_user_func($onResolution, $this);
            }
        }
    }
}
