<?php

namespace After;

/**
 * A placeholder value that will be resolved at some point in the future.
 */
interface Future {
    /**
     * Pass this Future to the specified callback upon completion regardless of success or failure
     */
    public function onResolution(callable $onResolution);

    /**
     * Is the Future resolved?
     */
    public function isResolved();

    /**
     * Did the Future resolve successfully?
     */
    public function succeeded();

    /**
     * Retrieve the Future's successfully resolved value
     */
    public function getValue();

    /**
     * Retrieve the Exception responsible for Future resolution failure
     */
    public function getError();
}
