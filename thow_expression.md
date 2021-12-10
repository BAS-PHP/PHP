### Throw expression [RFC](https://wiki.php.net/rfc/throw_expression)

This RFC changes `throw` from being a statement to being an expression,
which makes it possible to throw exception in many new places:

    $triggerError = fn () => throw new MyError();

    $foo = $bar['offset'] ?? throw new OffsetDoesNotExist('offset');
