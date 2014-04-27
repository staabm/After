<?php

namespace After;

/**
 * A placeholder value for a failed Future
 */
class Failure implements Future {
    private $error;

    public function __construct(\Exception $error) {
        $this->error = $error;
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
     * For specialied Failure Futures this method always returns TRUE.
     *
     * @return bool
     */
    public function isResolved() {
        return TRUE;
    }

    /**
     * Did the Future resolve successfully?
     *
     * For specialied Failure Futures this method always returns FALSE.
     *
     * @return bool
     */
    public function succeeded() {
        return FALSE;
    }

    /**
     * Retrieve the Future's successfully resolved value
     *
     * For specialied Failure Futures this method always throws the Exception instance responsible
     * for the failure.
     *
     * @throws Exception
     */
    public function getValue() {
        throw $this->error;
    }

    /**
     * Retrieve the Exception responsible for Future resolution failure
     *
     * @return Exception
     */
    public function getError() {
        return $this->error;
    }
}
