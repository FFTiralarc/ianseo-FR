<?php
/*
39 	Type_36Arr70mRound

$TourId is the ID of the tournament!
$SubRule is the eventual subrule (see sets.php for the order)
$TourType is the Tour Type (39)

*/

require_once(dirname(__FILE__).'/lib.php');

// "global" definition, used or not
$tourCollation = '';
$tourDetIocCode = 'FRA';
if(empty($SubRule)) $SubRule='1';

// Tournament informations
$TourType=39;

$tourDetTypeName		= 'Type_36Arr70mRound';
$tourDetNumDist			= '1';
$tourDetNumEnds			= '6';
$tourDetMaxDistScore	= '360';
$tourDetMaxFinIndScore	= '150';
$tourDetMaxFinTeamScore	= '240';
$tourDetCategory		= '1'; // 0: Other, 1: Outdoor, 2: Indoor, 4:Field, 8:3D
$tourDetElabTeam		= '0'; // 0:Standard, 1:Field, 2:3DI
$tourDetElimination		= '0'; // 0: No Eliminations, 1: Elimination Allowed
$tourDetGolds			= '10+X';
$tourDetXNine			= 'X';
$tourDetGoldsChars		= 'KL';
$tourDetXNineChars		= 'K';
$tourDetDouble			= '0';

// First, we setup divisions
$i=1;
CreateDivision($TourId, $i++, 'CL', 'Arc Classique', 1, 'R', 'R');
CreateDivision($TourId, $i++, 'CO', 'Arc à Poulies', 1, 'C', 'C');

// Second, classes, for badges classes means badge level
$i=1;
CreateClass($TourId, $i++, 1, 127, -1, 'Plume', 'Plume', 'Plume',			'1', 'CL',    '', '');
CreateClass($TourId, $i++, 1, 127, -1, 'FlBla', 'FlBla', 'Flèche Blanche',	'1', 'CL,CO', '', '');
CreateClass($TourId, $i++, 1, 127, -1, 'FlNoi', 'FlNoi', 'Flèche Noire',	'1', 'CL,CO', '', '');
CreateClass($TourId, $i++, 1, 127, -1, 'FlBle', 'FlBle', 'Flèche Bleue',	'1', 'CL,CO', '', '');
CreateClass($TourId, $i++, 1, 127, -1, 'FlRou', 'FlRou', 'Flèche Rouge',	'1', 'CL,CO', '', '');
CreateClass($TourId, $i++, 1, 127, -1, 'FlJau', 'FlJau', 'Flèche Jaune',	'1', 'CL,CO', '', '');
CreateClass($TourId, $i++, 1, 127, -1, 'FlBr',  'FlBr',  'Flèche de Bronze','1', 'CL,CO', '', '');
CreateClass($TourId, $i++, 1, 127, -1, 'FlAr',  'FlAr',  'Flèche d\'Argent','1', 'CL,CO', '', '');
CreateClass($TourId, $i++, 1, 127, -1, 'FlOr',  'FlOr',  'Flèche d\'Or',	'1', 'CL,CO', '', '');

// Third, distances
CreateDistanceNew($TourId, $TourType, '__Pl%', array(array('10m-1',10)));
CreateDistanceNew($TourId, $TourType, '__FlBla%', array(array('10m-1',10)));
CreateDistanceNew($TourId, $TourType, '__FlN%', array(array('15m-1',15)));
CreateDistanceNew($TourId, $TourType, '__FlBle%', array(array('20m-1',20)));
CreateDistanceNew($TourId, $TourType, '__FlR%', array(array('25m-1',25)));
CreateDistanceNew($TourId, $TourType, '__FlJ%', array(array('30m-1',30)));
CreateDistanceNew($TourId, $TourType, '__FlBr%', array(array('40m-1',40)));
CreateDistanceNew($TourId, $TourType, 'CLFlA%', array(array('60m-1',60)));
CreateDistanceNew($TourId, $TourType, 'CLFlO%', array(array('70m-1',70)));
CreateDistanceNew($TourId, $TourType, 'COFlA%', array(array('50m-1',50)));
CreateDistanceNew($TourId, $TourType, 'COFlO%', array(array('50m-1',50)));

// Fourth, events: one per division x badge class, qualification only (no finals)
$EvOptions=array(
	'EvFinalFirstPhase' => 0,
	'EvNumQualified' => 0,
	'EvRecCategory' => '',
	'EvWaCategory' => '',
);
$Events=array(
	// EvCode => array(EvEventName, Division, Class, EvFinalTargetType, EvTargetSize, EvDistance)
	'CL_Plume' => array('Plume - Arc Classique', 'CL', 'Plume', 5, 80, 10),
	'CL_FlBla' => array('Flèche Blanche - Arc Classique', 'CL', 'FlBla', 5, 80, 10),
	'CL_FlNoi' => array('Flèche Noire - Arc Classique', 'CL', 'FlNoi', 5, 80, 15),
	'CL_FlBle' => array('Flèche Bleue - Arc Classique', 'CL', 'FlBle', 5, 80, 20),
	'CL_FlRou' => array('Flèche Rouge - Arc Classique', 'CL', 'FlRou', 5, 80, 25),
	'CL_FlJau' => array('Flèche Jaune - Arc Classique', 'CL', 'FlJau', 5, 80, 30),
	'CL_FlBr'  => array('Flèche de Bronze - Arc Classique', 'CL', 'FlBr', 5, 80, 40),
	'CL_FlAr'  => array('Flèche d\'Argent - Arc Classique', 'CL', 'FlAr', 5, 122, 60),
	'CL_FlOr'  => array('Flèche d\'Or - Arc Classique', 'CL', 'FlOr', 5, 122, 70),
	'CO_FlBla' => array('Flèche Blanche - Arc à Poulies', 'CO', 'FlBla', 5, 80, 10),
	'CO_FlNoi' => array('Flèche Noire - Arc à Poulies', 'CO', 'FlNoi', 5, 80, 15),
	'CO_FlBle' => array('Flèche Bleue - Arc à Poulies', 'CO', 'FlBle', 5, 80, 20),
	'CO_FlRou' => array('Flèche Rouge - Arc à Poulies', 'CO', 'FlRou', 5, 80, 25),
	'CO_FlJau' => array('Flèche Jaune - Arc à Poulies', 'CO', 'FlJau', 5, 80, 30),
	'CO_FlBr'  => array('Flèche de Bronze - Arc à Poulies', 'CO', 'FlBr', 9, 80, 40),
	'CO_FlAr'  => array('Flèche d\'Argent - Arc à Poulies', 'CO', 'FlAr', 9, 80, 50),
	'CO_FlOr'  => array('Flèche d\'Or - Arc à Poulies', 'CO', 'FlOr', 9, 80, 50),
);
$i=1;
foreach($Events as $EvCode => $Event) {
	list($EvName, $Division, $Class, $TargetType, $TargetSize, $Distance) = $Event;
	$Options = $EvOptions;
	$Options['EvFinalTargetType'] = $TargetType;
	$Options['EvTargetSize'] = $TargetSize;
	$Options['EvDistance'] = $Distance;
	CreateEventNew($TourId, $EvCode, $EvName, $i++, $Options);
	InsertClassEvent($TourId, 0, 1, $EvCode, $Division, $Class);
}

// Fifth, targets
$i=1;
CreateTargetFace($TourId, $i++, 'Blason Complet 80', 'REG-(^((CL(Fl|P)|COFl)(Bl|N|R|J|l)|CLFlBr))', '1', 5, 80);
CreateTargetFace($TourId, $i++, 'Blason Classique 122', 'REG-(^CLFl(Ar|Or))', '1', 5, 122);
CreateTargetFace($TourId, $i++, 'Blason Poulies 80', 'COFl_r%', '1', 9, 80);

// Sixth, session and distance information (10 targets, 2 archers per target)
$DistanceInfoArray=array(array(6,6));
CreateDistanceInformation($TourId, $DistanceInfoArray, 10, 2, 1, 'Passage de Flèche');
$DistanceInfoArray=array(array(3,6));
CreateDistanceInformation($TourId, $DistanceInfoArray, 10, 2, 2, 'Passage de Plume');

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

?>