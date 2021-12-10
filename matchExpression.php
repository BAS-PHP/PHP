<?php
// Old
switch (8.0) {
    case '8.0':
        $result = 'Oh no!';
        break;
    case 8.0:
        $result = 'This is what I expected';
        break;
}
echo $result;
//> Oh no!

// ===
echo match (8.0) {
    '8.0' => 'Oh no!',
    8.0   => 'This is what I expected',
    default => 'this is a default'
};
//> This is what I expected
