<body style="font-size: large">

## Inheritance with private methods

Previously, PHP used to apply the same inheritance checks on public,
protected and private methods. In other words: private methods should
follow the same method signature rules as protected and public methods.
This doesn't make sense, since private methods won't be accessible by
child classes.

This RFC changed that behaviour, so that these inheritance checks are
not performed on private methods anymore. Furthermore, the use of `final
private function` also didn't make sense, so doing so will now trigger a
warning:

    Warning: Private methods cannot be final as they are never overridden by other classes
