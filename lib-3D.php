<?php

/*

3D DEFINITIONS (Target Tournaments)

*/
function CreateStandard3DClasses($TourId, $SubRule) {
    $NextYearClass=intval(substr($_SESSION['TourRealWhenFrom'], 5,2))>=9 ? -1 : 0;
    $i=1;
    $AllowedDivs=($SubRule==2?'CL':'');
    CreateClass($TourId, $i++, 1, 10+$NextYearClass, 1, 'U11F', 'U11F,U13F', 'U11 Femme', 1, 'CL');
    CreateClass($TourId, $i++, 1, 10+$NextYearClass, 0, 'U11H', 'U11H,U13H', 'U11 Homme', 1, 'CL');
    CreateClass($TourId, $i++, 11+$NextYearClass, 12+$NextYearClass, 1, 'U13F', 'U13F,U15F', 'U13 Femme', 1, $AllowedDivs);
    CreateClass($TourId, $i++, 11+$NextYearClass, 12+$NextYearClass, 0, 'U13H', 'U13H,U15H', 'U13 Homme', 1, $AllowedDivs);
    CreateClass($TourId, $i++, 13+$NextYearClass, 14+$NextYearClass, 1, 'U15F', 'U15F,U18F', 'U15 Femme', 1, $AllowedDivs);
    CreateClass($TourId, $i++, 13+$NextYearClass, 14+$NextYearClass, 0, 'U15H', 'U15H,U18H', 'U15 Homme', 1, $AllowedDivs);
    CreateClass($TourId, $i++, 15+$NextYearClass, 17+$NextYearClass, 1, 'U18F', 'S1F,U18F,U21F', 'U18 Femme', 1, '', 'U18W', 'U18W');
    CreateClass($TourId, $i++, 15+$NextYearClass, 17+$NextYearClass, 0, 'U18H', 'S1H,U18H,U21H', 'U18 Homme', 1, '', 'U18M', 'U18M');
    CreateClass($TourId, $i++, 18+$NextYearClass, 20+$NextYearClass, 1, 'U21F', 'S1F,U21F', 'U21 Femme', 1, '', 'U21W', 'U21W');
    CreateClass($TourId, $i++, 18+$NextYearClass, 20+$NextYearClass, 0, 'U21H', 'S1H,U21H', 'U21 Homme', 1, '', 'U21M', 'U21M');
    CreateClass($TourId, $i++, 21+$NextYearClass, 39+$NextYearClass, 1, 'S1F', 'S1F', 'Senior 1 Femme', 1, '', 'W', 'W');
    CreateClass($TourId, $i++, 21+$NextYearClass, 39+$NextYearClass, 0, 'S1H', 'S1H', 'Senior 1 Homme', 1, '', 'M', 'M');
    CreateClass($TourId, $i++, 40+$NextYearClass, 59+$NextYearClass, 1, 'S2F', 'S1F,S2F', 'Senior 2 Femme', 1, '');
    CreateClass($TourId, $i++, 40+$NextYearClass, 59+$NextYearClass, 0, 'S2H', 'S1H,S2H', 'Senior 2 Homme', 1, '');
    CreateClass($TourId, $i++, 60+$NextYearClass, 127, 1, 'S3F', 'S1F,S2F,S3F', 'Senior 3 Femme', 1, '', '50W', '50W');
    CreateClass($TourId, $i++, 60+$NextYearClass, 127, 0, 'S3H', 'S1H,S2H,S3H', 'Senior 3 Homme', 1, '', '50M', '50M');
    if($SubRule==1) {
        CreateClass($TourId, $i++, 1, 127, -1, 'DEC', 'DEC', 'Découverte', 1);
    }
}
function CreateStandard3DEvents($TourId, $SubRule) {
    $SettingsInd=array(
        'EvFinalFirstPhase' => 0,
        'EvFinalTargetType'=>8,
        'EvElimEnds'=>6,
        'EvElimArrows'=>2,
        'EvElimSO'=>1,
        'EvFinEnds'=>4,
        'EvFinArrows'=>2,
        'EvFinSO'=>1,
        'EvFinalAthTarget'=>255,
        'EvMatchArrowsNo'=>FINAL_FROM_2,
    );
    $SettingsTeam=array(
        'EvTeamEvent' => 1,
        'EvFinalFirstPhase' => 0,
        'EvFinalTargetType'=>8,
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
        'EvFinalTargetType'=>8,
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
        'U11FCL' => 'U11 Femme Arc Nu',
        'U11HCL' => 'U11 Homme Arc Nu',
        'U13FCL' => 'U13 Femme Arc Nu',
        'U13HCL' => 'U13 Homme Arc Nu',
        'U15FCL' => 'U15 Femme Arc Nu',
        'U15HCL' => 'U15 Homme Arc Nu',
        'U21FCL' => 'U18-U21 Femme Arc Nu',
        'U21HCL' => 'U18-U21 Homme Arc Nu',
        'S1FCL' => 'Senior 1 Femme Arc Nu',
        'S1HCL' => 'Senior 1 Homme Arc Nu',
        'S2FCL' => 'Senior 2 Femme Arc Nu',
        'S2HCL' => 'Senior 2 Homme Arc Nu',
        'S3FCL' => 'Senior 3 Femme Arc Nu',
        'S3HCL' => 'Senior 3 Homme Arc Nu',

        'U15FCO' => 'U13-U15 Femme Arc à Poulies Nu',
        'U15HCO' => 'U13-U15 Homme Arc à Poulies Nu',
        'U21FCO' => 'U18-U21 Femme Arc à Poulies Nu',
        'U21HCO' => 'U18-U21 Homme Arc à Poulies Nu',
        'SFCO' => 'Scratch Femme Arc à Poulies Nu',
        'SHCO' => 'Scratch Homme Arc à Poulies Nu',

        'U15FTL' => 'U13-U15 Femme Arc Libre',
        'U15HTL' => 'U13-U15 Homme Arc Libre',
        'U21FTL' => 'U18-U21 Femme Arc Libre',
        'U21HTL' => 'U18-U21 Homme Arc Libre',
        'S1FTL' => 'Senior 1 Femme Arc Libre',
        'S1HTL' => 'Senior 1 Homme Arc Libre',
        'S2FTL' => 'Senior 2 Femme Arc Libre',
        'S2HTL' => 'Senior 2 Homme Arc Libre',
        'S3FTL' => 'Senior 3 Femme Arc Libre',
        'S3HTL' => 'Senior 3 Homme Arc Libre',

        'U15FAD' => 'U13-U15 Femme Arc Droit',
        'U15HAD' => 'U13-U15 Homme Arc Droit',
        'U21FAD' => 'U18-U21 Femme Arc Droit',
        'U21HAD' => 'U18-U21 Homme Arc Droit',
        'S1FAD' => 'Senior 1 Femme Arc Droit',
        'S1HAD' => 'Senior 1 Homme Arc Droit',
        'S2FAD' => 'Senior 2 Femme Arc Droit',
        'S2HAD' => 'Senior 2 Homme Arc Droit',
        'S3FAD' => 'Senior 3 Femme Arc Droit',
        'S3HAD' => 'Senior 3 Homme Arc Droit',

        'U15FAC' => 'U13-U15 Femme Arc Chasse',
        'U15HAC' => 'U13-U15 Homme Arc Chasse',
        'U21FAC' => 'U18-U21 Femme Arc Chasse',
        'U21HAC' => 'U18-U21 Homme Arc Chasse',
        'S1FAC' => 'Senior 1 Femme Arc Chasse',
        'S1HAC' => 'Senior 1 Homme Arc Chasse',
        'S2FAC' => 'Senior 2 Femme Arc Chasse',
        'S2HAC' => 'Senior 2 Homme Arc Chasse',
        'S3FAC' => 'Senior 3 Femme Arc Chasse',
        'S3HAC' => 'Senior 3 Homme Arc Chasse',

        'DEC' => 'Découverte',
    ];
    $FirstPhases=array_fill_keys(array_keys($Events), 0);
    if($SubRule==2) {
        unset($Events['DEC']);
        unset($Events['U15FTL']);
        unset($Events['U15HTL']);
        unset($Events['U21FCL']);
        unset($Events['U21HCL']);
        $Events['S1FCL']='U21 - Senior 1 Femme Arc Nu';
        $Events['S1HCL']='U21 - Senior 1 Homme Arc Nu';
        unset($Events['U21FAD']);
        unset($Events['U21HAD']);
        $Events['S1FAD']='U21 - Senior 1 Femme Arc Droit';
        $Events['S1HAD']='U21 - Senior 1 Homme Arc Droit';
        unset($Events['U21FAC']);
        unset($Events['U21HAC']);
        $Events['S1FAC']='U21 - Senior 1 Femme Arc Chasse';
        $Events['S1HAC']='U21 - Senior 1 Homme Arc Chasse';

        unset($Events['U21FCO']);
        unset($Events['U21HCO']);
        unset($Events['S1FCO']);
        unset($Events['S1HCO']);
        unset($Events['S2FCO']);
        unset($Events['S2HCO']);
        unset($Events['S3FCO']);
        unset($Events['S3HCO']);

        $FirstPhases['S1FCL']=8;
        $FirstPhases['S1HCL']=8;
        $FirstPhases['S2FCL']=8;
        $FirstPhases['S2HCL']=8;
        $FirstPhases['S3HCL']=8;
        $FirstPhases['S3FCL']=4;
        $FirstPhases['SFCO']=2;
        $FirstPhases['SHCO']=8;
        $FirstPhases['S1FAD']=2;
        $FirstPhases['S1HAD']=4;
        $FirstPhases['S2FAD']=4;
        $FirstPhases['S2HAD']=8;
        $FirstPhases['S3FAD']=4;
        $FirstPhases['S3HAD']=8;
        $FirstPhases['U21FTL']=2;
        $FirstPhases['U21HTL']=2;
        $FirstPhases['S1FTL']=4;
        $FirstPhases['S1HTL']=8;
        $FirstPhases['S2FTL']=4;
        $FirstPhases['S2HTL']=8;
        $FirstPhases['S3FTL']=2;
        $FirstPhases['S3HTL']=8;
        $FirstPhases['S1FAC']=4;
        $FirstPhases['S1HAC']=4;
        $FirstPhases['S2FAC']=8;
        $FirstPhases['S2HAC']=8;
        $FirstPhases['S3FAC']=4;
        $FirstPhases['S3HAC']=8;

    }
    foreach($Events as $code=>$event) {
        $SettingsInd['EvFinalFirstPhase']=$FirstPhases[$code];
        CreateEventNew($TourId, $code, $event, $i++, $SettingsInd);
    }

    $i=1;
    CreateEventNew($TourId, 'F', 'Equipe Femme', $i++, $SettingsTeam);
    CreateEventNew($TourId, 'H', 'Equipes Homme', $i++, $SettingsTeam);
    CreateEventNew($TourId, 'DMCL', 'Double Mixte Arc Nu', $i++, $SettingsMixedTeam);
    CreateEventNew($TourId, 'DMTL', 'Double Mixte Arc Libre', $i++, $SettingsMixedTeam);
    CreateEventNew($TourId, 'DMAD', 'Double Mixte Arc Droit', $i++, $SettingsMixedTeam);
    CreateEventNew($TourId, 'DMAC', 'Double Mixte Arc Chasse', $i++, $SettingsMixedTeam);
}

function InsertStandard3DEvents($TourId, $SubRule) {
    // Common Individual
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
    InsertClassEvent($TourId, 0, 1, 'SFCO',  'CO',  'S1F');
    InsertClassEvent($TourId, 0, 1, 'SHCO',  'CO',  'S1H');
    InsertClassEvent($TourId, 0, 1, 'SFCO',  'CO',  'S2F');
    InsertClassEvent($TourId, 0, 1, 'SHCO',  'CO',  'S2H');
    InsertClassEvent($TourId, 0, 1, 'SFCO',  'CO',  'S3F');
    InsertClassEvent($TourId, 0, 1, 'SHCO',  'CO',  'S3H');

    InsertClassEvent($TourId, 0, 1, 'U15FTL',  'TL',  'U13F');
    InsertClassEvent($TourId, 0, 1, 'U15HTL',  'TL',  'U13H');
    InsertClassEvent($TourId, 0, 1, 'U15FTL',  'TL',  'U15F');
    InsertClassEvent($TourId, 0, 1, 'U15HTL',  'TL',  'U15H');
    InsertClassEvent($TourId, 0, 1, 'U21FTL',  'TL',  'U18F');
    InsertClassEvent($TourId, 0, 1, 'U21HTL',  'TL',  'U18H');
    InsertClassEvent($TourId, 0, 1, 'U21FTL',  'TL',  'U21F');
    InsertClassEvent($TourId, 0, 1, 'U21HTL',  'TL',  'U21H');
    InsertClassEvent($TourId, 0, 1, 'S1FTL',  'TL',  'S1F');
    InsertClassEvent($TourId, 0, 1, 'S1HTL',  'TL',  'S1H');
    InsertClassEvent($TourId, 0, 1, 'S2FTL',  'TL',  'S2F');
    InsertClassEvent($TourId, 0, 1, 'S2HTL',  'TL',  'S2H');
    InsertClassEvent($TourId, 0, 1, 'S3FTL',  'TL',  'S3F');
    InsertClassEvent($TourId, 0, 1, 'S3HTL',  'TL',  'S3H');

    InsertClassEvent($TourId, 0, 1, 'U15FAD',  'AD',  'U13F');
    InsertClassEvent($TourId, 0, 1, 'U15HAD',  'AD',  'U13H');
    InsertClassEvent($TourId, 0, 1, 'U15FAD',  'AD',  'U15F');
    InsertClassEvent($TourId, 0, 1, 'U15HAD',  'AD',  'U15H');
    InsertClassEvent($TourId, 0, 1, 'U21FAD',  'AD',  'U18F');
    InsertClassEvent($TourId, 0, 1, 'U21HAD',  'AD',  'U18H');
    InsertClassEvent($TourId, 0, 1, 'U21FAD',  'AD',  'U21F');
    InsertClassEvent($TourId, 0, 1, 'U21HAD',  'AD',  'U21H');
    InsertClassEvent($TourId, 0, 1, 'S1FAD',  'AD',  'S1F');
    InsertClassEvent($TourId, 0, 1, 'S1HAD',  'AD',  'S1H');
    InsertClassEvent($TourId, 0, 1, 'S2FAD',  'AD',  'S2F');
    InsertClassEvent($TourId, 0, 1, 'S2HAD',  'AD',  'S2H');
    InsertClassEvent($TourId, 0, 1, 'S3FAD',  'AD',  'S3F');
    InsertClassEvent($TourId, 0, 1, 'S3HAD',  'AD',  'S3H');

    InsertClassEvent($TourId, 0, 1, 'U15FAC',  'AC',  'U13F');
    InsertClassEvent($TourId, 0, 1, 'U15HAC',  'AC',  'U13H');
    InsertClassEvent($TourId, 0, 1, 'U15FAC',  'AC',  'U15F');
    InsertClassEvent($TourId, 0, 1, 'U15HAC',  'AC',  'U15H');
    InsertClassEvent($TourId, 0, 1, 'U21FAC',  'AC',  'U18F');
    InsertClassEvent($TourId, 0, 1, 'U21HAC',  'AC',  'U18H');
    InsertClassEvent($TourId, 0, 1, 'U21FAC',  'AC',  'U21F');
    InsertClassEvent($TourId, 0, 1, 'U21HAC',  'AC',  'U21H');
    InsertClassEvent($TourId, 0, 1, 'S1FAC',  'AC',  'S1F');
    InsertClassEvent($TourId, 0, 1, 'S1HAC',  'AC',  'S1H');
    InsertClassEvent($TourId, 0, 1, 'S2FAC',  'AC',  'S2F');
    InsertClassEvent($TourId, 0, 1, 'S2HAC',  'AC',  'S2H');
    InsertClassEvent($TourId, 0, 1, 'S3FAC',  'AC',  'S3F');
    InsertClassEvent($TourId, 0, 1, 'S3HAC',  'AC',  'S3H');

    // specific Individual
    if($SubRule==1) {
        InsertClassEvent($TourId, 0, 1, 'DEC',  'CL',  'DEC');
        InsertClassEvent($TourId, 0, 1, 'DEC',  'CO',  'DEC');
        InsertClassEvent($TourId, 0, 1, 'DEC',  'AD',  'DEC');
        InsertClassEvent($TourId, 0, 1, 'DEC',  'TL',  'DEC');
        InsertClassEvent($TourId, 0, 1, 'DEC',  'AC',  'DEC');
    } else {
        InsertClassEvent($TourId, 0, 1, 'S1FCL',  'CL',  'U21F');
        InsertClassEvent($TourId, 0, 1, 'S1HCL',  'CL',  'U21H');
        InsertClassEvent($TourId, 0, 1, 'S1FAD',  'AD',  'U21F');
        InsertClassEvent($TourId, 0, 1, 'S1HAD',  'AD',  'U21H');
        InsertClassEvent($TourId, 0, 1, 'S1FAC',  'AC',  'U21F');
        InsertClassEvent($TourId, 0, 1, 'S1HAC',  'AC',  'U21H');
    }

    // Teams
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U11F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'S1F' );
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'S2F' );
    InsertClassEvent($TourId, 1, 1, 'F', 'CL', 'S3F' );
    InsertClassEvent($TourId, 1, 1, 'F', 'AC', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'F', 'AC', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'F', 'AC', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'F', 'AC', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'F', 'AC', 'S1F' );
    InsertClassEvent($TourId, 1, 1, 'F', 'AC', 'S2F' );
    InsertClassEvent($TourId, 1, 1, 'F', 'AC', 'S3F' );
    InsertClassEvent($TourId, 2, 1, 'F', 'AD', 'U13F');
    InsertClassEvent($TourId, 2, 1, 'F', 'AD', 'U15F');
    InsertClassEvent($TourId, 2, 1, 'F', 'AD', 'U18F');
    InsertClassEvent($TourId, 2, 1, 'F', 'AD', 'U21F');
    InsertClassEvent($TourId, 2, 1, 'F', 'AD', 'S1F' );
    InsertClassEvent($TourId, 2, 1, 'F', 'AD', 'S2F' );
    InsertClassEvent($TourId, 2, 1, 'F', 'AD', 'S3F' );
    InsertClassEvent($TourId, 3, 1, 'F', 'CO', 'U13F');
    InsertClassEvent($TourId, 3, 1, 'F', 'CO', 'U15F');
    InsertClassEvent($TourId, 3, 1, 'F', 'CO', 'U18F');
    InsertClassEvent($TourId, 3, 1, 'F', 'CO', 'U21F');
    InsertClassEvent($TourId, 3, 1, 'F', 'CO', 'S1F' );
    InsertClassEvent($TourId, 3, 1, 'F', 'CO', 'S2F' );
    InsertClassEvent($TourId, 3, 1, 'F', 'CO', 'S3F' );
    InsertClassEvent($TourId, 3, 1, 'F', 'TL', 'U13F');
    InsertClassEvent($TourId, 3, 1, 'F', 'TL', 'U15F');
    InsertClassEvent($TourId, 3, 1, 'F', 'TL', 'U18F');
    InsertClassEvent($TourId, 3, 1, 'F', 'TL', 'U21F');
    InsertClassEvent($TourId, 3, 1, 'F', 'TL', 'S1F' );
    InsertClassEvent($TourId, 3, 1, 'F', 'TL', 'S2F' );
    InsertClassEvent($TourId, 3, 1, 'F', 'TL', 'S3F' );

    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U11H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U13H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U15H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U18H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'U21H');
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'S1H' );
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'S2H' );
    InsertClassEvent($TourId, 1, 1, 'H', 'CL', 'S3H' );
    InsertClassEvent($TourId, 1, 1, 'H', 'AC', 'U13H');
    InsertClassEvent($TourId, 1, 1, 'H', 'AC', 'U15H');
    InsertClassEvent($TourId, 1, 1, 'H', 'AC', 'U18H');
    InsertClassEvent($TourId, 1, 1, 'H', 'AC', 'U21H');
    InsertClassEvent($TourId, 1, 1, 'H', 'AC', 'S1H' );
    InsertClassEvent($TourId, 1, 1, 'H', 'AC', 'S2H' );
    InsertClassEvent($TourId, 1, 1, 'H', 'AC', 'S3H' );
    InsertClassEvent($TourId, 2, 1, 'H', 'AD', 'U13H');
    InsertClassEvent($TourId, 2, 1, 'H', 'AD', 'U15H');
    InsertClassEvent($TourId, 2, 1, 'H', 'AD', 'U18H');
    InsertClassEvent($TourId, 2, 1, 'H', 'AD', 'U21H');
    InsertClassEvent($TourId, 2, 1, 'H', 'AD', 'S1H' );
    InsertClassEvent($TourId, 2, 1, 'H', 'AD', 'S2H' );
    InsertClassEvent($TourId, 2, 1, 'H', 'AD', 'S3H' );
    InsertClassEvent($TourId, 3, 1, 'H', 'CO', 'U13H');
    InsertClassEvent($TourId, 3, 1, 'H', 'CO', 'U15H');
    InsertClassEvent($TourId, 3, 1, 'H', 'CO', 'U18H');
    InsertClassEvent($TourId, 3, 1, 'H', 'CO', 'U21H');
    InsertClassEvent($TourId, 3, 1, 'H', 'CO', 'S1H' );
    InsertClassEvent($TourId, 3, 1, 'H', 'CO', 'S2H' );
    InsertClassEvent($TourId, 3, 1, 'H', 'CO', 'S3H' );
    InsertClassEvent($TourId, 3, 1, 'H', 'TL', 'U13H');
    InsertClassEvent($TourId, 3, 1, 'H', 'TL', 'U15H');
    InsertClassEvent($TourId, 3, 1, 'H', 'TL', 'U18H');
    InsertClassEvent($TourId, 3, 1, 'H', 'TL', 'U21H');
    InsertClassEvent($TourId, 3, 1, 'H', 'TL', 'S1H' );
    InsertClassEvent($TourId, 3, 1, 'H', 'TL', 'S2H' );
    InsertClassEvent($TourId, 3, 1, 'H', 'TL', 'S3H' );

    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U11F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'S1F' );
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'S2F' );
    InsertClassEvent($TourId, 1, 1, 'DMCL', 'CL', 'S3F' );
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U11H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U13H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U15H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U18H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'U21H');
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'S1H' );
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'S2H' );
    InsertClassEvent($TourId, 2, 1, 'DMCL', 'CL', 'S3H' );

    InsertClassEvent($TourId, 1, 1, 'DMAC', 'AC', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'DMAC', 'AC', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'DMAC', 'AC', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'DMAC', 'AC', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'DMAC', 'AC', 'S1F' );
    InsertClassEvent($TourId, 1, 1, 'DMAC', 'AC', 'S2F' );
    InsertClassEvent($TourId, 1, 1, 'DMAC', 'AC', 'S3F' );
    InsertClassEvent($TourId, 2, 1, 'DMAC', 'AC', 'U13H');
    InsertClassEvent($TourId, 2, 1, 'DMAC', 'AC', 'U15H');
    InsertClassEvent($TourId, 2, 1, 'DMAC', 'AC', 'U18H');
    InsertClassEvent($TourId, 2, 1, 'DMAC', 'AC', 'U21H');
    InsertClassEvent($TourId, 2, 1, 'DMAC', 'AC', 'S1H' );
    InsertClassEvent($TourId, 2, 1, 'DMAC', 'AC', 'S2H' );
    InsertClassEvent($TourId, 2, 1, 'DMAC', 'AC', 'S3H' );

    InsertClassEvent($TourId, 1, 1, 'DMAD', 'AD', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'DMAD', 'AD', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'DMAD', 'AD', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'DMAD', 'AD', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'DMAD', 'AD', 'S1F' );
    InsertClassEvent($TourId, 1, 1, 'DMAD', 'AD', 'S2F' );
    InsertClassEvent($TourId, 1, 1, 'DMAD', 'AD', 'S3F' );
    InsertClassEvent($TourId, 2, 1, 'DMAD', 'AD', 'U13H');
    InsertClassEvent($TourId, 2, 1, 'DMAD', 'AD', 'U15H');
    InsertClassEvent($TourId, 2, 1, 'DMAD', 'AD', 'U18H');
    InsertClassEvent($TourId, 2, 1, 'DMAD', 'AD', 'U21H');
    InsertClassEvent($TourId, 2, 1, 'DMAD', 'AD', 'S1H' );
    InsertClassEvent($TourId, 2, 1, 'DMAD', 'AD', 'S2H' );
    InsertClassEvent($TourId, 2, 1, 'DMAD', 'AD', 'S3H' );

    InsertClassEvent($TourId, 1, 1, 'DMTL', 'TL', 'U13F');
    InsertClassEvent($TourId, 1, 1, 'DMTL', 'TL', 'U15F');
    InsertClassEvent($TourId, 1, 1, 'DMTL', 'TL', 'U18F');
    InsertClassEvent($TourId, 1, 1, 'DMTL', 'TL', 'U21F');
    InsertClassEvent($TourId, 1, 1, 'DMTL', 'TL', 'S1F' );
    InsertClassEvent($TourId, 1, 1, 'DMTL', 'TL', 'S2F' );
    InsertClassEvent($TourId, 1, 1, 'DMTL', 'TL', 'S3F' );
    InsertClassEvent($TourId, 2, 1, 'DMTL', 'TL', 'U13H');
    InsertClassEvent($TourId, 2, 1, 'DMTL', 'TL', 'U15H');
    InsertClassEvent($TourId, 2, 1, 'DMTL', 'TL', 'U18H');
    InsertClassEvent($TourId, 2, 1, 'DMTL', 'TL', 'U21H');
    InsertClassEvent($TourId, 2, 1, 'DMTL', 'TL', 'S1H' );
    InsertClassEvent($TourId, 2, 1, 'DMTL', 'TL', 'S2H' );
    InsertClassEvent($TourId, 2, 1, 'DMTL', 'TL', 'S3H' );
}

function InsertStandard3DEliminations($TourId, $SubRule){
	$cls=array();
	switch($SubRule) {
		case '1':
			$cls=array('M', 'W', 'JM', 'JW', 'CM', 'CW', 'MM', 'MW');
			break;
		case '2':
			$cls=array('M', 'W');
			break;
	}
	foreach(array('C', 'B', 'L', 'I') as $div) {
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
