<?php
/**
 * Referral System
 * https://webenginecms.org/
 * 
 * @version 1.0.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w641ba01901cb0925ec50f543e59acbd
 */

// File Name
$file_name = basename(__FILE__);

// Run Cron
$ReferralSystem = new \Plugin\ReferralSystem\ReferralSystem();
$ReferralSystem->checkUnverifiedReferrals();
$ReferralSystem->checkReferrals();
$ReferralSystem->checkPendingRewardReferrals();

// UPDATE CRON
updateCronLastRun($file_name);