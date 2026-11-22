<?php
/*
51 	Nature 	(1 distance)

$TourId is the ID of the tournament!
$SubRule is the eventual subrule (see sets.php for the order)
$TourType is the Tour Type (51)

*/
require_once(dirname(__FILE__).'/lib.php');


// Divisions
// Usage : CreateDivision($TourId, order, division_code, division_label, athlete(1/0), optional: WA equiv, optional: WA WR)
$i=1;
CreateDivision($TourId, $i++, 'CL', 'Arc Nu', 1);
CreateDivision($TourId, $i++, 'CO', 'Arc à Poulies Nu', 1);
CreateDivision($TourId, $i++, 'AD', 'Arc Droit', 1);
CreateDivision($TourId, $i++, 'TL', 'Arc Libre', 1);
CreateDivision($TourId, $i++, 'AC', 'Arc Chasse', 1);

// Classes 
$NextYearClass=intval(substr($_SESSION['TourRealWhenFrom'], 5,2))>=9 ? -1 : 0;
$i=1;
CreateClass($TourId, $i++, 1,                 10+$NextYearClass, 1, 'U11F', 'U11F,U13F',     'U11 Femme',      1, 'CL');
CreateClass($TourId, $i++, 1,                 10+$NextYearClass, 0, 'U11H', 'U11H,U13H',     'U11 Homme',      1, 'CL');
CreateClass($TourId, $i++, 11+$NextYearClass, 12+$NextYearClass, 1, 'U13F', 'U13F,U15F',     'U13 Femme',      1, '');
CreateClass($TourId, $i++, 11+$NextYearClass, 12+$NextYearClass, 0, 'U13H', 'U13H,U15H',     'U13 Homme',      1, '');
CreateClass($TourId, $i++, 13+$NextYearClass, 14+$NextYearClass, 1, 'U15F', 'U15F,U18F',     'U15 Femme',      1, '');
CreateClass($TourId, $i++, 13+$NextYearClass, 14+$NextYearClass, 0, 'U15H', 'U15H,U18H',     'U15 Homme',      1, '');
CreateClass($TourId, $i++, 15+$NextYearClass, 17+$NextYearClass, 1, 'U18F', 'U18F,U21F,S1F', 'U18 Femme',      1, '');
CreateClass($TourId, $i++, 15+$NextYearClass, 17+$NextYearClass, 0, 'U18H', 'U18H,U21H,S1H', 'U18 Homme',      1, '');
CreateClass($TourId, $i++, 18+$NextYearClass, 20+$NextYearClass, 1, 'U21F', 'U21F,S1F',      'U21 Femme',      1, '');
CreateClass($TourId, $i++, 18+$NextYearClass, 20+$NextYearClass, 0, 'U21H', 'U21H,S1H',      'U21 Homme',      1, '');
CreateClass($TourId, $i++, 21+$NextYearClass, 39+$NextYearClass, 1, 'S1F',  'S1F',           'Senior 1 Femme', 1, '');
CreateClass($TourId, $i++, 21+$NextYearClass, 39+$NextYearClass, 0, 'S1H',  'S1H',           'Senior 1 Homme', 1, '');
CreateClass($TourId, $i++, 40+$NextYearClass, 59+$NextYearClass, 1, 'S2F',  'S2F,S1F',       'Senior 2 Femme', 1, '');
CreateClass($TourId, $i++, 40+$NextYearClass, 59+$NextYearClass, 0, 'S2H',  'S2H,S1H',       'Senior 2 Homme', 1, '');
CreateClass($TourId, $i++, 60+$NextYearClass, 127,               1, 'S3F',  'S3F,S2F,S1F',   'Senior 3 Femme', 1, '');
CreateClass($TourId, $i++, 60+$NextYearClass, 127,               0, 'S3H',  'S3H,S2H,S1H',   'Senior 3 Homme', 1, '');

// Specific
if($SubRule==1) {
    CreateClass($TourId, $i++, 1,             127,              -1, 'DEC',  'DEC',           'Découverte',     1);
}

// Distances
 CreateDistanceNew($TourId, $TourType, '%', array(array('Parcours',0)));

// Targets
CreateTargetFace($TourId, 1, 'Piquets Rose/Rose',   'CLU11%'                         , '1', 33, 0);
CreateTargetFace($TourId, 2, 'Piquets Blanc/Blanc', 'REG-(CL|CO|AD|TL|AC)U1[35]'     , '1', 33, 0);
CreateTargetFace($TourId, 3, 'Piquets Rouge/Bleu',  'REG-(CL|CO|AD|TL|AC)(U18|U21|S)', '1', 33, 0);
CreateTargetFace($TourId, 5, 'Piquet Découverte',   '__DEC',                           '1', 33, 0);

// Sessions
$DistanceInfoArray=array(array(21, 2));
CreateDistanceInformation($TourId, $DistanceInfoArray, 21, 5);

// Events
$SettingsInd=array(
                'EvFinalTargetType' => 33,
                'EvTargetSize' => 0,
                'EvDistance' => 0,
                'EvFinalFirstPhase' => 0,
                'EvNumQualified' => 0,
                'EvRecCategory' => '',
                'EvWaCategory' => '',
            );
$SettingsTeam=array();
$SettingsMixedTeam=array();
$i=1;
// CL events
CreateEventNew($TourId, 'U11FCL', 'U11 Femme Arc Nu',               $i++, $SettingsInd);
CreateEventNew($TourId, 'U11HCL', 'U11 Homme Arc Nu',               $i++, $SettingsInd);
CreateEventNew($TourId, 'U13FCL', 'U13 Femme Arc Nu',               $i++, $SettingsInd);
CreateEventNew($TourId, 'U13HCL', 'U13 Homme Arc Nu',               $i++, $SettingsInd);
CreateEventNew($TourId, 'U15FCL', 'U15 Femme Arc Nu',               $i++, $SettingsInd);
CreateEventNew($TourId, 'U15HCL', 'U15 Homme Arc Nu',               $i++, $SettingsInd);
CreateEventNew($TourId, 'U21FCL', 'U18-U21 Femme Arc Nu',           $i++, $SettingsInd);
CreateEventNew($TourId, 'U21HCL', 'U18-U21 Homme Arc Nu',           $i++, $SettingsInd);
CreateEventNew($TourId, 'S1FCL' , 'Senior 1 Femme Arc Nu',          $i++, $SettingsInd);
CreateEventNew($TourId, 'S1HCL' , 'Senior 1 Homme Arc Nu',          $i++, $SettingsInd);
CreateEventNew($TourId, 'S2FCL' , 'Senior 2 Femme Arc Nu',          $i++, $SettingsInd);
CreateEventNew($TourId, 'S2HCL' , 'Senior 2 Homme Arc Nu',          $i++, $SettingsInd);
CreateEventNew($TourId, 'S3FCL' , 'Senior 3 Femme Arc Nu',          $i++, $SettingsInd);
CreateEventNew($TourId, 'S3HCL' , 'Senior 3 Homme Arc Nu',          $i++, $SettingsInd);

// CO events
CreateEventNew($TourId, 'U15FCO', 'U13-U15 Femme Arc à Poulies Nu', $i++, $SettingsInd);
CreateEventNew($TourId, 'U15HCO', 'U13-U15 Homme Arc à Poulies Nu', $i++, $SettingsInd);
CreateEventNew($TourId, 'U21FCO', 'U18-U21 Femme Arc à Poulies Nu', $i++, $SettingsInd);
CreateEventNew($TourId, 'U21HCO', 'U18-U21 Homme Arc à Poulies Nu', $i++, $SettingsInd);
CreateEventNew($TourId, 'SFCO'  , 'Scratch Femme Arc à Poulies Nu', $i++, $SettingsInd);
CreateEventNew($TourId, 'SHCO'  , 'Scratch Homme Arc à Poulies Nu', $i++, $SettingsInd);

// TL Events
CreateEventNew($TourId, 'U15FTL', 'U13-U15 Femme Arc Libre',        $i++, $SettingsInd);
CreateEventNew($TourId, 'U15HTL', 'U13-U15 Homme Arc Libre',        $i++, $SettingsInd);
CreateEventNew($TourId, 'U21FTL', 'U18-U21 Femme Arc Libre',        $i++, $SettingsInd);
CreateEventNew($TourId, 'U21HTL', 'U18-U21 Homme Arc Libre',        $i++, $SettingsInd);
CreateEventNew($TourId, 'S1FTL',  'Senior 1 Femme Arc Libre',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S1HTL',  'Senior 1 Homme Arc Libre',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S2FTL',  'Senior 2 Femme Arc Libre',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S2HTL',  'Senior 2 Homme Arc Libre',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S3FTL',  'Senior 3 Femme Arc Libre',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S3HTL',  'Senior 3 Homme Arc Libre',       $i++, $SettingsInd);

// AD Events
CreateEventNew($TourId, 'U15FAD', 'U13-U15 Femme Arc Droit',        $i++, $SettingsInd);
CreateEventNew($TourId, 'U15HAD', 'U13-U15 Homme Arc Droit',        $i++, $SettingsInd);
CreateEventNew($TourId, 'U21FAD', 'U18-U21 Femme Arc Droit',        $i++, $SettingsInd);
CreateEventNew($TourId, 'U21HAD', 'U18-U21 Homme Arc Droit',        $i++, $SettingsInd);
CreateEventNew($TourId, 'S1FAD',  'Senior 1 Femme Arc Droit',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S1HAD',  'Senior 1 Homme Arc Droit',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S2FAD',  'Senior 2 Femme Arc Droit',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S2HAD',  'Senior 2 Homme Arc Droit',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S3FAD',  'Senior 3 Femme Arc Droit',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S3HAD',  'Senior 3 Homme Arc Droit',       $i++, $SettingsInd);

// AC Events
CreateEventNew($TourId, 'U15FAC', 'U13-U15 Femme Arc Chasse',       $i++, $SettingsInd);
CreateEventNew($TourId, 'U15HAC', 'U13-U15 Homme Arc Chasse',       $i++, $SettingsInd);
CreateEventNew($TourId, 'U21FAC', 'U18-U21 Femme Arc Chasse',       $i++, $SettingsInd);
CreateEventNew($TourId, 'U21HAC', 'U18-U21 Homme Arc Chasse',       $i++, $SettingsInd);
CreateEventNew($TourId, 'S1FAC',  'Senior 1 Femme Arc Chasse',      $i++, $SettingsInd);
CreateEventNew($TourId, 'S1HAC',  'Senior 1 Homme Arc Chasse',      $i++, $SettingsInd);
CreateEventNew($TourId, 'S2FAC',  'Senior 2 Femme Arc Chasse',      $i++, $SettingsInd);
CreateEventNew($TourId, 'S2HAC',  'Senior 2 Homme Arc Chasse',      $i++, $SettingsInd);
CreateEventNew($TourId, 'S3FAC',  'Senior 3 Femme Arc Chasse',      $i++, $SettingsInd);
CreateEventNew($TourId, 'S3HAC',  'Senior 3 Homme Arc Chasse',      $i++, $SettingsInd);

// DEC Events
CreateEventNew($TourId, 'DEC',    'Découverte',                     $i++, $SettingsInd);


// Classes in events
InsertClassEvent($TourId, 0, 1, 'U11FCL',  'CL',  'U11F');
InsertClassEvent($TourId, 0, 1, 'U11HCL',  'CL',  'U11H');
InsertClassEvent($TourId, 0, 1, 'U13FCL',  'CL',  'U13F');
InsertClassEvent($TourId, 0, 1, 'U13HCL',  'CL',  'U13H');
InsertClassEvent($TourId, 0, 1, 'U15FCL',  'CL',  'U15F');
InsertClassEvent($TourId, 0, 1, 'U15HCL',  'CL',  'U15H');
InsertClassEvent($TourId, 0, 1, 'U21FCL',  'CL',  'U18F');
InsertClassEvent($TourId, 0, 1, 'U21HCL',  'CL',  'U18H');
InsertClassEvent($TourId, 0, 1, 'U21FCL',  'CL',  'U21F');
InsertClassEvent($TourId, 0, 1, 'U21HCL',  'CL',  'U21H');
InsertClassEvent($TourId, 0, 1, 'S1FCL',   'CL',  'S1F');
InsertClassEvent($TourId, 0, 1, 'S1HCL',   'CL',  'S1H');
InsertClassEvent($TourId, 0, 1, 'S2FCL',   'CL',  'S2F');
InsertClassEvent($TourId, 0, 1, 'S2HCL',   'CL',  'S2H');
InsertClassEvent($TourId, 0, 1, 'S3FCL',   'CL',  'S3F');
InsertClassEvent($TourId, 0, 1, 'S3HCL',   'CL',  'S3H');

InsertClassEvent($TourId, 0, 1, 'U15FCO',  'CO',  'U13F');
InsertClassEvent($TourId, 0, 1, 'U15HCO',  'CO',  'U13H');
InsertClassEvent($TourId, 0, 1, 'U15FCO',  'CO',  'U15F');
InsertClassEvent($TourId, 0, 1, 'U15HCO',  'CO',  'U15H');
InsertClassEvent($TourId, 0, 1, 'U21FCO',  'CO',  'U18F');
InsertClassEvent($TourId, 0, 1, 'U21HCO',  'CO',  'U18H');
InsertClassEvent($TourId, 0, 1, 'U21FCO',  'CO',  'U21F');
InsertClassEvent($TourId, 0, 1, 'U21HCO',  'CO',  'U21H');
InsertClassEvent($TourId, 0, 1, 'SFCO',    'CO',  'S1F');
InsertClassEvent($TourId, 0, 1, 'SHCO',    'CO',  'S1H');
InsertClassEvent($TourId, 0, 1, 'SFCO',    'CO',  'S2F');
InsertClassEvent($TourId, 0, 1, 'SHCO',    'CO',  'S2H');
InsertClassEvent($TourId, 0, 1, 'SFCO',    'CO',  'S3F');
InsertClassEvent($TourId, 0, 1, 'SHCO',    'CO',  'S3H');

InsertClassEvent($TourId, 0, 1, 'U15FTL',  'TL',  'U13F');
InsertClassEvent($TourId, 0, 1, 'U15HTL',  'TL',  'U13H');
InsertClassEvent($TourId, 0, 1, 'U15FTL',  'TL',  'U15F');
InsertClassEvent($TourId, 0, 1, 'U15HTL',  'TL',  'U15H');
InsertClassEvent($TourId, 0, 1, 'U21FTL',  'TL',  'U18F');
InsertClassEvent($TourId, 0, 1, 'U21HTL',  'TL',  'U18H');
InsertClassEvent($TourId, 0, 1, 'U21FTL',  'TL',  'U21F');
InsertClassEvent($TourId, 0, 1, 'U21HTL',  'TL',  'U21H');
InsertClassEvent($TourId, 0, 1, 'S1FTL',   'TL',  'S1F');
InsertClassEvent($TourId, 0, 1, 'S1HTL',   'TL',  'S1H');
InsertClassEvent($TourId, 0, 1, 'S2FTL',   'TL',  'S2F');
InsertClassEvent($TourId, 0, 1, 'S2HTL',   'TL',  'S2H');
InsertClassEvent($TourId, 0, 1, 'S3FTL',   'TL',  'S3F');
InsertClassEvent($TourId, 0, 1, 'S3HTL',   'TL',  'S3H');

InsertClassEvent($TourId, 0, 1, 'U15FAD',  'AD',  'U13F');
InsertClassEvent($TourId, 0, 1, 'U15HAD',  'AD',  'U13H');
InsertClassEvent($TourId, 0, 1, 'U15FAD',  'AD',  'U15F');
InsertClassEvent($TourId, 0, 1, 'U15HAD',  'AD',  'U15H');
InsertClassEvent($TourId, 0, 1, 'U21FAD',  'AD',  'U18F');
InsertClassEvent($TourId, 0, 1, 'U21HAD',  'AD',  'U18H');
InsertClassEvent($TourId, 0, 1, 'U21FAD',  'AD',  'U21F');
InsertClassEvent($TourId, 0, 1, 'U21HAD',  'AD',  'U21H');
InsertClassEvent($TourId, 0, 1, 'S1FAD',   'AD',  'S1F');
InsertClassEvent($TourId, 0, 1, 'S1HAD',   'AD',  'S1H');
InsertClassEvent($TourId, 0, 1, 'S2FAD',   'AD',  'S2F');
InsertClassEvent($TourId, 0, 1, 'S2HAD',   'AD',  'S2H');
InsertClassEvent($TourId, 0, 1, 'S3FAD',   'AD',  'S3F');
InsertClassEvent($TourId, 0, 1, 'S3HAD',   'AD',  'S3H');

InsertClassEvent($TourId, 0, 1, 'U15FAC',  'AC',  'U13F');
InsertClassEvent($TourId, 0, 1, 'U15HAC',  'AC',  'U13H');
InsertClassEvent($TourId, 0, 1, 'U15FAC',  'AC',  'U15F');
InsertClassEvent($TourId, 0, 1, 'U15HAC',  'AC',  'U15H');
InsertClassEvent($TourId, 0, 1, 'U21FAC',  'AC',  'U18F');
InsertClassEvent($TourId, 0, 1, 'U21HAC',  'AC',  'U18H');
InsertClassEvent($TourId, 0, 1, 'U21FAC',  'AC',  'U21F');
InsertClassEvent($TourId, 0, 1, 'U21HAC',  'AC',  'U21H');
InsertClassEvent($TourId, 0, 1, 'S1FAC',   'AC',  'S1F');
InsertClassEvent($TourId, 0, 1, 'S1HAC',   'AC',  'S1H');
InsertClassEvent($TourId, 0, 1, 'S2FAC',   'AC',  'S2F');
InsertClassEvent($TourId, 0, 1, 'S2HAC',   'AC',  'S2H');
InsertClassEvent($TourId, 0, 1, 'S3FAC',   'AC',  'S3F');
InsertClassEvent($TourId, 0, 1, 'S3HAC',   'AC',  'S3H');

InsertClassEvent($TourId, 0, 1, 'DEC',     'CL',  'DEC');
InsertClassEvent($TourId, 0, 1, 'DEC',     'CO',  'DEC');
InsertClassEvent($TourId, 0, 1, 'DEC',     'AD',  'DEC');
InsertClassEvent($TourId, 0, 1, 'DEC',     'TL',  'DEC');
InsertClassEvent($TourId, 0, 1, 'DEC',     'AC',  'DEC');

$tourDetails = array(
    'ToCollation'        => $tourCollation,
    'ToTypeName'         => $tourDetTypeName,
    'ToNumDist'          => $tourDetNumDist,
    'ToNumEnds'          => $tourDetNumEnds,
    'ToMaxDistScore'     => $tourDetMaxDistScore,
    'ToMaxFinIndScore'   => $tourDetMaxFinIndScore,
    'ToMaxFinTeamScore'  => $tourDetMaxFinTeamScore,
    'ToCategory'         => $tourDetCategory,
    'ToElabTeam'         => $tourDetElabTeam,
    'ToElimination'      => $tourDetElimination,
    'ToGolds'            => $tourDetGolds,
    'ToXNine'            => $tourDetXNine,
    'ToGoldsChars'       => $tourDetGoldsChars,
    'ToXNineChars'       => $tourDetXNineChars,
    'ToDouble'           => $tourDetDouble,
    'ToIocCode'          => $tourDetIocCode,
);

UpdateTourDetails($TourId, $tourDetails);