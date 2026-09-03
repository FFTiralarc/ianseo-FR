<?php
/*
11 	3D 	(1 distance)

$TourId is the ID of the tournament!
$SubRule is the eventual subrule (see sets.php for the order)
$TourType is the Tour Type (11)

*/

require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');

// default Divisions
CreateStandardDivisions($TourId, $TourType, $SubRule);

// default Classes
CreateStandard3DClasses($TourId, $SubRule);

// default Events
CreateStandard3DEvents($TourId, $SubRule);

// insert class in events
InsertStandard3DEvents($TourId, $SubRule);

// Finals & TeamFinals
CreateFinals($TourId);

if($SubRule==1) {
    // default Distances
    CreateDistanceNew($TourId, $TourType, '%', array(array('Parcours',0)));
    // Default Target
    CreateTargetFace($TourId, 1, 'Piquet Rouge', 'REG-^TL(U18|U2|S)', '1', 8, 0);
    CreateTargetFace($TourId, 2, 'Piquet Bleu', 'REG-^(CL|CO|AD|AC)(U18|U2|S)|TLU1[35]', '1', 8, 0);
    CreateTargetFace($TourId, 3, 'Piquet Blanc', 'REG-(CL|CO|AD|AC)U1[35]', '1', 8, 0);
    CreateTargetFace($TourId, 4, 'Piquet Rose', 'CLU11%', '1', 8, 0);
    CreateTargetFace($TourId, 5, 'Piquet Découverte', '__DEC', '1', 8, 0);
    // create a first distance prototype
    CreateDistanceInformation($TourId, $DistanceInfoArray, 30, 4);
} else {
    $tourDetNumDist=2;
    // default Distances
    CreateDistanceNew($TourId, $TourType, '%U1%', array(array('Parcours 1',0),array('Parcours 2',0)));
    CreateDistanceNew($TourId, $TourType, '%U2%', array(array('Parcours',0),array('-',0)));
    CreateDistanceNew($TourId, $TourType, '%S%', array(array('Parcours',0),array('-',0)));
    // Default Target
    CreateTargetFace($TourId, 1, 'Piquet Rouge', 'REG-^TL(S|U2)', '1', 8, 0, 8, 0);
    CreateTargetFace($TourId, 2, 'Piquet Bleu', 'REG-^((AC|CL|CO|AD)(S|U2|U18))|TLU1', '1', 8, 0, 8, 0);
    CreateTargetFace($TourId, 3, 'Piquet Blanc', 'REG-^CLU1[35]', '1', 8, 0, 8, 0);
    // create a first distance prototype
    CreateDistanceInformation($TourId, [$DistanceInfoArray[0],$DistanceInfoArray[0]], 30, 4, 1, 'Jeunes CL/TL');
    CreateDistanceInformation($TourId, $DistanceInfoArray, 30, 4, 2, 'Adultes AC');
    CreateDistanceInformation($TourId, $DistanceInfoArray, 30, 4, 3, 'Adultes TL');
    CreateDistanceInformation($TourId, $DistanceInfoArray, 30, 4, 4, 'Adultes AD H');
    CreateDistanceInformation($TourId, $DistanceInfoArray, 30, 4, 5, 'Adultes CL H');
    CreateDistanceInformation($TourId, $DistanceInfoArray, 30, 4, 6, 'Adultes AD/CL F + CO');
}

$tourDetIocCode='FRA';

// Update Tour details
$tourDetails=array(
	'ToCollation' => $tourCollation,
	'ToTypeName' => $tourDetTypeName,
	'ToNumDist' => $tourDetNumDist,
	'ToNumEnds' => $tourDetNumEnds,
	'ToMaxDistScore' => $tourDetMaxDistScore,
	'ToMaxFinIndScore' => $tourDetMaxFinIndScore,
	'ToMaxFinTeamScore' => $tourDetMaxFinTeamScore,
	'ToCategory' => $tourDetCategory,
	'ToElabTeam' => $tourDetElabTeam,
	'ToElimination' => $tourDetElimination,
	'ToGolds' => $tourDetGolds,
	'ToXNine' => $tourDetXNine,
	'ToGoldsChars' => $tourDetGoldsChars,
	'ToXNineChars' => $tourDetXNineChars,
	'ToDouble' => $tourDetDouble,
	'ToIocCode'	=> $tourDetIocCode,
	);
UpdateTourDetails($TourId, $tourDetails);
