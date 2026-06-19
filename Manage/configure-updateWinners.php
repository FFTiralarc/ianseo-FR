<?php

require_once(dirname(__FILE__) . '/config.php');

$JSON=array('error'=>1, 'reload'=> 0, 'msg'=>'');

if(($_REQUEST['item']??'')!='ALLCLUBS' and (!isset($_REQUEST['pos']) or !isset($_REQUEST['cat']) or empty($_REQUEST['item']))) {
	JsonOut($JSON);
}

switch($_REQUEST['item']) {
    case 'GETCOMPETITIONS':
        $JSON['data']=[];
        $q=safe_r_SQL("select ToId, ToCode, ToName from Tournament where ToLocRule='{$_SESSION['TourLocRule']}' and ToId != {$_SESSION['TourId']} order by ToWhenTo desc");
        while($r=safe_fetch($q)) {
            $JSON['data'][]=[
                'val'=>$r->ToId,
                'txt'=>$r->ToCode . ' - ' . $r->ToName,
            ];
        }
        $JSON['error']=0;
        break;
    case 'SETCOMPETITIONDISTANCE':
        $Distance=intval($_REQUEST['day']);
        $Comp=intval($_REQUEST['comp']);
        $Bonus=getModuleParameter('FFTA', 'D1Bonus');
        // load country IDs
        $CountryCodes=[];
        $q=safe_r_sql("select CoId, CoCode 
            from Countries
            where CoTournament={$_SESSION['TourId']}");
        while($r=safe_fetch($q)) {
            $CountryCodes[$r->CoCode]=$r->CoId;
        }
        // import the related entries of the competition
        $SQL="select CoId2, EcCode, Entries.*
            from Entries
            inner join Countries on CoTournament=EnTournament and CoId=EnCountry
            inner join (select distinct CoId as CoId2, CoCode as CoCode2
                from Countries
                where CoTournament={$_SESSION['TourId']}
            ) comp on CoCode2=CoCode
            inner join EventClass on EcTournament={$_SESSION['TourId']} and EcTeamEvent=1 and EcClass=EnClass and EcDivision=EnDivision
            left join (
                select EnCode as EnCode2, EnDivision as EnDiv2
                from Entries
                where EnTournament={$_SESSION['TourId']}
            ) En2 on EnCode2=EnCode and EnDiv2=EnDivision
            where EnTournament=$Comp and EnTeamFEvent=1 and EnCode2 is null
            order by EnFirstName, EnName";
        $q=safe_r_SQL($SQL);
        $Values=[];
        while($r=safe_fetch($q)) {
            safe_w_sql("insert into Entries set
                EnTournament={$_SESSION['TourId']}, EnDivision='{$r->EnDivision}', EnClass='{$r->EnClass}', EnSubClass='{$r->EnSubClass}',
                EnAgeClass='{$r->EnAgeClass}', EnCountry='{$r->CoId2}', EnIocCode='{$r->EnIocCode}',
                EnCtrlCode='{$r->EnCtrlCode}', EnDob='{$r->EnDob}', EnCode='{$r->EnCode}', EnName=".StrSafe_DB($r->EnName).", EnFirstName=".StrSafe_DB($r->EnFirstName).",
                EnAthlete='{$r->EnAthlete}', EnSex='{$r->EnSex}', EnClassified='{$r->EnClassified}', EnWChair='{$r->EnWChair}', EnSitting='{$r->EnSitting}',
                EnIndClEvent='{$r->EnIndClEvent}', EnTeamClEvent='{$r->EnTeamClEvent}', EnIndFEvent='{$r->EnIndFEvent}', EnTeamFEvent='{$r->EnTeamFEvent}', EnTeamMixEvent='{$r->EnTeamMixEvent}',
                EnDoubleSpace='{$r->EnDoubleSpace}', EnPays='{$r->EnPays}', EnStatus='{$r->EnStatus}', EnTargetFace='{$r->EnTargetFace}', EnLueTimeStamp='{$r->EnLueTimeStamp}',
                EnLueFieldChanged='{$r->EnLueFieldChanged}', EnTimestamp='{$r->EnTimestamp}', EnMainInfoUpdate=0, EnNameOrder='{$r->EnNameOrder}',
                EnOdfShortname=".StrSafe_DB($r->EnOdfShortname).", EnTvGivenName=".StrSafe_DB($r->EnTvGivenName).", EnTvFamilyName=".StrSafe_DB($r->EnTvFamilyName).", EnTvInitials='{$r->EnTvInitials}'");
        }

        // updates the teamfincomponent
        $SQL="select EnCountry, EnId, EcCode 
            from Entries
            inner join EventClass on EcTournament=EnTournament and EcTeamEvent=1 and EcClass=EnClass and EcDivision=EnDivision
            where EnTournament={$_SESSION['TourId']}";
        $OldCode='';
        $q=safe_r_SQL($SQL);
        while($r=safe_fetch($q)) {
            if($OldCode!="{$r->EnCountry}-{$r->EnId}") {
                $OldCode="{$r->EnCountry}-{$r->EnId}";
                $Order=1 + 10*($Distance-1);
            }
            safe_w_sql("insert ignore into TeamFinComponent set TfcCoId=$r->EnCountry, TfcSubTeam=0, TfcTournament={$_SESSION['TourId']}, TfcEvent='$r->EcCode', TfcId=$r->EnId, TfcOrder=$Order");
            $Order++;
        }

        // enter the team ranking
        safe_w_SQL("delete from TeamQualificationDistances where TqdDistance=$Distance and TqdTournament={$_SESSION['TourId']}");
        $RankedItems=[];
        $RankedEvents=[];
        // get the list of participants and eventual ranked teams
        $q=safe_r_sql("select * from RoundRobinParticipants where RrPartTournament={$_SESSION['TourId']} and RrPartEvent in ('FCL','HCL','HCO','FCO') and RrPartTeam=1 and RrPartLevel=1");
        while($r=safe_fetch($q)) {
            $RankedItems[$r->RrPartEvent][$r->RrPartParticipant]=$r->RrPartGroupRank;
        }
        $SQL="select Teams.*, c2.CoId
            from Teams
            inner join Countries c1 on TeCoId=c1.CoId and TeTournament=c1.CoTournament
            inner join Countries c2 on c2.CoCode=c1.CoCode and c2.CoTournament={$_SESSION['TourId']}
            inner join Events on EvTournament=TeTournament and EvCode=TeEvent and EvWinnerFinalRank=1
            where TeTournament=$Comp
            order by TeEvent, TeRank";
        $q=safe_r_SQL($SQL);
        while($r=safe_fetch($q)) {
            $BonusToTake=$Bonus['16'];
            switch($r->TeEvent) {
                case 'DRRH':
                case 'D1HCL':
                case 'HCL':
                    $r->TeEvent='HCL';
                    if(!isset($RankedItems[$r->TeEvent][$r->CoId])) {
                        continue 2;
                    }
                    if($Distance==3) {
                        $BonusToTake=$Bonus['8'];
                        if($RankedItems[$r->TeEvent][$r->CoId]<=8) {
                            $r->TeEvent='HCLof';
                        } else {
                            $r->TeEvent='HCLdn';
                        }
                    }
                    break;
                case 'DRRF':
                case 'D1FCL':
                case 'FCL':
                    $r->TeEvent='FCL';
                    if(!isset($RankedItems[$r->TeEvent][$r->CoId])) {
                        continue 2;
                    }
                    if($Distance==3) {
                        $BonusToTake=$Bonus['8'];
                        if($RankedItems[$r->TeEvent][$r->CoId]<=8) {
                            $r->TeEvent='FCLof';
                        } else {
                            $r->TeEvent='FCLdn';
                        }
                    }
                    break;
                case 'DRCH':
                case 'HCO':
                case 'D1HCO':
                    $r->TeEvent='HCO';
                    if(!isset($RankedItems[$r->TeEvent][$r->CoId])) {
                        continue 2;
                    }
                    if($Distance==3) {
                        $BonusToTake=$Bonus['8'];
                        if($RankedItems[$r->TeEvent][$r->CoId]<=8) {
                            $r->TeEvent='HCOof';
                        } else {
                            $r->TeEvent='HCOdn';
                        }
                    }
                    break;
                case 'DRCF':
                case 'FCO':
                case 'D1FCO':
                    $r->TeEvent='FCO';
                    if(!isset($RankedItems[$r->TeEvent][$r->CoId])) {
                        continue 2;
                    }
                    $BonusToTake=$Bonus['8'];
                    break;
                default:
                    continue 2;
            }
            $RankedEvents[$r->TeEvent]=($RankedEvents[$r->TeEvent]??0)+1;

            $Bon=($BonusToTake[$RankedEvents[$r->TeEvent]]??0);
            $sql="TqdCoId={$r->CoId}, TqdSubTeam={$r->TeSubTeam}, TqdEvent='{$r->TeEvent}', TqdTournament={$_SESSION['TourId']},
                TqdDistance={$Distance}, TqdScore={$r->TeScore}, TqdBonus=$Bon,
                TqdTie1={$r->TeGold}, TqdTie2={$r->TeXnine}, TqdTie3={$r->TeTieBreaker3}, TqdRank={$RankedEvents[$r->TeEvent]}, TqdTimeStamp='{$r->TeTimeStamp}'";
            safe_w_sql("insert into TeamQualificationDistances set $sql on duplicate key update $sql");
        }

        // reassign the rank
        require_once('Common/Lib/Obj_RankFactory.php');
        $options=[
            'tournament' => $_SESSION['TourId'],
            'team' => 1,
            'level' => 1,
        ];
        $q=safe_r_SQL("select EvCode from Events where EvTournament={$_SESSION['TourId']} and EvTeamEvent=1");
        while($r=safe_fetch($q)) {
            $options['event'] = $r->EvCode;
            Obj_RankFactory::create('Robin', $options)->calculate();
        }

        $JSON['error']=0;
        break;
	case 'ALLONE':
		setModuleParameter('FFTA', 'D1AllInOne', intval($_REQUEST['club']));
		if($_REQUEST['club']) {
			// all matches are made only with teams, so no bonus and no individual, FCO=6 teams...
			setModuleParameter('FFTA', 'DefaultMatchIndividual', 0);
			$Winners=getModuleParameter('FFTA', 'D1Winners');
			$ClubsToRemove=array();
			// removes from the table
			if(!empty($Winners['FCO']['7'])) {
				$ClubsToRemove[]=$Winners['FCO']['7'];
			}
			if(!empty($Winners['FCO']['8'])) {
				$ClubsToRemove[]=$Winners['FCO']['8'];
			}
			$Modules=array();
			foreach(array('DefaultMatchIndividual', 'DefaultMatchTeam', 'D1Bonus', 'D1Winners', 'D1AllInOne') as $type) {
				$tmp=getModuleParameter('FFTA', $type);
				// reset bonus...
				if($type=='D1Bonus') {
					foreach($tmp as $k1 => &$v1) {
						foreach($v1 as $k2 => &$v2) {
							$v2=0;
						}
					}
				}
				if(isset($tmp['FCO']) and is_array($tmp['FCO'])) {
					$tmp['FCO']=array_slice($tmp['FCO'],0,6, true);
				}
				$Modules[$type]=$tmp;
			}

			require_once('Qualification/Fun_Qualification.local.inc.php');
			if($Connected=getModuleParameter('FFTA', 'ConnectedCompetitions')) {
				foreach($Connected as $tmp) {
					if($CompId=getIdFromCode($tmp)) {
						// reset to 6 the numbers of FCO
						safe_w_sql("update Events set EvNumQualified=6 where EvTournament=$CompId and EvTeamEvent=1 and EvCode='FCO'");
						// removes all the individual events
						safe_w_sql("delete from Finals where FinTournament=$CompId");
						safe_w_sql("delete from Events where EvTeamEvent=0 and EvTournament=$CompId");
						safe_w_sql("delete from FinSchedule where FsTeamEvent=0 and FsTournament=$CompId");
						safe_w_sql("update Qualifications inner join Entries on EnId=QuId and EnTournament=$CompId set QuHits=1");
						if($ClubsToRemove) {
							safe_w_sql("delete from TeamDavis where TeDaTournament=$CompId and TeDaEvent='FCO' and TeDaTeam in ('".implode("','", $ClubsToRemove)."')");
						}
						foreach(array('DefaultMatchIndividual', 'DefaultMatchTeam', 'D1Bonus', 'D1Winners', 'D1AllInOne') as $type) {
							setModuleParameter('FFTA', $type, $Modules[$type], $CompId);
						}
						// recreates the teams
						MakeTeams(NULL, NULL, $CompId);
						MakeTeamsAbs(null,null,null, $CompId);
					}
				}
			} else {
				// reset to 6 the numbers of FCO
				safe_w_sql("update Events set EvNumQualified=6 where EvTournament={$_SESSION['TourId']} and EvTeamEvent=1 and EvCode='FCO'");
				// removes all the individual events
				safe_w_sql("delete from Finals where FinTournament={$_SESSION['TourId']}");
				safe_w_sql("delete from Events where EvTeamEvent=0 and EvTournament={$_SESSION['TourId']}");
				safe_w_sql("delete from FinSchedule where FsTeamEvent=0 and FsTournament={$_SESSION['TourId']}");
				if($ClubsToRemove) {
					safe_w_sql("delete from TeamDavis where TeDaTournament={$_SESSION['TourId']} and TeDaEvent='FCO' and TeDaTeam in ('".implode("','", $ClubsToRemove)."')");
				}
				// recreates the teams
				MakeTeams();
				MakeTeamsAbs();
			}
			// sets all qual arrows to be shot

			set_qual_session_flags();
		}
		$JSON['reload']=1;
		$JSON['error']=0;
		break;
	case 'DEFIND':
		setModuleParameter('FFTA', 'DefaultMatchIndividual', intval($_REQUEST['club']));
		$JSON['error']=0;
		break;
	case 'DEFTEAM':
		setModuleParameter('FFTA', 'DefaultMatchTeam', intval($_REQUEST['club']));
		$JSON['error']=0;
		break;
	case 'BONUS':
		$Bonus=getModuleParameter('FFTA', 'D1Bonus');

		if(isset($Bonus[$_REQUEST['cat']][$_REQUEST['pos']])) {
			$Bonus[$_REQUEST['cat']][$_REQUEST['pos']]=intval($_REQUEST['club']);
			setModuleParameter('FFTA', 'D1Bonus', $Bonus);
			$JSON['error']=0;
		}
		break;
	case 'ALLCLUBS':
		$teams=preg_split('/[^0-9a-z]+/im', $_REQUEST['club']??'');
		$cat=($_REQUEST['cat']??'');
		if(!$cat or !$teams) {
			JsonOut($JSON);
		}
		$pos=1;
		foreach($teams as $team) {
			if(!$team) {
				continue;
			}

			if($JSON['msg']=assignFrTeam($team, $cat, $pos)) {
				JsonOut($JSON);
			}
			$JSON['ret'][]=['cat'=>$cat,'pos'=>$pos,'team'=>$team];
			$pos++;
		}
		$JSON['error']=0;
		break;
	case 'CLUB':
		if($JSON['msg']=assignFrTeam($_REQUEST['club'], $_REQUEST['cat'], intval($_REQUEST['pos']??0))) {
			JsonOut($JSON);
		}
		$JSON['error']=0;
		break;
	case 'CONNECTED':
		$Comps=array();
		foreach(preg_split('/[ ,;+]+/', $_REQUEST['club']) as $c) {
			if($c=trim($c)) {
				$Comps[]=$c;
			}
		}

		setModuleParameter('FFTA', 'ConnectedCompetitions', $Comps);
		$JSON['error']=0;

		// check if we have some more defaults already set!
		if($Comps) {
			// check if this parameter is already present in the first competition set
			$CompId=getIdFromCode($Comps[0]);
			foreach(array('DefaultMatchIndividual', 'DefaultMatchTeam', 'D1Bonus', 'D1Winners', 'D1AllInOne') as $type) {
				if($valOld=getModuleParameter('FFTA', $type, '', $CompId)) {
					setModuleParameter('FFTA', $type, $valOld);
					$JSON['reload']=1;
				}
			}
		}

		break;
	case 'TOURDATE':
		$pos=($_REQUEST['pos']??'');
		$cat=($_REQUEST['cat']??'');
		$val=($_REQUEST['club']??'');
		if(!$pos or !$cat) {
			JsonOut($JSON);
		}
		switch($cat) {
			case 'date':
				require_once('Common/Lib/Fun_DateTime.inc.php');
				$val=CleanDate($val);
				break;
			case 'time':
				if($val) {
					$i=explode(':',$val);
					if(count($i)<2) {
						$val='';
					} else {
						$val=sprintf("%02d:%02d", intval($i[0]), intval($i[1]));
					}
				}
				break;
		}
		$TourDates=getModuleParameter('FFTA', 'D1TourDates', ['D1' => [], 'D2' => [], 'D3' => []]);
		$TourDates['D'.$pos][$cat]=$val;
		setModuleParameter('FFTA', 'D1TourDates', $TourDates);
		// update the dates of the matches
		if(($TourDates['D'.$pos]['date']??'') and ($TourDates['D'.$pos]['time']??'')) {
			$StartingDatetime=$TourDates['D'.$pos]['date'].' '.$TourDates['D'.$pos]['time'];
			$Duration=getModuleParameter('FFTA', 'DefaultMatchTeam', 30);
			switch($pos) {
				case '1': // rounds 1-7 for everybody
					for($i=1;$i<=7;$i++) {
						$items=explode(' ', $StartingDatetime);
						safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and RrMatchTeam=1 and RrMatchLevel=1 and RrMatchGroup=1 and RrMatchRound=$i");
						$StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
					}
					break;
				case '2': // rounds 8-15 for FCL, HCL, HCO; rounds 1-7 of level 2 for FCO
					for($i=8;$i<=15;$i++) {
						$items=explode(' ', $StartingDatetime);
						safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and RrMatchEvent!='FCO' and RrMatchTeam=1 and RrMatchLevel=1 and RrMatchGroup=1 and RrMatchRound=$i");
						$StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
					}
					$StartingDatetime=$TourDates['D'.$pos]['date'].' '.$TourDates['D'.$pos]['time'];
					for($i=1;$i<=7;$i++) {
						$items=explode(' ', $StartingDatetime);
						safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and RrMatchEvent='FCO' and RrMatchTeam=1 and RrMatchLevel=2 and RrMatchGroup=1 and RrMatchRound=$i");
						$StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
					}
					break;
				case '3':
                    if($_SESSION['TourLocSubRule']=='SetFRD12026') {
                        // rounds 1-7 for FCLof/dn, HCLof/dn, HCOof/dn;
                        for($i=1;$i<=7;$i++) {
                            $items=explode(' ', $StartingDatetime);
                            safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and length(RrMatchEvent)>3 and RrMatchTeam=1 and RrMatchLevel=1 and RrMatchGroup=1 and RrMatchRound=$i");
                            $StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
                        }

                        // rounds 1-7 of level 3 for FCO
                        $StartingDatetime=$TourDates['D'.$pos]['date'].' '.$TourDates['D'.$pos]['time'];
                        for($i=1;$i<=7;$i++) {
                            $items=explode(' ', $StartingDatetime);
                            safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and RrMatchEvent='FCO' and RrMatchTeam=1 and RrMatchLevel=3 and RrMatchGroup=1 and RrMatchRound=$i");
                            $StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
                        }
                    } else {
                        // rounds 1-15 for FCL, HCL, HCO; rounds 1-7 of level 3 and 4 for FCO
                        for($i=1;$i<=12;$i++) {
                            $items=explode(' ', $StartingDatetime);
                            safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and RrMatchEvent!='FCO' and RrMatchTeam=1 and RrMatchLevel=2 and RrMatchGroup=1 and RrMatchRound=$i");
                            $StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
                        }
                        $StartingDatetime=$TourDates['D'.$pos]['date'].' '.$TourDates['D'.$pos]['time'];
                        for($i=1;$i<=7;$i++) {
                            $items=explode(' ', $StartingDatetime);
                            safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and RrMatchEvent='FCO' and RrMatchTeam=1 and RrMatchLevel=3 and RrMatchGroup=1 and RrMatchRound=$i");
                            $StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
                        }
                        for($i=1;$i<=5;$i++) {
                            $items=explode(' ', $StartingDatetime);
                            safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and RrMatchEvent='FCO' and RrMatchTeam=1 and RrMatchLevel=4 and RrMatchGroup=1 and RrMatchRound=$i");
                            $StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
                        }
                        $StartingDatetime=date('Y-m-d H:i:s', strtotime($TourDates['D'.$pos]['date'].' '.$TourDates['D'.$pos]['time']." +23 hours +30 minutes"));
                        for($i=13;$i<=15;$i++) {
                            $items=explode(' ', $StartingDatetime);
                            safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and RrMatchEvent!='FCO' and RrMatchTeam=1 and RrMatchLevel=2 and RrMatchGroup=1 and RrMatchRound=$i");
                            $StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
                        }
                        $StartingDatetime=date('Y-m-d H:i:s', strtotime($TourDates['D'.$pos]['date'].' '.$TourDates['D'.$pos]['time']." +23 hours +30 minutes"));
                        for($i=6;$i<=7;$i++) {
                            $items=explode(' ', $StartingDatetime);
                            safe_w_sql("update RoundRobinMatches set RrMatchScheduledDate='{$items[0]}', RrMatchScheduledTime='$items[1]', RrMatchScheduledLength=$Duration
                         	where RrMatchTournament={$_SESSION['TourId']} and RrMatchEvent='FCO' and RrMatchTeam=1 and RrMatchLevel=4 and RrMatchGroup=1 and RrMatchRound=$i");
                            $StartingDatetime=date('Y-m-d H:i:s', strtotime($StartingDatetime." +{$Duration} minutes"));
                        }
                    }
					break;
			}
		}
		$JSON['error']=0;
		break;
}

JsonOut($JSON);

function assignFrTeam($team, $cat, $pos) {
	$Winners=getModuleParameter('FFTA', 'D1Winners');
	if(isset($Winners[$cat]["$pos"])) {
		$Winners[$cat]["$pos"]=$team;
		setModuleParameter('FFTA', 'D1Winners', $Winners);

		$q=safe_r_sql("select distinct CoId, LueCountry, LueCoDescr, LueCoShort from LookUpEntries left join Countries on CoCode=LueCountry and CoTournament={$_SESSION['TourId']} where LueIocCode like 'FRA%' and LueCountry=".StrSafe_DB($team));
		if($r=safe_fetch($q)) {
			if(!$r->CoId) {
				safe_w_sql("insert into Countries set CoCode=".StrSafe_DB($r->LueCountry).", CoName=".StrSafe_DB($r->LueCoShort).", CoNameComplete=".StrSafe_DB($r->LueCoDescr).", CoTournament={$_SESSION['TourId']}");
				$r->CoId=safe_w_last_id();
			}
			safe_w_sql("delete from Teams 
       				where TeTournament={$_SESSION['TourId']}
					and TeFinEvent=1
					and TeRank=$pos
					and TeEvent=".StrSafe_DB($cat));
			safe_w_sql("insert into Teams set
					TeCoId=$r->CoId,
					TeTournament={$_SESSION['TourId']},
					TeFinEvent=1,
					TeHits=1,
					TeRank=$pos,
					TeEvent=".StrSafe_DB($cat)."
					on duplicate key update TeCoId=$r->CoId,
					TeTournament={$_SESSION['TourId']},
					TeFinEvent=1,
					TeHits=1,
					TeRank=$pos,
					TeEvent=".StrSafe_DB($cat)."");

			if($_SESSION['TourLocSubRule']=='SetFRD12023' or $_SESSION['TourLocSubRule']=='SetFRD12026') {
				// insert this club in level 1!
				safe_w_sql("update RoundRobinParticipants set RrPartParticipant=$r->CoId 
                		where RrPartTournament={$_SESSION['TourId']} 
                			and RrPartTeam=1 
                		  	and RrPartEvent=".StrSafe_DB($cat)."
                		  	and RrPartLevel=1
                		  	and RrPartGroup=1
                		  	and RrPartSourceRank=$pos");
				// insert into the matches of level 1!
				safe_w_sql("Update RoundRobinMatches
						inner join RoundRobinGrids on RrGridTournament=RrMatchTournament
							and RrGridTeam=RrMatchTeam
							and RrGridEvent=RrMatchEvent
							and RrGridLevel=RrMatchLevel
							and RrGridGroup=RrMatchGroup
							and RrGridRound=RrMatchRound
							and RrGridMatchno=RrMatchMatchno
							and RrGridItem=$pos
						set RrMatchAthlete=$r->CoId, RrMatchSubTeam=0
						where RrMatchTournament={$_SESSION['TourId']} 
							and RrMatchTeam=1 
							and RrMatchEvent=".StrSafe_DB($cat)."
						    and RrMatchLevel=1
							and RrMatchGroup=1
							 ");
			}

			// check if all teams are there
			$q=safe_r_sql("select * from RoundRobinParticipants 
                    where RrPartTournament={$_SESSION['TourId']} 
                        and RrPartParticipant=0
                        and RrPartTeam=1 
                        and RrPartEvent=".StrSafe_DB($cat)."
                        and RrPartLevel=1
                        and RrPartGroup=1");
			if(safe_num_rows($q)==0) {
				safe_w_sql("update Events set EvE1ShootOff=1 where EvCode=".StrSafe_DB($cat)." and EvTournament={$_SESSION['TourId']} and EvTeamEvent=1");
				set_qual_session_flags();
			}
			return 0;
		}
	}
	return get_text('ClubNotFound', 'Errors');
}