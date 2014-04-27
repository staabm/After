<?php

namespace After;

/**
 * A Promisor is a contract to resolve a value at some point in the future
 *
 * The After\Promisor is NOT the same as the common JavaScript "promise" idiom. Instead,
 * After defines a "Promise" as an internal agreement made by producers of asynchronous
 * results to fulfill a placeholder "Future" value at some point in the future. In this
 * regard an After\Promise has more in common with the Scala promise API than JavaScript
 * implementations.
 *
 * A Promisor resolves its associated Future placeholder with a value using the succeed()
 * method. Conversely, a Promisor reports Future failure by passing an Exception to its
 * Promisor::failure() method.
 *
 * Example:
 *
 * function myAsyncProducer() {
 *     // Create a new promise that needs to be resolved
 *     $promise = new After\Promise;
 *
 *     // When we eventually finish non-blocking value resolution we
 *     // simply call the relevant Promise method to notify any code
 *     // with references to the Future:
 *     // $promise->succeed($value) -or- $promise->fail($error)
 *
 *     return $promise->getFuture();
 * }
 *
 */
interface Promisor {
    /**
     * Retrieve the Future value bound to this Promisor
     *
     * @return After\Future
     */
    public function getFuture();

    /**
     * Resolve the Promisor's bound Future as a success
     *
     * @param mixed $value
     */
    public function succeed($value = NULL);

    /**
     * Resolve the Promisor's bound Future as a failure
     *
     * @param \Exception $error
     * @return void
     */
    public function fail(\Exception $error);

    /**
     * Resolve the Promisor's bound Future
     *
     * @param \Exception $error
     * @param mixed $value
     */
    public function resolve(\Exception $error = NULL, $value = NULL);

    /**
     * Resolve the Promisor's Future but only if it has not already resolved
     *
     * This method allows promise holders to resolve the bound Future without throwing an exception
     * if the Future has previously resolved.
     *
     * @param \Exception $error
     * @param mixed $value
     */
    public function resolveSafely(\Exception $error = NULL, $value = NULL);
}
