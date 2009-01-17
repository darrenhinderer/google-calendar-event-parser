REQUIREMENTS
PHP 5 (untested on other versions) 
Google Calendar Account

HOW IT WORKS
Google Calendar provides an Atom RSS feed that is not entended for displaying events in order, but in the order that they are published. gcalParse.php reads in this feed, and outputs a much cleaner format that has a tagged order based on the event times. The gcal.js file reads this output and inserts it into webpage complete with any style information it needs.  

INSTALLATION
The gcalParse.php is the main file and  most of the configuation needs to be
done here.

The feed url can be found inside of your Google Calendar account:
* Go to Settings->Calendars
* Click on the calendar to be used
* Click the XML button shown next to Calendar Address.
* Copy this address into the php file and replace the last part of it "basic" with "full"

The time zone should be changed to the correct one that matches what the Google Calendar is to configured as.  Here are some examples of how to change the time zone in the gcalParse.php file: 

putenv("TZ=US/Eastern");
putenv ("TZ=Europe/Amsterdam");
putenv("TZ=UTC0");

Because of recurring events, the feed query to Google needs to be limited to a range of dates. This is set with the startRange and endRange variables in gcalParse.php.  

The HTML file provided is set up to use the calendar, but most likely you already have a file that you want to add the calendar to; You will need the following lines added to it: 

Inbetween <head> tags
<script language="javaScript" type="text/javascript" src="gcal.js"></script>

As part of the of the body tag
<body onload="loadCalendar();">

And inbetween <body> tags
<div id='insertCalendar'></div>

The gcal.js file inserts elements with css classes and id's that should be altered to use the styles available on your website. Or optionally you can leave gcal.js alone and set your styles in gcal.css.

gcal.js, gcalParse.php, and Event.php are all meant to sit together in one directory. 

GOOGLE CALENDAR USE

Google calendar use is the easiest part of the setup. Entries can be made as
normal, but only weekly reoccuring events are supported. Announcements events
that do not necessarily need a date should be marked at the beginning of the
description with an @ symbol. These announcements will float to the bottom of
the event list and be marked Announcemnt where the date/time information
normally is. Locations that are actual addresses and not labels like "Home" are recognized and linked to Google Maps.

KNOWN ISSUES
Copying and pasting in the Microsoft "right quote" into Google Calendar will result in the use of the back tick (`) instead. 

The work-around for this is to retype each right quote after copying and pasting into the calendar, or to simply hand type the whole thing.
