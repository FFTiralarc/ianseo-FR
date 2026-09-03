<?php
/*

Common setup for Field

*/

require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');

// default Divisions
CreateStandardDivisions($TourId, $TourType, $SubRule);

// default Classes
CreateStandardFieldClasses($TourId, $SubRule);

// default Events
CreateStandardFieldEvents($TourId, $SubRule);

// insert class in events
InsertStandardFieldEvents($TourId, $SubRule);

// Finals & TeamFinals
CreateFinals($TourId);

if($SubRule==1) {
    // default Distances
    CreateDistanceNew($TourId, $TourType, '%', array(array('Parcours',0)));
    // Default Target
    CreateTargetFace($TourId, 1, 'Piquet Rouge', 'REG-^CL(U2|S)|CO(U(18|2)|S)', '1', 6, 0);
    CreateTargetFace($TourId, 2, 'Piquet Bleu', 'REG-^(CL|BB)U18|BB(U2|S)', '1', 6, 0);
    CreateTargetFace($TourId, 3, 'Piquet Blanc', 'REG-^AD[US]|(BB|CL|CO)U1[35]', '1', 6, 0);
    CreateTargetFace($TourId, 4, 'Piquet Rose', 'CLU11%', '1', 6, 0);
    CreateTargetFace($TourId, 5, 'Piquet Découverte', '__DEC', '1', 6, 0);
    // create a first distance prototype
    CreateDistanceInformation($TourId, $DistanceInfoArray, $tourDetNumEnds*2, 4);
} else {
    // default Distances
    $tourDetNumDist=2;
    CreateDistanceNew($TourId, $TourType, 'C_U1%', array(array('Parcours 1',0),array('Parcours 2',0)));
    CreateDistanceNew($TourId, $TourType, 'BBU1%', array(array('Parcours 1',0),array('Parcours 2',0)));
    CreateDistanceNew($TourId, $TourType, 'ADU1%', array(array('Parcours',0),array('-',0)));
    CreateDistanceNew($TourId, $TourType, '%U2%', array(array('Parcours',0),array('-',0)));
    CreateDistanceNew($TourId, $TourType, '%S%', array(array('Parcours',0),array('-',0)));
    // Default Target
    CreateTargetFace($TourId, 1, 'Piquet Bleu', 'REG-^(BB(S|U2))|(C[LO]U18)', '1', 6, 0);
    CreateTargetFace($TourId, 2, 'Piquet Blanc', 'REG-^(AD[US])|(BBU1)|(C[LO]U1[35])', '1', 6, 0);
    CreateTargetFace($TourId, 3, 'Piquet Rouge', 'REG-^(PNS)|(C[LO](S|U2))', '1', 6, 0);
    // create a first distance prototype
    CreateDistanceInformation($TourId, [$DistanceInfoArray[0],$DistanceInfoArray[0]], $tourDetNumEnds*2, 4, 1, 'Jeunes');
    CreateDistanceInformation($TourId, $DistanceInfoArray, $tourDetNumEnds*2, 4, 2, 'Adultes CO');
    CreateDistanceInformation($TourId, $DistanceInfoArray, $tourDetNumEnds*2, 4, 3, 'Adultes CL');
    CreateDistanceInformation($TourId, $DistanceInfoArray, $tourDetNumEnds*2, 4, 4, 'Adultes BB/AD');
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
