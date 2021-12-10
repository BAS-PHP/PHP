<body style="font-size: large">

## New `fdiv()` function

The new `fdiv()` function does something similar as the [`fmod()`](https://www.php.net/manual/en/function.fmod.php) and
[`intdiv()`](https://www.php.net/manual/en/function.intdiv.php) functions, which allows for division by 0. Instead of errors
you'll get [`INF`](https://www.php.net/manual/en/math.constants.php), [`-INF`](https://www.php.net/manual/en/math.constants.php) or [`NAN`](https://www.php.net/manual/en/math.constants.php), depending on the case.