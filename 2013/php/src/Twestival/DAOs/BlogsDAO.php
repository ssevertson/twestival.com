<?php namespace Twestival\DAOs;

class BlogsDAO extends BaseDAO
{
	function getBlogs() {
	
		$output = "";
		$conn = $this->container['connection'];
  		// Define and perform the SQL SELECT query
  		$sql = "SELECT * FROM `Blog`";
  		$result = $conn->query($sql);

  		// If the SQL query is succesfully performed ($result not false)
  		if($result !== false) {
    		$cols = $result->columnCount();           // Number of returned columns

    		// Parse the result set
    		$i = 0;
    		foreach($result as $row) {
    			$i++;
    			//print_r($row);
    			$output = $output . $i . " ";
    		}
  		}
		return ($output);
	}

	function getBlog($aBlogID) {
	
		$output = "";
		$conn = $this->container['connection'];
  		// Define and perform the SQL SELECT query
  		$sql = "SELECT * FROM `Blog` WHERE BlogID = " . $aBlogID;
  		
  		$result = $conn->query($sql);

  		// If the SQL query is succesfully performed ($result not false)
  		if($result !== false) {
    		$cols = $result->columnCount();           // Number of returned columns

    		// Parse the result set
    		foreach($result as $row) {
    			//print_r($row);
    			$output = $output . json_decode($row);
    		}
  		}
		return ($output);
	}
}
?>