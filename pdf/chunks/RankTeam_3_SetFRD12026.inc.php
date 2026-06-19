<?php

require_once('Common/Lib/Fun_Phases.inc.php');
$pdf->setDocUpdate($PdfData->rankData['meta']['lastUpdate']);

// se ho degli eventi
$FirstPage=true;
foreach($PdfData->rankData['sections'] as $Event => $section) {
	// if this event has children layout differs
	if(!empty($section['meta']['hasChildren'])) {
		if($section['meta']['parent']=='') {
			$NumPhases=$section['meta']['firstPhase'] ? ceil(log($section['meta']['firstPhase'], 2))+1 : 1;
			$NeedTitle=true;

			// Se Esistono righe caricate....
			if(count($section['items'])) {
				if(!$FirstPage) $pdf->AddPage();
				$FirstPage=false;

                // if $Event==FCO there are 3 "manches" to show, otherwise 2 or 1
                $firstItem=current($section['items']??[]);
                $HasFinals=($firstItem and ($firstItem['finals']??''));

                $Blocks=1 + (strlen($Event)==3) + ($Event=='FCO');
                $wB=6;
                $wR=6;
                $wQ=11;
                $wTQ=11;
                $wTS=9;
                $wTP=9;
                $wRank=6;
                $wMatchRank=0;
                $wMatchPoints=0;
                $wT=$pdf->getPageWidth() - 20 - $wRank - ($wTP+$wTS)*2 - $wTQ - ($wB+$wR+$wQ)*$Blocks;
                if($HasFinals) {
                    $wMatchRank=6;
                    $wMatchPoints=6;
                    $wT -= ($wMatchRank+2*$wMatchPoints);
                }
				foreach($section['items'] as $item) {
					$NumComponenti = 1;
					if(!$pdf->SamePage(4 )) $NeedTitle=true;

					//Valuto Se è necessario il titolo
					if($NeedTitle) {
						// testastampa
						if ($section['meta']['printHeader']) {
							$pdf->SetFont($pdf->FontStd,'B',10);
							$pdf->Cell(0, 7.5,  $section['meta']['printHeader'], 0, 1, 'R', 0);
						}
						// Titolo della tabella
						if($FromRobin??'') {
							$pdf->SetFont($pdf->FontStd,'B',15);
							$pdf->Cell(0, 7.5,  $section['meta']['descr'], 1, 1, 'C', 1);
							$pdf->dy(5);
						} else {
							$pdf->SetFont($pdf->FontStd,'B',10);
							$pdf->Cell(0, 7.5,  $section['meta']['descr'], 1, 1, 'C', 1);
						}

						$pdf->SetFont($pdf->FontStd,'B',7);
                        // print the stages
                        $pdf->setX(10+$wT+$wRank+($wTP+$wTS)*2 + $wTQ + $wMatchRank+2*$wMatchPoints);
                        switch($Blocks) {
                            case 1:
                                $pdf->cell($wB+$wR+$wQ, 5, $PdfData->rankData['meta']['stages']['D3']['comp'], 1, 0, 'C', 1);
                                break;
                            case 2:
                                $pdf->cell($wB+$wR+$wQ, 5, $PdfData->rankData['meta']['stages']['D1']['comp'], 1, 0, 'C', 1);
                                $pdf->cell($wB+$wR+$wQ, 5, $PdfData->rankData['meta']['stages']['D2']['comp'], 1, 0, 'C', 1);
                                break;
                            case 3:
                                $pdf->cell($wB+$wR+$wQ, 5, $PdfData->rankData['meta']['stages']['D1']['comp'], 1, 0, 'C', 1);
                                $pdf->cell($wB+$wR+$wQ, 5, $PdfData->rankData['meta']['stages']['D2']['comp'], 1, 0, 'C', 1);
                                $pdf->cell($wB+$wR+$wQ, 5, $PdfData->rankData['meta']['stages']['D3']['comp'], 1, 0, 'C', 1);
                                break;
                        }
                        $pdf->ln();

						// Header vero e proprio
						$pdf->Cell($wRank, 5, $section['meta']['fields']['rank'], 'LTB', 0, 'C', 1);
						$pdf->Cell($wT, 5, $section['meta']['fields']['countryName'], 'TB', 0, 'C', 1);
                        if($HasFinals) {
                            $pdf->Cell($wMatchPoints, 5, '1/2', 'TBL', 0, 'C', 1);
                            $pdf->Cell($wMatchPoints, 5, 'Fin', 'TBR', 0, 'C', 1);
                            $pdf->Cell($wMatchRank, 5, 'Cl.', 'TBL', 0, 'C', 1);
                        }
						$pdf->Cell($wTP, 5, 'Points', 'TB', 0, 'C', 1);
						$pdf->Cell($wTS, 5, 'Score', 'TBR', 0, 'C', 1);
						$pdf->Cell($wTP, 5, 'Pts Match', 'LTB', 0, 'C', 1);
						$pdf->Cell($wTS, 5, 'Score', 'TB', 0, 'C', 1);
						$pdf->Cell($wTQ, 5, 'Cumul Qual.', 'TBR', 0, 'C', 1);
                        foreach(range(1,$Blocks) as $b) {
                            $pdf->cell($wQ, 5, 'Qual.', 'LTB', 0, 'C', 1);
                            $pdf->cell($wR, 5, 'Place', 'TB', 0, 'C', 1);
                            $pdf->cell($wB, 5, 'Bonus', 'TBR', 0, 'C', 1);
                        }
                        $pdf->ln();
						$NeedTitle=false;
					}

                    $TotBonus=0;
                    $TotScore=0;
                    foreach($item['stages'] as $stage) {
                        $TotBonus+=$stage['bonus'];
                        $TotScore+=$stage['score'];
                    }
					$pdf->SetFont($pdf->FontStd,'B',8);
					$pdf->Cell($wRank, 4, ($item['rank'] ? $item['rank']+(substr($Event,-2)=='dn'?8:0) : ''), 'TBL', 0, 'C', 0);
					$pdf->SetFont($pdf->FontStd,'',8);
					$pdf->Cell($wT, 4, $item['countryCode']  . ' - ' . $item['countryName'] . ($item['subteam']<=1 ? '' : ' (' . $item['subteam'] .')'), 'TB', 0, 'L', 0);

                    if($HasFinals) {
                        if($item['finals']??'') {
                            $i=0;
                            foreach($item['finals'] as $final) {
					            $pdf->Cell($wMatchPoints, 4, $section['meta']['matchMode']?$final['setScore']:$final['score'], 'TB'.($i++?'R':'L'), 0, 'C', 0);
                            }
                        } else {
                            $pdf->Cell($wMatchPoints*2,4,'', 1, 0, 'C', 0);
                        }
					    $pdf->Cell($wMatchRank, 4, ($item['qualRank'] ? $item['qualRank'] : ''), 'TBL', 0, 'C', 0);
                    }

                    $pdf->SetFont($pdf->FontFix,'B',8);
                    $pdf->Cell($wTP, 4,  number_format($item['qualScore'],0,$PdfData->NumberDecimalSeparator,$PdfData->NumberThousandsSeparator), 'TB', 0, 'R', 0);
                    $pdf->SetFont($pdf->FontFix,'I',8);
					$pdf->Cell($wTS, 4,  number_format($item['qualGolds'],0,$PdfData->NumberDecimalSeparator,$PdfData->NumberThousandsSeparator), 'TBR', 0, 'R', 0);
                    $pdf->SetFont($pdf->FontFix,'B',8);
					$pdf->Cell($wTP, 4,  number_format($item['qualScore']-$TotBonus,0,$PdfData->NumberDecimalSeparator,$PdfData->NumberThousandsSeparator), 'TBL', 0, 'R', 0);
                    $pdf->SetFont($pdf->FontFix,'I',8);
					$pdf->Cell($wTS, 4,  number_format($item['qualGolds'],0,$PdfData->NumberDecimalSeparator,$PdfData->NumberThousandsSeparator), 'TB', 0, 'R', 0);
					$pdf->SetFont($pdf->FontStd,'',8);
					$pdf->Cell($wTQ, 4,  number_format($TotScore,0,$PdfData->NumberDecimalSeparator,$PdfData->NumberThousandsSeparator), 'TBR', 0, 'R', 0);
                    while(count($item['stages'])<$Blocks) {
                        $item['stages'][]='';
                    }
                    foreach($item['stages'] as $b=>$a) {
                        $pdf->cell($wQ, 4, $item['stages'][$b]['score']??'', 'LTB', 0, 'C');
                        $pdf->cell($wR, 4, $item['stages'][$b]['rank']??'', 'TB', 0, 'C');
                        $pdf->cell($wB, 4, $item['stages'][$b]['bonus']??'', 'TBR', 0, 'C');
                    }

					$pdf->ln();
				}

                // add the components for each team
                foreach($section['items'] as $item) {
                    $pdf->ln(3);
                    $pdf->Cell(15, 0, $item['countryCode'], '', 0, 'C', 0);
                    $pdf->Cell(35, 0, $item['countryName'] . ($item['subteam']<=1 ? '' : ' (' . $item['subteam'] .')'), '', 0, 'L', 0);
                    $content=[];
                    foreach($item['athletes'] as $a) {
                        $content[]=$a['athlete'];
                    }
                    $pdf->MultiCell(0,4,implode(", ", $content),'','L');
                }
			}

		}
	} else {

		$NumPhases=$section['meta']['firstPhase'] ? ceil(log($section['meta']['firstPhase'], 2))+1 : 1;
		$NeedTitle=true;

		// Se Esistono righe caricate....
		if(count($section['items'])) {
			if(!$FirstPage) $pdf->AddPage();
			$FirstPage=false;

			foreach($section['items'] as $item) {
				$NumComponenti = max(1, count($item['athletes']));
				if(!$pdf->SamePage(4 )) $NeedTitle=true;

				//Valuto Se è necessario il titolo
				if($NeedTitle) {
					// testastampa
					if ($section['meta']['printHeader']) {
				        $pdf->SetFont($pdf->FontStd,'B',10);
						$pdf->Cell(190, 7.5,  $section['meta']['printHeader'], 0, 1, 'R', 0);
					}
					// Titolo della tabella
				    $pdf->SetFont($pdf->FontStd,'B',10);
					$pdf->Cell(190, 7.5,  $section['meta']['descr'], 1, 1, 'C', 1);
					// Header vero e proprio
				    $pdf->SetFont($pdf->FontStd,'B',7);
					$pdf->Cell(10, 5, $section['meta']['fields']['rank'], 1, 0, 'C', 1);
					$pdf->Cell(55+(15*(7-$NumPhases)), 5, $section['meta']['fields']['countryName'], 1, 0, 'C', 1);
					$pdf->Cell(20, 5, $section['meta']['fields']['qualRank'], 1, 0, 'C', 1);
					foreach($section['meta']['fields']['finals'] as $k=>$v)
					{
						if(is_numeric($k) && $k!=1)
							$pdf->Cell(15, 5, $v, 1, 0, 'C', 1);
					}
					$pdf->Cell(0, 5,'',0,1,'C',0);
					$NeedTitle=false;
				}

				$pdf->SetFont($pdf->FontStd,'B',1);
				$pdf->Cell(190, 0.2,'',0,1,'C',0);
			    $pdf->SetFont($pdf->FontStd,'B',8);
				$pdf->Cell(10, 4, ($item['rank'] ? $item['rank'] : ''), 1, 0, 'C', 0);
			    $pdf->SetFont($pdf->FontStd,'',8);
				$pdf->Cell(10, 4,   $item['countryCode'], 'LTB', 0, 'C', 0);
				$pdf->Cell(25+(15*(5-$NumPhases)), 4, $item['countryName'] . ($item['subteam']<=1 ? '' : ' (' . $item['subteam'] .')'), 'TB', 0, 'L', 0);

				//Metto i nomi dei Componenti se li ho
				if(count($item['athletes'])) {
					$tmpX=$pdf->GetX();
					$tmpY=$pdf->GetY();
					$NameCount=0;
					foreach($item['athletes'] as $k =>$v)
					{
						$pdf->SetXY($tmpX, $tmpY+(4*$NameCount++));
						$pdf->Cell(50, 4, $v['athlete'], 1, 0, 'L', 0);
					}
					$pdf->SetXY($tmpX+50, $tmpY);
				} else {
					$pdf->Cell(50, 4, '', 'RTB', 0, 'L', 0);
				}

				$pdf->SetFont($pdf->FontFix,'',8);
				$pdf->Cell(20, 4,  number_format($item['qualScore'],0,$PdfData->NumberDecimalSeparator,$PdfData->NumberThousandsSeparator) . '-' . substr('00' . $item['qualRank'],-2,2), 1, 0, 'R', 0);
				//Risultati  delle varie fasi
				foreach($item['finals'] as $k=>$v)
				{
					if($v['tie']==2)
						$pdf->Cell(15, 4,  $PdfData->Bye, 1, 0, 'R', 0);
					else
					{
						$pdf->SetFont($pdf->FontFix,'',8);
						if($k==4 && $section['meta']['matchMode']!=0 && $item['rank']>=5)
						{
							$pdf->Cell(11, 4, '(' . $v['score'] . ')', 'LTB', 0, 'R', 0);
							$pdf->Cell(4, 4, $v['setScore'], 'RTB', 0, 'R', 0);
						}
						else
						{
							$pdf->SetFont($pdf->FontFix,'',7);
							$pdf->Cell(15 - (strlen($v['tiebreak'])>0 && $k<=1 ? 7 : 0), 4, ($section['meta']['matchMode']==0 ? $v['score'] : $v['setScore']) . ($k<=1 && $v['tie']==1 && strlen($v['tiebreak'])==0 ? '*' : ''), ($k<=1 && strlen($v['tiebreak'])>0 ? 'LTB' : 1), 0, 'R', 0);
							if(strlen($v['tiebreak'])>0 && $k<=1)
							{
								$tmpTxt="";
								$tmpArr=explode("|",$v['tiebreak']);
								for($countArr=0; $countArr<count($tmpArr); $countArr+=$NumComponenti)
									$tmpTxt .= array_sum(array_slice($tmpArr,$countArr,$NumComponenti)). ",";
								$pdf->Cell(7, 4,  "T.".substr($tmpTxt,0,-1), 'RTB', 0, 'R', 0);
							}
						}
					}
				}
				$pdf->Cell(0.1, 4,'',0,1,'C',0);
			}
		}
	}
}


?>