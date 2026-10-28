<?php
/*
51 	Nature 	(1 distance)

$TourId is the ID of the tournament!
$SubRule is the eventual subrule (see sets.php for the order)
$TourType is the Tour Type (51)

*/

$TourType = 51;

// Screen tournament -> Advanced Parameters
$tourDetTypeName        = 'Type_FR_Nature';
$tourDetNumDist         = '1';
$tourDetNumEnds         = '21';
$tourDetMaxDistScore    = '735';
$tourDetMaxFinIndScore  = '0';
$tourDetMaxFinTeamScore = '0';
$tourDetCategory        = '0';
$tourDetElabTeam        = '0';
$tourDetElimination     = '0';
$tourDetGolds           = '20';
$tourDetXNine           = '15';
$tourDetGoldsChars      = 'V';
$tourDetXNineChars      = '9';
$tourDetDouble          = '0';
$tourCollation          = '';
$tourDetIocCode         = 'FRA';
if(empty($SubRule)) $SubRule='1';

require_once('Setup_Nature.php');

?>