<?php

namespace After;

class PromiseGroup extends Promise {
    private $futures = [];
    private $resolvedValues = [];
    private $isResolved = FALSE;

    public function __construct(array $futures) {
        if (!$futures = array_filter($futures, function($v) { return isset($v); })) {
            $this->succeed([]);
            return;
        }

        $this->futures = $futures;

        foreach ($futures as $key => $future) {
            if (!$future instanceof Future) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Future array required at Argument 1: %s provided at index %s',
                        gettype($future),
                        $key
                    )
                );
            }

            $isResolved = $future->isResolved();

            if ($isResolved && $this->resolveIndividualFuture($future, $key)) {
                return;
            } elseif (!$isResolved) {
                $future->onResolution(function($future) use ($key) {
                    $this->resolveIndividualFuture($future, $key);
                });
            }
        }
    }

    private function resolveIndividualFuture($future, $key) {
        unset($this->futures[$key]);

        if ($this->isResolved) {
            return TRUE;
        } elseif ($future->succeeded()) {
            $this->resolvedValues[$key] = $future->getValue();
            return ($this->isResolved = empty($this->futures))
                ? $this->succeed($this->resolvedValues)
                : FALSE;
        } else {
            $this->fail($future->getError());
            return ($this->isResolved = TRUE);
        }
    }
}
