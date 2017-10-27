<?php
use Danon\CleanRegex\Match\Match;

require __DIR__ . '/vendor/autoload.php';

pattern('/(?<whole>(?<first>[A-Z])[a-z]+)(?<no>[0-9])?/')
    ->match('Daniel Jest Spoks siemka')
    ->iterate(function (Match $match) {

        // gets the match
        $m = $match->match();    // (string) "172"
        $m = (string)$match;     // also gets the match

        // gets the match offset
        $m = $match->offset();   // (int) 8

        // gets the group index
        $m = $match->index();    // (int) 3

        $m = $match->groupNames();

        $m = $match->group('first');

        $m = $match->subject();

        $m = $match->namedGroups();
    });
