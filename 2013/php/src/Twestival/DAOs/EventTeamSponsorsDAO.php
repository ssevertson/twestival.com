<?php namespace Twestival\DAOs;

class EventTeamSponsorsDAO extends BaseDAO
{
	function getEventTeamSponsors() {
	
		$output = "";
		/*$conn = $this->container['connection'];
  		// Define and perform the SQL SELECT query
  		$sql = "SELECT *
FROM `Event` INNER JOIN Blog ON `Event`.BlogID = Blog.BlogID
	 INNER JOIN BlogPost ON Blog.BlogID = BlogPost.BlogID
	 INNER JOIN EventCharity ON `Event`.EventID = EventCharity.EventID";
  		$result = $conn->query($sql);

  		// If the SQL query is succesfully performed ($result not false)
  		if($result !== false) {
    		$cols = $result->columnCount();           // Number of returned columns

    		// Parse the result set
    		$output = "[";
    		foreach($result as $row) {
    			$output = $output . json_encode($row);
    			
    			$output = $output . ",";
    		}
    		
    		$output = substr($output,0,strlen($output) - 1);
    		$output = $output . "]";
  		}*/
		return ($output);
	}

	function getEventTeamSponsor($anEventID) {
		
		$conn = $this->container['connection'];
  		// Define and perform the SQL SELECT query
  		$sql = "SELECT *
			FROM EventSponsor
			WHERE EventID = " . $anEventID;
  		
  		$result = $conn->query($sql);

  		// If the SQL query is succesfully performed ($result not false)
  		if($result !== false) {
    		$cols = $result->columnCount();           // Number of returned columns

    		// Parse the result set
    		$output = "[";
    		foreach($result as $row) {
    			$output = $output . json_encode($row);
    			
    			$output = $output . ",";
    		}
    		
    		$output = substr($output,0,strlen($output) - 1);
    		$output = $output . "]";
  		}
		return ($output);
	}
}
?>