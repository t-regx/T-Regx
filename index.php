<?php
use Danon\CleanRegex\Match\Match;

require __DIR__ . '/vendor/autoload.php';


$matches = [];
$result = preg_match('/(?<whole>(?<first>[A-Z])[a-z]+)/', 'Daniel Jest Spoks siemka', $matches);



$matchesAll = [];
$resultAll = preg_match_all('/(?<whole>(?<first>[A-Z])[a-z]+)/', 'Daniel Jest Spoks siemka', $matchesAll);

pattern('/something/')->