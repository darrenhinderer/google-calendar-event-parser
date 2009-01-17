<?
include 'Event.php';

//Configurable Options
putenv("TZ=US/Mountain");
$calendarURL = 'http://www.google.com/calendar/feeds/emsq0p64drvnsfqgisie76719s@group.calendar.google.com/public/full';
$startRange = "yesterday";
$endRange = "next month";
//End Configurable Options

$rangeStartSecs = strtotime($startRange);
$searchTime = strftime("%G-%m-%dT%H:%M:%S", $rangeStartSecs);
$searchTime = '?start-min=' . $searchTime;
$rangeEndSecs = strtotime($endRange);
$searchTime = $searchTime . '&start-max='; 
$searchTime = $searchTime . strftime("%G-%m-%dT%H:%M:%S", $rangeEndSecs);

$data = file_get_contents($calendarURL . $searchTime); 

//Microsoft right quote and ampersands both cause problems in the xml
$data = str_replace("\xe2\x80\x99","`",$data);
$data = str_replace('&amp;','and', $data);

$readTitle = false;
$readDescription = false;
$tmpEvent = NULL;
$events = Array();
$announcements = Array();

$xmlParser = xml_parser_create();
xml_set_element_handler($xmlParser, "tagOpen", "tagClose");
xml_set_character_data_handler($xmlParser, "tagData");   
xml_parse($xmlParser, $data); 

$eventsOrder = Array();
foreach ($events as $event)
{
  if ($event->recurs)
  {
    sort($event->recurArray);
    $event->startTime = $event->recurArray[0];
  }

  array_push($eventsOrder,$event->startTime);
}
asort($eventsOrder);

header("Content-type: text/xml");
echo "<calendar>";

foreach ($eventsOrder as $order => $val)
{
  $events[$order]->toString();
}

foreach ($announcements as $announcement)
{
  $announcement->toString();
}

echo '</calendar>';

function tagOpen($parser, $tag, $attribs)
{
  global $rangeStartSecs;
  global $tmpEvent;
  global $readTitle;
  global $readDescription;

  if ($tag == 'ENTRY')
  {
    $tmpEvent = new Event();
  }
  else if ($tag == 'TITLE')
  {
    $readTitle = true;
  }
  else if ($tag == 'CONTENT')
  {
    $readDescription = true;
  }
  else if ($tag == 'GD:WHEN')
  {
    if ($tmpEvent->recurs)
    {
      if (strtotime($attribs['STARTTIME']) > $rangeStartSecs)
      {
        array_push($tmpEvent->recurArray,strtotime($attribs['STARTTIME']));
        $tmpEvent->endTime = strtotime($attribs['ENDTIME']);
      }
    }
    else
    {
      $tmpEvent->startTime = strtotime($attribs['STARTTIME']);
      $tmpEvent->endTime = strtotime($attribs['ENDTIME']);
    }
  }
  else if ($tag == 'GD:WHERE')
  {
    $tmpEvent->where = $attribs['VALUESTRING'];
  }
  else if ($tag == 'GD:RECURRENCE')
  {
    $tmpEvent->recurs = true;
  }
}

function tagData($parser, $data)
{
  global $tmpEvent;
  global $readTitle;
  global $readDescription;

  if ($readDescription == true)
  {
    if ($data[0] == "@")
    {
      $tmpEvent->announcement = true; 
      $tmpEvent->description = substr($data,1-strlen($data));
    }
    else
    {
      $tmpEvent->description = $data;
    }
  }

  if ($readTitle == true)
  {
    $tmpEvent->title = $data;
    $readTitle = false;
  }
}

function tagClose($parser, $tag)
{
  global $tmpEvent;
  global $events;
  global $announcements;
  global $readDescription;

  if ($tag == 'ENTRY')
  {
    if ($tmpEvent->announcement)
      array_push($announcements,$tmpEvent);
    else
      array_push($events,$tmpEvent);
  }
  if ($tag == 'CONTENT')
  {
    $readDescription = false;
  }
}
?>
