<?php namespace Twestival\DAOs;

class EventBlogsDAO extends BaseDAO
{
	function getEventBlogs() {
	
		$output = "";
		$conn = $this->container['connection'];
  		// Define and perform the SQL SELECT query
  		$sql = "SELECT *
FROM `Event` INNER JOIN Blog ON `Event`.BlogID = Blog.BlogID
	 INNER JOIN BlogPost ON Blog.BlogID = BlogPost.BlogID,
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
  		}
		return ($output);
	}

	function getEventBlog($anEventID) {
	
		$output = "";
		$conn = $this->container['connection'];
  		// Define and perform the SQL SELECT query
  		$sql = "SELECT *, 
			EventCharity.`Name` AS EventCharityName,
			EventCharity.`URL` AS EventCharityURL,
			BlogPost.`Created` AS BlogPostCreated
			FROM `Event` INNER JOIN Blog ON `Event`.BlogID = Blog.BlogID
	 		INNER JOIN BlogPost ON Blog.BlogID = BlogPost.BlogID
	 		INNER JOIN EventCharity ON `Event`.EventID = EventCharity.EventID WHERE Event.EventID = " . $anEventID . " ORDER BY BlogPost.Created DESC";
  		
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