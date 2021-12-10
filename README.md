
###  Non-capturing catches [RFC](https://wiki.php.net/rfc/non-capturing_catches)

Whenever you wanted to catch an exception before PHP 8, you had to store
it in a variable, regardless whether you used that variable or not. With
non-capturing catches, you can omit the variable, so instead of this:

    try {
        // Something goes wrong
    } catch (MySpecialException $exception) {
        Log::error("Something went wrong");
    }

You can now do this:

    try {
        // Something goes wrong
    } catch (MySpecialException) {
        Log::error("Something went wrong");
    }

Note that it\'s required to always specify the type, you\'re not allowed
to have an empty `catch`. If you want to catch all exceptions and
errors, you can use `Throwable` as the catching type.
