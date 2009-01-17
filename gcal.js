var xmlhttp;

function loadCalendar()
{
  if (window.XMLHttpRequest) 
    xmlhttp = new XMLHttpRequest();
  else if (window.ActiveXObject) 
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
 
  xmlhttp.open("GET","gcalParse.php", true);
  xmlhttp.onreadystatechange = stateChanged;
  xmlhttp.send('');
}

function stateChanged()
{
  if (xmlhttp.readyState == 1)
  {
    loadingText="<br/><div align='center'><img src='busy.gif'/></div><br/>";
    document.getElementById('insertCalendar').innerHTML = loadingText;
  }
  else if (xmlhttp.readyState == 4)
  {
    updatePage();
  }
}

function updatePage()
{
  var xml = xmlhttp.responseXML.documentElement;
  
  document.getElementById('insertCalendar').innerHTML = ""; 
  var html = "<div id='calendar'>";

  var events = xml.getElementsByTagName('event');
  if (events.length == 0)
  {
    html += 'No events scheduled.</div>';
  }
  else 
  { 
    var title;
    var when;
    var where;
    var description;

    for (var i=0; i < events.length; i++)
    {
      title = events[i].getElementsByTagName('title').item(0).firstChild.data;
      when = events[i].getElementsByTagName('when').item(0).firstChild.data;

      try 
      { 	  
        var map=events[i].getElementsByTagName('where').item(0).firstChild.data;
        var encoded = encodeURIComponent(map);
        where = "Location: ";
        if (parseInt(map))
        {
          where += "<a href='http://maps.google.com/?q=" + encoded + "'>";
          where += map + "</a>";
        }
        else
        {
          where += map;
        }
      } 
      catch (e) 
      { 
        where =  '';
      }

      try
      {
        description = 
          events[i].getElementsByTagName('description').item(0).firstChild.data;
      }
      catch (e)
      {
        description = '';
      }
		  
      html += "<div class='subhead'>"+title+"</div>";
      html += "<div class='bodycopy'><i>"+when+"</i></div>";
      html += "<div class='bodycopy'>"+where+"</div>";
      html += "<br/><div class='bodycopy'>"+description+"</div>";

      if (i != events.length - 1)
          html += "<hr style='width:95%'/>";
    }
    html += '</div>';	
  }
  document.getElementById('insertCalendar').innerHTML = html;
}
