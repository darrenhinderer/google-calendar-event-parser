<?
class Event
{
  var $title;
  var $where;
  var $description;
  var $startTime;
  var $endTime;
  var $timeString;
  var $recurs;
  var $recurArray;
  var $announcement;

  function Event()
  {
    $this->recurs = false;
    $this->announcement = false;
    $this->recurArray = Array();
  }

  function toString()
  {
    echo '<event>';
    echo '<title>' . $this->title . '</title>';
    
    $this->parseWhen();
    echo '<when>' . $this->timeString . '</when>';

    echo '<where>' . $this->where . '</where>';
    echo '<description>' . $this->description . '</description>';
    echo '</event>';
  }

  function parseWhen()
  {
    if ($this->recurs)
    {
      if (strftime("%l",($this->recurArray[0])) == 
        (strftime("%l",$this->endTime)))
      {
        $recur = strftime("Every %A",$this->recurArray[0]);
        $this->timeString = $recur;
      }
      else
      {
        $recur = strftime("Every %A, %l:%M%p", $this->recurArray[0]);
        $recur = $recur .  ' - ' .  strftime("%l:%M%p", $this->endTime);
        $this->timeString = $recur;
      }
    }
    else if ($this->announcement == true)
    {
      $this->timeString = "Announcement";
    }
    else
    {
      if (strftime("%l",($this->startTime)) == (strftime("%l",$this->endTime)))
      {
        $this->timeString = strftime("%A %B %e, %G",$this->startTime);
      }
      else
      {
        $start = strftime("%A %B %e, %G %l:%M%p", $this->startTime);
        $end = strftime("%l:%M%p", $this->endTime);
        $this->timeString= $start . ' - ' . $end;
      }
    }
  }
}
?>
