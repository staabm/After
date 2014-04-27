<?php

namespace After;

/**
 * A placeholder value that will be resolved at some point in the future by
 * the Promisor that created it.
 */
class Unresolved implements Future {
    use Resolvable;
}
