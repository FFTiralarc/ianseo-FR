<?php

/*

STANDARD DEFINITIONS (Target Tournaments)

*/

/*

FIELD DEFINITIONS (Target Tournaments)

*/

function CreateStandardFieldClasses($TourId, $SubRule) {
    $NextYearClass=intval(substr($_SESSION['TourRealWhenFrom'], 5,2))>=9 ? -1 : 0;
    $i=1;
	switch($SubRule) {
		case '1':
            $AlDivU13='';
            $AlDivU15='';
			break;
		case '2':
            $AlDivU13='AD,CL';
            $AlDivU15='AD,BB,CL';
			break;
	}
    CreateClass($TourId, $i++, 1, 10+$NextYearClass, 1, 'U11F', 'U11F,U13F', 'U11 Femme', 1, 'CL');
    CreateClass($TourId, $i++, 1, 10+$NextYearClass, 1, 'U11H', 'U11H,U13H', 'U11 Homme', 1, 'CL');
    CreateClass($TourId, $i++, 11+$NextYearClass, 12+$NextYearClass, 1, 'U13F', 'U13F,U15F', 'U13 Femme', 1, $AlDivU13);
    CreateClass($TourId, $i++, 11+$NextYearClass, 12+$NextYearClass, 0, 'U13H', 'U13H,U15H', 'U13 Homme', 1, $AlDivU13);
    CreateClass($TourId, $i++, 13+$NextYearClass, 14+$NextYearClass, 1, 'U15F', 'U15F,U18F', 'U15 Femme', 1, $AlDivU15);
    CreateClass($TourId, $i++, 13+$NextYearClass, 14+$NextYearClass, 0, 'U15H', 'U15H,U18H', 'U15 Homme', 1, $AlDivU15);
    CreateClass($TourId, $i++, 15+$NextYearClass, 17+$NextYearClass, 1, 'U18F', 'S1F,U18F,U21F', 'U18 Femme', 1, '', 'U18W', 'U18W');
    CreateClass($TourId, $i++, 15+$NextYearClass, 17+$NextYearClass, 0, 'U18H', 'S1H,U18H,U21H', 'U18 Homme', 1, '', 'U18M', 'U18M');
    CreateClass($TourId, $i++, 18+$NextYearClass, 20+$NextYearClass, 1, 'U21F', 'S1F,U21F', 'U21 Femme', 1, '', 'U21W', 'U21W');
    CreateClass($TourId, $i++, 18+$NextYearClass, 20+$NextYearClass, 0, 'U21H', 'S1H,U21H', 'U21 Homme', 1, '', 'U21M', 'U21M');
    CreateClass($TourId, $i++, 21+$NextYearClass, 39+$NextYearClass, 1, 'S1F', 'S1F', 'Senior 1 Femme', 1, '', 'W', 'W');
    CreateClass($TourId, $i++, 21+$NextYearClass, 39+$NextYearClass, 0, 'S1H', 'S1H', 'Senior 1 Homme', 1, '', 'M', 'M');
    CreateClass($TourId, $i++, 40+$NextYearClass, 59+$NextYearClass, 1, 'S2F', 'S1F,S2F', 'Senior 2 Femme', 1, '');
    CreateClass($TourId, $i++, 40+$NextYearClass, 59+$NextYearClass, 0, 'S2H', 'S1H,S2H', 'Senior 2 Homme', 1, '');
    CreateClass($TourId, $i++, 60+$NextYearClass, 127, 1, 'S3F', 'S1F,S2F,S3F', 'Senior 3 Femme', 1, '', '50W', '50W');
    CreateClass($TourId, $i++, 60+$NextYearClass, 127, 0, 'S3H', 'S1H,S2H,S3H', 'Senior 3 Homme', 1, '', '50M', '50W');
    if($SubRule==1) {
        CreateClass($TourId, $i++, 1, 127, -1, 'DEC', 'DEC', 'Découverte', 1);
    }
}

function CreateStandardFieldEvents($TourId, $SubRule) {
    $SettingsInd=array(
        'EvFinalFirstPhase' => 0,
        'EvFinalTargetType'=>TGT_FIELD,
        'EvElimEnds'=>6,
        'EvElimArrows'=>3,
        'EvElimSO'=>1,
        'EvFinEnds'=>4,
        'EvFinArrows'=>3,
        'EvFinSO'=>1,
        'EvFinalAthTarget'=>255,
        'EvMatchArrowsNo'=>FINAL_FROM_2,
    );
    $SettingsTeam=array(
        'EvTeamEvent' => 1,
        'EvFinalFirstPhase' => 0,
        'EvFinalTargetType'=>TGT_FIELD,
        'EvElimEnds'=>4,
        'EvElimArrows'=>3,
        'EvElimSO'=>3,
        'EvFinEnds'=>4,
        'EvFinArrows'=>3,
        'EvFinSO'=>3,
        'EvFinalAthTarget'=>255,
        'EvMatchArrowsNo'=>FINAL_FROM_2,
    );
    $SettingsMixedTeam=array(
        'EvTeamEvent' => '1',
        'EvMixedTeam' => '1',
        'EvFinalFirstPhase' => '0',
        'EvFinalTargetType'=>TGT_FIELD,
        'EvElimEnds'=>4,
        'EvElimArrows'=>4,
        'EvElimSO'=>2,
        'EvFinEnds'=>4,
        'EvFinArrows'=>4,
        'EvFinSO'=>2,
        'EvFinalAthTarget'=>255,
        'EvMatchArrowsNo'=>FINAL_FROM_2,
    );
    $i=1;
    $Events=[
        'U11FCL' => 'U11 Femme Arc Classique',
        'U11HCL' => 'U11 Homme Arc Classique',
        'U13FCL' => 'U13 Femme Arc Classique',
        'U13HCL' => 'U13 Homme Arc Classique',
        'U15FCL' => 'U15 Femme Arc Classique',
        'U15HCL' => 'U15 Homme Arc Classique',
        'U18FCL' => 'U18 Femme Arc Classique',
        'U18HCL' => 'U18 Homme Arc Classique',
        'U21FCL' => 'U21 Femme Arc Classique',
        'U21HCL' => 'U21 Homme Arc Classique',
        'S1FCL' => 'Senior 1 Femme Arc Classique',
        'S1HCL' => 'Senior 1 Homme Arc Classique',
        'S2FCL' => 'Senior 2 Femme Arc Classique',
        'S2HCL' => 'Senior 2 Homme Arc Classique',
        'S3FCL' => 'Senior 3 Femme Arc Classique',
        'S3HCL' => 'Senior 3 Homme Arc Classique',
        'U15FCO' => 'U13-U15 Femme Arc à Poulies',
        'U15HCO' => 'U13-U15 Homme Arc à Poulies',
        'U21FCO' => 'U18-U21 Femme Arc à Poulies',
        'U21HCO' => 'U18-U21 Homme Arc à Poulies',
        'S1FCO' => 'Senior 1 Femme Arc à Poulies',
        'S1HCO' => 'Senior 1 Homme Arc à Poulies',
        'S2FCO' => 'Senior 2 Femme Arc à Poulies',
        'S2HCO' => 'Senior 2 Homme Arc à Poulies',
        'S3FCO' => 'Senior 3 Femme Arc à Poulies',
        'S3HCO' => 'Senior 3 Homme Arc à Poulies',
        'U15FBB' => 'U13-U15 Femme Arc Nu',
        'U15HBB' => 'U13-U15 Homme Arc Nu',
        'U21FBB' => 'U18-U21 Femme Arc Nu',
        'U21HBB' => 'U18-U21 Homme Arc Nu',
        'S1FBB' => 'Senior 1 Femme Arc Nu',
        'S1HBB' => 'Senior 1 Homme Arc Nu',
        'S2FBB' => 'Senior 2 Femme Arc Nu',
        'S2HBB' => 'Senior 2 Homme Arc Nu',
        'S3FBB' => 'Senior 3 Femme Arc Nu',
        'S3HBB' => 'Senior 3 Homme Arc Nu',
        'U15FAD' => 'U13-U15 Femme Arc Droit',
        'U15HAD' => 'U13-U15 Homme Arc Droit',
        'U21FAD' => 'U18-U21 Femme Arc Droit',
        'U21HAD' => 'U18-U21 Homme Arc Droit',
        'SFAD' => 'Scratch Femme Arc Droit',
        'SHAD' => 'Scratch Homme Arc Droit',
        'DEC' => 'Découverte',
    ];
    $FirstPhases=array_fill_keys(array_keys($Events), 0);
    if($SubRule==2) {
        unset($Events['U15FCO']);
        unset($Events['U15HCO']);
        unset($Events['U21FBB']);
        unset($Events['U21HBB']);
        unset($Events['DEC']);
        $Events['S1FBB']='U21 - Senior 1 Femme Arc Nu';
        $Events['S1HBB']='U21 - Senior 1 Homme Arc Nu';
        $FirstPhases['S3HCO']=8;
        $FirstPhases['S2HCO']=8;
        $FirstPhases['S1HCO']=8;
        $FirstPhases['S3FCO']=4;
        $FirstPhases['S2FCO']=4;
        $FirstPhases['S1FCO']=4;
        $FirstPhases['U21HCO']=2;
        $FirstPhases['U21FCO']=2;
        $FirstPhases['S1HCL']=8;
        $FirstPhases['S2HCL']=8;
        $FirstPhases['S3HCL']=4;
        $FirstPhases['S1FCL']=4;
        $FirstPhases['S2FCL']=4;
        $FirstPhases['S3FCL']=2;
        $FirstPhases['U21HCL']=4;
        $FirstPhases['U21FCL']=4;
        $FirstPhases['S1HBB']=4;
        $FirstPhases['S1FBB']=4;
        $FirstPhases['S2HBB']=8;
        $FirstPhases['S2FBB']=4;
        $FirstPhases['S3HBB']=8;
        $FirstPhases['S3FBB']=2;
        $FirstPhases['SHAD']=8;
        $FirstPhases['SFAD']=4;
    }
    foreach($Events as $code=>$event) {
        $SettingsInd['EvFinalFirstPhase']=$FirstPhases[$code];
        CreateEventNew($TourId, $code, $event, $i++, $SettingsInd);
    }

    $i=1;
    CreateEventNew($TourId, 'F', 'Equipe Femme', $i++, $SettingsTeam);
    CreateEventNew($TourId, 'H', 'Equipes Homme', $i++, $SettingsTeam);
    CreateEventNew($TourId, 'DMCL', 'Double Mixte Arc Classique', $i++, $SettingsMixedTeam);
    CreateEventNew($TourId, 'DMCO', 'Double Mixte Arc à Poulies', $i++, $SettingsMixedTeam);
    CreateEventNew($TourId, 'DMBB', 'Double Mixte Arc Nu', $i++, $SettingsMixedTeam);
}

function InsertStandardFieldEvents($TourId, $SubRule) {
    // Common Individual
    InsertClassEvent($TourId, 0, 1, 'U11FCL',  'CL',  'U11F');
    InsertClassEvent($TourId, 0, 1, 'U11HCL',  'CL',  'U11H');
    InsertClassEvent($TourId, 0, 1, 'U13FCL',  'CL',  'U13F');
    InsertClassEvent($TourId, 0, 1, 'U13HCL',  'CL',  'U13H');
    InsertClassEvent($TourId, 0, 1, 'U15FCL',  'CL',  'U15F');
    InsertClassEvent($TourId, 0, 1, 'U15HCL',  'CL',  'U15H');
    InsertClassEvent($TourId, 0, 1, 'U18FCL',  'CL',  'U18F');
    InsertClassEvent($TourId, 0, 1, 'U18HCL',  'CL',  'U18H');
    InsertClassEvent($TourId, 0, 1, 'U21FCL',  'CL',  'U21F');
    InsertClassEvent($TourId, 0, 1, 'U21HCL',  'CL',  'U21H');
    InsertClassEvent($TourId, 0, 1, 'S1FCL',  'CL',  'S1F');
    InsertClassEvent($TourId, 0, 1, 'S1HCL',  'CL',  'S1H');
    InsertClassEvent($TourId, 0, 1, 'S2FCL',  'CL',  'S2F');
    InsertClassEvent($TourId, 0, 1, 'S2HCL',  'CL',  'S2H');
    InsertClassEvent($TourId, 0, 1, 'S3FCL',  'CL',  'S3F');
    InsertClassEvent($TourId, 0, 1, 'S3HCL',  'CL',  'S3H');
    InsertClassEvent($TourId, 0, 1, 'U15FCO',  'CO',  'U13F');
    InsertClassEvent($TourId, 0, 1, 'U15HCO',  'CO',  'U13H');
    InsertClassEvent($TourId, 0, 1, 'U15FCO',  'CO',  'U15F');
    InsertClassEvent($TourId, 0, 1, 'U15HCO',  'CO',  'U15H');
    InsertClassEvent($TourId, 0, 1, 'U21FCO',  'CO',  'U18F');
    InsertClassEvent($TourId, 0, 1, 'U21HCO',  'CO',  'U18H');
    InsertClassEvent($TourId, 0, 1, 'U21FCO',  'CO',  'U21F');
    InsertClassEvent($TourId, 0, 1, 'U21HCO',  'CO',  'U21H');
    InsertClassEvent($TourId, 0, 1, 'S1FCO',  'CO',  'S1F');
    InsertClassEvent($TourId, 0, 1, 'S1HCO',  'CO',  'S1H');
    InsertClassEvent($TourId, 0, 1, 'S2FCO',  'CO',  'S2F');
    InsertClassEvent($TourId, 0, 1, 'S2HCO',  'CO',  'S2H');
    InsertClassEvent($TourId, 0, 1, 'S3FCO',  'CO',  'S3F');
    InsertClassEvent($TourId, 0, 1, 'S3HCO',  'CO',  'S3H');
    InsertClassEvent($TourId, 0, 1, 'U15FBB',  'BB',  'U13F');
    InsertClassEvent($TourId, 0, 1, 'U15HBB',  'BB',  'U13H');
    InsertClassEvent($TourId, 0, 1, 'U15FBB',  'BB',  'U15F');
    InsertClassEvent($TourId, 0, 1, 'U15HBB',  'BB',  'U15H');
    InsertClassEvent($TourId, 0, 1, 'U21FBB',  'BB',  'U18F');
    InsertClassEvent($TourId, 0, 1, 'U21HBB',  'BB',  'U18H');
    InsertClassEvent($TourId, 0, 1, 'U21FBB',  'BB',  'U21F');
    InsertClassEvent($TourId, 0, 1, 'U21HBB',  'BB',  'U21H');
    InsertClassEvent($TourId, 0, 1, 'S1FBB',  'BB',  'S1F');
    InsertClassEvent($TourId, 0, 1, 'S1HBB',  'BB',  'S1H');
    InsertClassEvent($TourId, 0, 1, 'S2FBB',  'BB',  'S2F');
    InsertClassEvent($TourId, 0, 1, 'S2HBB',  'BB',  'S2H');
    InsertClassEvent($TourId, 0, 1, 'S3FBB',  'BB',  'S3F');
    InsertClassEvent($TourId, 0, 1, 'S3HBB',  'BB',  'S3H');
    InsertClassEvent($TourId, 0, 1, 'U15FAD',  'AD',  'U13F');
    InsertClassEvent($TourId, 0, 1, 'U15HAD',  'AD',  'U13H');
    InsertClassEvent($TourId, 0, 1, 'U15FAD',  'AD',  'U15F');
    InsertClassEvent($TourId, 0, 1, 'U15HAD',  'AD',  'U15H');
    InsertClassEvent($TourId, 0, 1, 'U21FAD',  'AD',  'U18F');
    InsertClassEvent($TourId, 0, 1, 'U21HAD',  'AD',  'U18H');
    InsertClassEvent($TourId, 0, 1, 'U21FAD',  'AD',  'U21F');
    InsertClassEvent($TourId, 0, 1, 'U21HAD',  'AD',  'U21H');
    InsertClassEvent($TourId, 0, 1, 'SFAD',  'AD',  'S1F');
    InsertClassEvent($TourId, 0, 1, 'SHAD',  'AD',  'S1H');
    InsertClassEvent($TourId, 0, 1, 'SFAD',  'AD',  'S2F');
    InsertClassEvent($TourId, 0, 1, 'SHAD',  'AD',  'S2H');
    InsertClassEvent($TourId, 0, 1, 'SFAD',  'AD',  'S3F');
    InsertClassEvent($TourId, 0, 1, 'SHAD',  'AD',  'S3H');

    // Common Team
    InsertClassEvent($TourId, 1, 1, 'DMBB', 'BB', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'DMBB', 'BB', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'DMBB', 'BB', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'DMBB', 'BB', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'DMBB', 'BB', 'S1F');
    InsertClassEvent($TourId, 1, 1, 'DMBB', 'BB', 'S2F');
    InsertClassEvent($TourId, 1, 1, 'DMBB', 'BB', 'S3F');
    InsertClassEvent($TourId, 2, 1, 'DMBB', 'BB', 'U13H');
    InsertClassEvent($TourId, 2, 1, 'DMBB', 'BB', 'U15H');
    InsertClassEvent($TourId, 2, 1, 'DMBB', 'BB', 'U18H');
    InsertClassEvent($TourId, 2, 1, 'DMBB', 'BB', 'U21H');
    InsertClassEvent($TourId, 2, 1, 'DMBB', 'BB', 'S1H');
    InsertClassEvent($TourId, 2, 1, 'DMBB', 'BB', 'S2H');
    InsertClassEvent($TourId, 2, 1, 'DMBB', 'BB', 'S3H');
    
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U11F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'S1F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'S2F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'S3F');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U11H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U13H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U15H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U18H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U21H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'S1H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'S2H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'S3H');

    InsertClassEvent($TourId, 1, 1, 'DMCO', 'CO', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'DMCO', 'CO', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'DMCO', 'CO', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'DMCO', 'CO', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'DMCO', 'CO', 'S1F');
    InsertClassEvent($TourId, 1, 1, 'DMCO', 'CO', 'S2F');
    InsertClassEvent($TourId, 1, 1, 'DMCO', 'CO', 'S3F');
    InsertClassEvent($TourId, 2, 1, 'DMCO', 'CO', 'U13H');
    InsertClassEvent($TourId, 2, 1, 'DMCO', 'CO', 'U15H');
    InsertClassEvent($TourId, 2, 1, 'DMCO', 'CO', 'U18H');
    InsertClassEvent($TourId, 2, 1, 'DMCO', 'CO', 'U21H');
    InsertClassEvent($TourId, 2, 1, 'DMCO', 'CO', 'S1H');
    InsertClassEvent($TourId, 2, 1, 'DMCO', 'CO', 'S2H');
    InsertClassEvent($TourId, 2, 1, 'DMCO', 'CO', 'S3H');
    
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U11F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'S1F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'S2F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'S3F');
    InsertClassEvent($TourId, 2, 1, 'F', 'CO', 'U13F');
    InsertClassEvent($TourId, 2, 1, 'F', 'CO', 'U15F');
    InsertClassEvent($TourId, 2, 1, 'F', 'CO', 'U18F');
    InsertClassEvent($TourId, 2, 1, 'F', 'CO', 'U21F');
    InsertClassEvent($TourId, 2, 1, 'F', 'CO', 'S1F');
    InsertClassEvent($TourId, 2, 1, 'F', 'CO', 'S2F');
    InsertClassEvent($TourId, 2, 1, 'F', 'CO', 'S3F');
    InsertClassEvent($TourId, 3, 1, 'F', 'BB', 'U13F');
    InsertClassEvent($TourId, 3, 1, 'F', 'BB', 'U15F');
    InsertClassEvent($TourId, 3, 1, 'F', 'BB', 'U18F');
    InsertClassEvent($TourId, 3, 1, 'F', 'BB', 'U21F');
    InsertClassEvent($TourId, 3, 1, 'F', 'BB', 'S1F');
    InsertClassEvent($TourId, 3, 1, 'F', 'BB', 'S2F');
    InsertClassEvent($TourId, 3, 1, 'F', 'BB', 'S3F');
    
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U11H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U13H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U15H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U18H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U21H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'S1H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'S2H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'S3H');
    InsertClassEvent($TourId, 2, 1, 'H', 'CO', 'U13H');
    InsertClassEvent($TourId, 2, 1, 'H', 'CO', 'U15H');
    InsertClassEvent($TourId, 2, 1, 'H', 'CO', 'U18H');
    InsertClassEvent($TourId, 2, 1, 'H', 'CO', 'U21H');
    InsertClassEvent($TourId, 2, 1, 'H', 'CO', 'S1H');
    InsertClassEvent($TourId, 2, 1, 'H', 'CO', 'S2H');
    InsertClassEvent($TourId, 2, 1, 'H', 'CO', 'S3H');
    InsertClassEvent($TourId, 3, 1, 'H', 'BB', 'U13H');
    InsertClassEvent($TourId, 3, 1, 'H', 'BB', 'U15H');
    InsertClassEvent($TourId, 3, 1, 'H', 'BB', 'U18H');
    InsertClassEvent($TourId, 3, 1, 'H', 'BB', 'U21H');
    InsertClassEvent($TourId, 3, 1, 'H', 'BB', 'S1H');
    InsertClassEvent($TourId, 3, 1, 'H', 'BB', 'S2H');
    InsertClassEvent($TourId, 3, 1, 'H', 'BB', 'S3H');

	switch($SubRule) { // if sélectif ≠ Championnat de France
		case '1':
			InsertClassEvent($TourId, 0, 1, 'DEC',  'CL',  'DEC');
			InsertClassEvent($TourId, 0, 1, 'DEC',  'CO',  'DEC');
			InsertClassEvent($TourId, 0, 1, 'DEC',  'BB',  'DEC');
			InsertClassEvent($TourId, 0, 1, 'DEC',  'AD',  'DEC');
			/* Sélectif only
            InsertClassEvent($TourId, 0, 1, 'U21FBB',  'BB',  'U21F');
			InsertClassEvent($TourId, 0, 1, 'U21HBB',  'BB',  'U21H');
			InsertClassEvent($TourId, 0, 1, 'U15FCO',  'CO',  'U13F');
			InsertClassEvent($TourId, 0, 1, 'U15FCO',  'CO',  'U15F');
			InsertClassEvent($TourId, 0, 1, 'U15HCO',  'CO',  'U13H');
			InsertClassEvent($TourId, 0, 1, 'U15HCO',  'CO',  'U15H');
            */
            break;
        case '2':
			/* Championnat de France only
            InsertClassEvent($TourId, 0, 1, 'S1FBB',  'BB',  'U21F');
			InsertClassEvent($TourId, 0, 1, 'S1HBB',  'BB',  'U21H');
            */
			break;
	}
}

function InsertStandardFieldEliminations($TourId, $SubRule){
	$cls=array();
	switch($SubRule) {
		case '1':
			$cls=array('M', 'W', 'JM', 'JW', 'CM', 'CW', 'MM', 'MW');
			break;
		case '2':
			$cls=array('M', 'W', 'JM', 'JW');
			break;
	}
	foreach(array('R', 'C', 'B') as $div) {
		foreach($cls as $cl) {
			for($n=1; $n<=16; $n++) {
				safe_w_SQL("INSERT INTO Eliminations set ElId=0, ElElimPhase=0, ElEventCode='$div$cl', ElTournament=$TourId, ElQualRank=$n");
			}
			for($n=1; $n<=8; $n++) {
				safe_w_SQL("INSERT INTO Eliminations set ElId=0, ElElimPhase=1, ElEventCode='$div$cl', ElTournament=$TourId, ElQualRank=$n");
			}
		}
	}
}

