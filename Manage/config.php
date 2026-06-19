<?php

require_once(dirname(__FILE__, 4) . '/config.php');

CheckTourSession(true);
checkFullACL(AclCompetition, 'cData', AclReadWrite);

define('Series', 5);
