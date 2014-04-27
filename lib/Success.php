<?php

namespace After;

/**
 * A placeholder value for a successfully resolved Future
 */
class Success implements Future {
    private $value;

    public function __construct($value = NULL) {
        $this->value = $value;
    }

    /**
     * Pass the Future to the specified callback upon completion regardless of success or failure
     *
     * @param callable $onResolution
     */
    public function onResolution(callable $onResolution) {
        call_user_func($onResolution, $this);
    }

    /**
     * Is the Future resolved?
     *
     * For specialied Success Futures this method always returns TRUE.
     *
     * @return bool
     */
    public function isResolved() {
        return TRUE;
    }

    /**
     * Did the Future resolve successfully?
     *
     * For specialied Success Futures this method always returns TRUE.
     *
     * @return bool
     */
    public function succeeded() {
        return TRUE;
    }

    /**
     * Retrieve the Future's successfully resolved value
     *
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Retrieve the Exception responsible for Future resolution failure
     *
     * For specialied Success Futures this method always returns NULL.
     *
     * @return NULL
     */
    public function getError() {
        return NULL;
    }
}
