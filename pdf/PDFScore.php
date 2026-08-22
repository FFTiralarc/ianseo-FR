<?php

require_once(dirname(dirname(dirname(__DIR__))) . '/config.php');
require_once('Common/pdf/ScorePDF.inc.php');
require_once('Common/Fun_FormatText.inc.php');
require_once('Common/Fun_Sessions.inc.php');
require_once('Common/Lib/ScorecardsLib.php');
checkFullACL(AclQualification, '', AclReadOnly);

if($_REQUEST['Marmot']??'') {
    require_once(__DIR__.'/Marmot.php');
    die;
}

// switch to decide which scorecard type to print $_REQUEST['TourField3D']=$_SESSION['TourField3D'];

$Session=intval($_REQUEST['x_Session'] ?? 0);
$_REQUEST['ScoreDist']=array(1);

$pdf=CreateBeursaultScorecard(
    $Session,
    $_REQUEST['x_From'] ?? 1,
    empty($_REQUEST['x_To']) ? ($_REQUEST['x_From'] ?? 1) : $_REQUEST['x_To'],
    $_REQUEST
);

$pdf->output();

function CreateBeursaultScorecard($Session, $FromTgt=1, $ToTgt=999, $Options=array(), $SaveDir='', $File='') {
    global $CFG;

    if($Session=='ONLINE') {
        $FromTgt=1;
        $ToTgt=999;
        $Options['ScoreDraw'] = "Complete";
        $Options['ScoreHeader'] = "1";
        $Options['ScoreLogos'] = "1";
        $Options['ScoreFlags'] = "1";
        $Options['ScoreBarcode'] = "1";
        $Options['PersonalScore'] = "1";
        $Options['ScoreFilled'] = "1";
        $Options['ScoreDist'] = [1];
    }

    $ScoreDraw=($Options["ScoreDraw"]??'');
    $FillWithArrows = !empty($Options["ScoreFilled"]);
    $ShowConsignes = empty($Options["ScoreFilled"]);

    $pdf = new ScorePDF(true);
    $pdf->setMargins(20, 15, 20, true);

    if(!empty($Options["QRCode"])) {
        $pdf->QRCode=$Options["QRCode"];
        $pdf->BottomImage=false;
    }

    $pdf->FillWithArrows=$FillWithArrows;

    if(empty($Options["ScoreHeader"])) {
        $pdf->HideHeader();
    }

    if(empty($Options["ScoreLogos"])) {
        $pdf->HideLogo();
    }

    if(empty($Options["ScoreFlags"])) {
        $pdf->HideFlags();
    }

    if(!empty($Options["ScoreBarcode"])) {
        $pdf->PrintBarcode=true;
    }

    if($Options['noEmpty']??'') {
        $SQL="select QuD1Hits as Hits, QuD1Score as Score, QuD1Gold as Gold, QuD1Xnine as XNine, QuD1Arrowstring as Arrowstring, QuTarget as Target, QuLetter as Letter, QuSession as Session,
                EnFirstName, EnName, EnCode, DivId, DivDescription, ClId, ClDescription, EvCode, EvEventName,
                CoCode, CoName, CoNameComplete,
                SesName, SesDtStart, SesLocation
            from Qualifications
            inner join Entries on EnId=QuId and EnTournament={$_SESSION['TourId']}
            inner join Countries on CoId=EnCountry
            inner join Tournament on ToId=EnTournament
            left join Session on SesTournament=EnTournament and SesOrder=QuSession and SesType='Q'
            inner join Divisions on DivTournament=EnTournament and DivId=EnDivision
            inner join Classes on ClTournament=EnTournament and ClId=EnClass
            inner join Individuals on IndId=EnId and IndTournament=EnTournament
            inner join Events on EvCode=IndEvent and EvTournament=EnTournament and EvTeamEvent=0
            where QuTarget between $FromTgt and $ToTgt and QuSession=$Session
            order by QuSession, QuTarget, QuLetter";
    } else {
        $atSql = createAvailableTargetSQL(($Session??0), $_SESSION['TourId']);

        $SQL="select coalesce(QuD1Hits,0) as Hits, coalesce(QuD1Score,0) as Score, coalesce(QuD1Gold,0) as Gold, coalesce(QuD1Xnine,0) as XNine, coalesce(QuD1Arrowstring,'') as Arrowstring, FullTgtTarget as Target, FullTgtLetter as Letter, FullTgtSession as Session,
                EnFirstName, EnName, EnCode, DivId, DivDescription, ClId, ClDescription, EvCode, EvEventName,
                CoCode, CoName, CoNameComplete,
                SesName, SesDtStart, SesLocation
            from ($atSql) at
            inner join Tournament on ToId={$_SESSION['TourId']}
            left join Session on SesTournament=ToId and SesOrder=FullTgtSession and SesType='Q'
            left join (
                select QuD1Hits, QuD1Score, QuD1Gold, QuD1Xnine, QuD1Arrowstring, QuTarget, QuLetter, QuSession,
                        EnFirstName, EnName, EnCode, DivId, DivDescription, ClId, ClDescription, EvCode, EvEventName,
                        CoCode, CoName, CoNameComplete
                    from Qualifications
                    inner join Entries on EnId=QuId and EnTournament={$_SESSION['TourId']}
                    inner join Countries on CoId=EnCountry
                    inner join Divisions on DivTournament=EnTournament and DivId=EnDivision
                    inner join Classes on ClTournament=EnTournament and ClId=EnClass
                    inner join Individuals on IndId=EnId and IndTournament=EnTournament
                    inner join Events on EvCode=IndEvent and EvTournament=EnTournament and EvTeamEvent=0
                ) Archers on QuSession=FullTgtSession AND QuTarget=FullTgtTarget AND QuLetter=FullTgtLetter
            where FullTgtTarget between $FromTgt and $ToTgt and FullTgtSession=$Session";
    }

    $q=safe_r_sql($SQL);

    $tmp=$pdf->getMargins();
    $TopX=$tmp['left'];
    $TopY=$tmp['top'];
    $Width=$pdf->getPageWidth()-2*$TopX;
    $Height=$pdf->getPageHeight()-2*$TopY;
    $TmpLeft=0;
    $TmpRight=0;

    if($pdf->BottomImage and file_exists($IM=$pdf->ToPaths['ToBottom'])) {
        $Height-=7.5;
    }

    $LeftColumn=110;
    $LeftOffset=$LeftColumn+10+$TopX;
    $ScoreWidth=$pdf->getPageWidth()-$TopX-$LeftOffset;

    $ArrowEnds = getArrowEnds($Session);
    $MaxScore=($_SESSION['TourLocSubRule']=='SetFrBouquet' ? 3 : 4);
    $ScoreCell=$ScoreWidth/($MaxScore+2);
    $CellHeight=($Height-6-($pdf->PrintBarcode?7:0))/($ArrowEnds[1]['ends']+4+($pdf->PrintBarcode?1:0));
    $LogoHeight=15;

    ini_set('error_reporting', E_ALL);

    $VGap=2;

    while($r=safe_fetch($q)) {
        $Misses = [];
        $ArrowCounts = [];
        $Chapelets = '';

        if(empty($Options["ScoreFilled"])) {
            $r->Arrowstring='';
            $r->Hits='';
            $r->Score='';
        } else {
            $r->Arrowstring=DecodeFromString($r->Arrowstring, false, true);
            $ArrowCounts=array_count_values($r->Arrowstring);
            $Misses=$ArrowCounts;

            // Chapelets = nombre de flèches indiquées comme 4 + nombre de flèches indiquées comme 3
            $Chapelets=($ArrowCounts[4]??0) + ($ArrowCounts[3]??0);
        }

        $pdf->setLeftMargin(20);
        $pdf->AddPage('','',true);

        // Logos du tournoi
        if($pdf->PrintLogo) {
            if(file_exists($IM=$pdf->ToPaths['ToLeft']) ) {
                $im=getimagesize($IM);
                $pdf->Image($IM, $TopX, $TopY, 0, $LogoHeight);
                $TmpLeft = (1 + ($im[0] * $LogoHeight / $im[1]));
            }

            if(file_exists($IM=$pdf->ToPaths['ToRight']) ) {
                $im=getimagesize($IM);
                $TmpRight = ($im[0] * $LogoHeight / $im[1]);
                $pdf->Image($IM, $TopX+$LeftColumn-$TmpRight, $TopY, 0, $LogoHeight);
                $TmpRight++;
            }

            // Image du bas / sponsors
            // Sponsors disabled if QRCodes are to be printed!!!
            if($pdf->BottomImage and file_exists($IM=$pdf->ToPaths['ToBottom'])) {
                $BottomImage=7.5;
                $im=getimagesize($IM);
                $imgW = $Width;
                $imgH = $imgW * $im[1] / $im[0];

                if($imgH > $BottomImage) {
                    $imgH = $BottomImage;
                    $imgW = $imgH * $im[0] / $im[1];
                }

                $pdf->Image($IM, ($TopX+($Width-$imgW)/2), $pdf->getPageHeight()-10-$imgH, $imgW, $imgH);
            }
        }

        // Entête tournoi
        if($pdf->PrintHeader) {
            $pdf->setCellPadding(0);
            $pdf->SetColors(true);
            $pdf->SetFont($pdf->FontStd,'B',12);
            $pdf->SetXY($TopX+$TmpLeft,$TopY);
            $pdf->Cell($LeftColumn-$TmpLeft-$TmpRight, $LogoHeight/3, $pdf->Name, 0, 1, 'L', 0, 0, 1,false,'T','T');

            $pdf->SetFont($pdf->FontStd,'B',10);
            $pdf->SetX($TopX+$TmpLeft);
            $pdf->Cell($LeftColumn-$TmpLeft-$TmpRight, $LogoHeight/3, $pdf->Where, 0, 1,'L', 0, 0, 1);

            $pdf->SetFont($pdf->FontStd,'',10);
            $pdf->SetX($TopX+$TmpLeft);
            $pdf->Cell($LeftColumn-$TmpLeft-$TmpRight, $LogoHeight/3, TournamentDate2String($pdf->WhenF,$pdf->WhenT), 0, 1, 'L', 0, 1, 1, false, 'T', 'B');
        }

        $pdf->setCellPadding(2);

        // Allée
        // Ancien positionnement : $pdf->setY(55);
        // Nouveau positionnement : au plus près sous l'entête / logos
        $FirstBlockY = $TopY + (($pdf->PrintLogo || $pdf->PrintHeader) ? $LogoHeight : 0) + 5;
        $pdf->setY($FirstBlockY);

        $pdf->setFont('','b','12');
        $pdf->Cell($LeftColumn,5, 'Allée '.$r->Target.' '.$r->Letter, '',1,'C');

        // Bloc Archer + Club / Compagnie avec flag, sans bordures visibles
        $pdf->dY($VGap);

        $BlockX = $pdf->GetX();
        $BlockY = $pdf->GetY();
        $BlockH = 24;

        $FlagW = 25;
        $FlagH = 25;
        $FlagGap = 2;

        $TextX = $BlockX + $FlagW + $FlagGap;
        $TextW = $LeftColumn - $FlagW - $FlagGap;

        $ArcherText = $r->EnFirstName
            ? mb_strtoupper($r->EnFirstName, 'UTF-8').' '.mb_convert_case($r->EnName, MB_CASE_TITLE, 'UTF-8')
            : 'Archer: '.str_repeat('_', 33);

        $ClubName = trim((string)($r->CoNameComplete ?? ''));

        if($ClubName === '') {
            $ClubName = trim((string)($r->CoName ?? ''));
        }

        $ClubText = $ClubName !== '' ? $ClubName : 'Club / Compagnie: '.str_repeat('_', 30);

        // Flag à gauche, centré verticalement sur les deux lignes
        if($pdf->PrintFlags and !empty($r->CoCode)) {
            $FlagFile = $CFG->DOCUMENT_PATH.'TV/Photos/'.$_SESSION['TourCodeSafe'].'-Fl-'.$r->CoCode.'.jpg';

            if(is_file($FlagFile)) {
                @$pdf->Image(
                    $FlagFile,
                    $BlockX,
                    $BlockY + (($BlockH - $FlagH) / 2),
                    $FlagW,
                    $FlagH,
                    'JPG',
                    '',
                    '',
                    true,
                    300,
                    '',
                    false,
                    false,
                    1,
                    true
                );
            }
        }

        // Nom prénom de l'archer à droite, en haut
        $pdf->SetXY($TextX, $BlockY + 2);
        $pdf->setFont('','b','14');
        $pdf->Cell($TextW, 8, $ArcherText, '', 1, 'L', '', '', 1);

        // CoNameComplete ou CoName à droite, en bas — non gras
        $pdf->SetXY($TextX, $BlockY + 12);
        $pdf->setFont('','','12');
        $pdf->Cell($TextW, 7, $ClubText, '', 1, 'L', '', '', 1);

        // Repositionnement après le bloc complet
        $pdf->SetXY($BlockX, $BlockY + $BlockH);

        // Licence uniquement
        $pdf->setFont('','','10');
        $pdf->dY($VGap);
        $pdf->setCellPadding(0);
        $pdf->Cell(50,4, 'N° Licence ');
        $pdf->Cell($LeftColumn-50,4,$r->EnCode, '',1);

        // Catégories
        $pdf->Cell(50,4, 'Arme: ');
        $pdf->Cell($LeftColumn-50,4, $r->DivDescription??'', '',1);

        $pdf->Cell(50,4, 'Catégorie:');
        $pdf->Cell($LeftColumn-50,4, $r->ClDescription??'', '',1);

        $pdf->Cell(50,4, 'Epreuve:');
        $pdf->Cell($LeftColumn-50,4, $r->EvEventName??'', '',1);

        $pdf->setCellPadding(2);

        // Jeu d'arc = SesLocation
        // Le bloc est affiché uniquement si SesLocation n'est pas vide.
        $JeuArc = trim((string)($r->SesLocation ?? ''));

        if($JeuArc !== '') {
            $pdf->dY($VGap);
            $pdf->setFont('','b','10');
            $pdf->RoundedRect($pdf->GetX(),$pdf->GetY(),$LeftColumn,10,2);
            $pdf->Cell($LeftColumn,10, 'JEU D\'ARC DE '.$JeuArc, '',1);
        }

        // Départ = SesName
        $DepartText = trim((string)($r->SesName ?? ''));

        if($DepartText === '') {
            $DepartText = trim((string)($r->Session ?? ''));
        }

        $pdf->dY($VGap);
        $pdf->setFont('','b','10');
        $pdf->RoundedRect($pdf->GetX(),$pdf->GetY(),$LeftColumn,10,2);
        $pdf->Cell(22,10, 'DEPART:', '',0);
        $pdf->Cell($LeftColumn-22,10, $DepartText, '',1, 'L', '', '', 1);

        // Heure de tir = SesDtStart
        $HeureTir = FormatBeursaultSessionStart($r->SesDtStart ?? '');

        $pdf->dY($VGap);
        $pdf->RoundedRect($pdf->GetX(),$pdf->GetY(),$LeftColumn,10,2);
        $pdf->Cell($LeftColumn-35,10, 'HEURE DE TIR:', '',0);
        $pdf->Cell(35,10, $HeureTir, '',1, 'C');

        // Consignes
        // Elles sont affichées uniquement si ScoreFilled n'est pas coché.
        if($ShowConsignes) {
            $pdf->dY($VGap);
            $pdf->RoundedRect($pdf->GetX(),$pdf->GetY(),$LeftColumn,30,2);
            $pdf->MultiCell($LeftColumn, 30, "1) Cette feuille de marque doit être:\n  a) remplie au stylo à bille\n  b) signée par l'archer et le marqueur\n\n2) Toute rature devra être contresignée par l'arbitre", '',1);
        }

        // Signatures
        $pdf->dY($VGap);
        $OrgY=$pdf->GetY();
        $pdf->RoundedRect($pdf->GetX(),$pdf->GetY(),$LeftColumn,30,2);
        $pdf->Cell($LeftColumn, 0, "SIGNATURES", '',1, 'C');

        $pdf->dY(-5);
        $pdf->setFont('','','10');
        $pdf->Cell($LeftColumn/2, 0, "ARCHER:", '',0, 'L');
        $pdf->Cell($LeftColumn/2, 0, "MARQUEUR:", '',0, 'R');

        // Totaux à gauche
        $pdf->setY($OrgY+40);

        $pdf->setFont('','b','10');
        $pdf->RoundedRect($pdf->GetX(),$pdf->GetY(),$LeftColumn,10,2);
        $pdf->Cell($LeftColumn-15,10, 'HONNEURS:', '',0);
        $pdf->Cell(15,10, ($FillWithArrows and $r->EnFirstName) ? ($r->Hits-($Misses['M']??0)):'', '',1, 'R');

        $pdf->dY($VGap);
        $pdf->RoundedRect($pdf->GetX(),$pdf->GetY(),$LeftColumn,10,2);
        $pdf->Cell($LeftColumn-25,10, 'CHAPELETS:', '',0);
        $pdf->Cell(25,10, ($FillWithArrows and $r->EnFirstName) ? $Chapelets:'', '',1, 'R');

        $pdf->dY($VGap);
        $pdf->RoundedRect($pdf->GetX(),$pdf->GetY(),$LeftColumn,10,2);
        $pdf->Cell($LeftColumn-15,10, 'POINTS:', '',0);
        $pdf->Cell(15,10, $r->EnFirstName?$r->Score:'', '',1, 'R');

        // Tableau de marque
        $pdf->setLeftMargin($LeftOffset);
        $pdf->setXY($LeftOffset, $TopY);
        $pdf->setCellPadding(0.5);

        $pdf->setFont('','b','9');
        $pdf->Cell($ScoreCell, $CellHeight, 'FL', '1', 0, 'C', '1');

        foreach(range(0, $MaxScore) as $ar) {
            $pdf->Cell($ScoreCell, $CellHeight, $ar, '1', 0, 'C', '1');
        }

        $pdf->ln();

        $Hons=[];

        foreach(range(1, $ArrowEnds[1]['ends']) as $end) {
            $pdf->Cell($ScoreCell, $CellHeight, $end, '1', 0, 'C', '1');

            foreach(range(0, $MaxScore) as $ar) {
                $txt='';

                if($FillWithArrows) {
                    $tmp=($r->Arrowstring[$end-1]??'');

                    if($tmp==$ar or ($tmp=='M' and $ar==0)) {
                        $txt=$tmp;
                        $Hons[$ar]=($Hons[$ar]??0)+1;
                    }
                }

                $pdf->Cell($ScoreCell, $CellHeight, $txt, '1', 0, 'C', '');
            }

            $pdf->ln();
        }

        // Ligne HON
        $pdf->dy(2);
        $pdf->Cell($ScoreCell, $CellHeight, 'HON', '1', 0, 'C', '1');

        foreach(range(0, $MaxScore) as $ar) {
            $pdf->Cell($ScoreCell, $CellHeight, $Hons[$ar]??'', '1', 0, 'C', '');
        }

        $pdf->ln();

        // Multiplicateurs
        $pdf->dy(2);
        $pdf->setFont('','', 8);
        $pdf->Cell($ScoreCell, $CellHeight, '', '1', 0, 'C', '');

        foreach(range(0, $MaxScore) as $ar) {
            $pdf->Cell($ScoreCell, $CellHeight, $ar ? 'x '.$ar : '', '1', 0, 'C', '');
        }

        $pdf->ln();

        // Points
        $pdf->dy(2);
        $pdf->setFont('','b', 9);
        $pdf->Cell($ScoreCell, $CellHeight, 'Pts.', '1', 0, 'C', '');

        foreach(range(0, $MaxScore) as $ar) {
            $pdf->Cell($ScoreCell, $CellHeight, $ar ? ($Hons[$ar]??'' ? $Hons[$ar]*$ar : '') : '', '1', 0, 'C', '');
        }

        $pdf->ln();

        // Barcode
        if($pdf->PrintBarcode and !empty($r->EnCode)) {
            $pdf->setCellPadding(0);
            $pdf->SetColors(true);
            $pdf->SetFont('barcode','',22);

            if($r->EnCode[0]=='_') {
                $r->EnCode='UU'.substr($r->EnCode, 1);
            }

            $pdf->Cell(0, $CellHeight+3, mb_convert_encoding('*' . $r->EnCode.'-'.$r->DivId.'-'.$r->ClId . '-1', "UTF-8","cp1252") . "*",0,0,'C',0);

            $pdf->SetFont($pdf->FontStd,'',7);
            $pdf->ln();

            $pdf->Cell(0, 3, mb_convert_encoding($r->EnCode.'-'.$r->DivId.'-'.$r->ClId . '-1', "UTF-8","cp1252"),0,0,'C',0, '', 1, false, 'T', 'T');

            $pdf->SetColors(false);
            $pdf->setCellPadding(2);
        }
    }

    return $pdf;
}
function FormatBeursaultSessionStart($Value) {
    $Value = trim((string)$Value);

    if($Value === '' || $Value === '0000-00-00 00:00:00' || $Value === '00:00:00') {
        return '';
    }

    $Timestamp = strtotime($Value);

    if($Timestamp !== false) {
        return date('H:i', $Timestamp);
    }
    return $Value;
}
