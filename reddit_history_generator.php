<?php

declare(ticks = 1);

// signal handler function
function handle_interrupt($signo)
{
     switch ($signo) {
         case SIGTERM:
             show_last_date();
             exit;
             break;
         case SIGINT:
             show_last_date();
             exit;
             break;
         case SIGHUP:
             show_last_date();
             exit;
             break;
         case SIGUSR1:
             show_last_date();
             break;
         default:
             show_last_date();
             exit;
     }

}

pcntl_signal(SIGTERM,   "handle_interrupt");
pcntl_signal(SIGHUP,    "handle_interrupt");
pcntl_signal(SIGUSR1,   "handle_interrupt");
pcntl_signal(SIGINT,    "handle_interrupt");

function show_last_date() {
    global $last_date, $last_time, $last_url;
    echo "\n--------------------------------------";
    echo "\n--------------------------------------";
    echo "\n LAST DATE | " . $last_date;
    echo "\n LAST TIME | " . $last_time;
    echo "\n LAST URL  | " . $last_url;
    echo "\n--------------------------------------";
    echo "\n--------------------------------------";
}
register_shutdown_function('show_last_date');



echo "\n";

$period = 86400 * 4;

$startdate  = "2015-03-01 00:00:00";
$enddate    = "2016-09-04 23:59:59";

$GLOBALS['last_date']   = '0000-00-00 00:00:00';
$GLOBALS['last_time']   = '0';
$GLOBALS['last_url']    = '';


$subRedditURL0      = 'http://www.reddit.com/r/Bitcoin/search?sort=top&q=timestamp%3A';
$subRedditURL1      = '&restrict_sr=on&syntax=cloudsearch'; 
$subRedditURLbrk    = '..';

$startTimeStamp = strtotime($startdate);
$endTimeStamp   = strtotime($enddate);

$currDay = 0;
do {
    
    $selectedTimestamp = $startTimeStamp + ($currDay * $period);
    $selectedYear = date('Y', $selectedTimestamp);  $selectedMonth = date('m', $selectedTimestamp);     $selectedDay = date('d', $selectedTimestamp);
    $selectedHour = date('G', $selectedTimestamp);  $selectedMinute = date('i', $selectedTimestamp);    $selectedSecond = date('s', $selectedTimestamp);
    
    $dateFrom       = "$selectedYear-$selectedMonth-$selectedDay 00:00:00";
    $timestampFrom  = strtotime($dateFrom);
    
    $timestampTo    = $timestampFrom + $period;
    
    $finalRedditUrl = $subRedditURL0 . "$timestampFrom$subRedditURLbrk$timestampTo$subRedditURL1";

    
    $GLOBALS['last_date']   = $dateFrom;
    $GLOBALS['last_time']   = $timestampFrom;
    $GLOBALS['last_url']    = $finalRedditUrl;
    
    echo "$finalRedditUrl\n";
    $handle = fopen("php://stdin", "r");
    $char = fgetc($handle);
    
    //var_dump(ord($char{0}));
    
    passthru('/usr/bin/firefox "'. $finalRedditUrl.'"');
    
    $currDay++;
} while($timestampFrom < $endTimeStamp);

//http://www.reddit.com/r/Silverbugs/search?sort=top&q=timestamp%3A1304208000..1304294400&restrict_sr=on&syntax=cloudsearch
// http://www.reddit.com/r/Silverbugs/search?sort=top&q=timestamp%3A1304208000..1304294400&restrict_sr=on&syntax=cloudsearch