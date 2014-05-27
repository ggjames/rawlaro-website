<?php
// ======================================
// THE PHP DATATABLE CLASS
// Author: Chris Taylor, stillbreathing.co.uk
// This work is protected under the GPL Licence
// Full details at http://www.opensource.org/licenses/gpl-license.php
// ======================================

class DataTable
{
	// initial variables
	function __construct() {
		// set some variables
		$this->summary = "Table summary";
		$this->caption = "Table caption";
		$this->altClasses = array("","alt");
		$this->fields = array("No fields set");
		$this->rows = array();
		$this->rowParams = array();
		$this->headerRepeat = 0;
		$this->fieldFormats = array();
	}

	// bind a MySQL mysql_fetch_array array to the datatable
	function dataBind($arr){
		// set the fields to nothing
		$this->fields = array();
		// loop the field names and add them
		foreach($arr as $key => $value){
           $this->addField($key);
        }
        // set the rows to nothing
        $this->rows = array();
        // loop each row and add it
		for ($r=0; $r<count($arr); $r++){
			$this->addRow($arr[$r]);
		}
	}

	// add a field
	function addField($fieldName){
		// add the values to a new row
		$this->fields[] = $fieldName;
	}

	// add a row
	function addRow($values,$params=array()){
		// add the values to a new row
		$this->rows[count($this->rows)] = $values;
		// if there are row parameters set
		if (count($params)>0){
			// add any parameters
			$this->rowParams[count($this->rows)-1] = $params;
		}
	}

	// format a field
	function formatField($fieldNo, $rowNo){
		$value = "";
		// if the field has a format
		if ($this->fieldFormats[$fieldNo+1]){
			$output = $this->fieldFormats[$fieldNo+1];
			// modify the field value with the format
			for ($y=0; $y<count($this->rows[$rowNo]); $y++){
				$fieldId = $y+1;
				$value .= "[$y] ";
				$output = preg_replace("/%%$fieldId/", $this->rows[$rowNo][$y], $output);
				$value .= $output ."<br />";
			}
		} else {
			$output = $this->rows[$rowNo][$fieldNo];
		}
		// return the new value
		return $output;
	}
	// set up the field format
	function fieldFormat($fieldNo, $format){
		// add the field format to the fieldFormats array
		$this->fieldFormats[$fieldNo] = $format;
	}

	// show the header row
	function showHeader(){
		$header = "";
		// print the header row
		$header .= "	<tr>\n";
		// loop the fields
		foreach ($this->fields as $field) {
			// print each field
			$header .= "		<th>".$field."</th>\n";
		}
		// end the header row
		$header .= "	</tr>\n";
		return $header;
	}

	// show the data table
	function showDataTable(){
		$output = "";
		// print the table
		$output .= "<table summary=\"".$this->summary."\">\n";
		// print the caption
		$output .= "<caption>".$this->caption."</caption>\n";
		$output .= $this->showHeader();
		// initialise variables
		$altCounter = 0;
		$altClass = "";
		$h = 1;
		// loop each row
		for ($x=0; $x<count($this->rows); $x++) {
			// if it is time to show the header
			if ($h==$this->headerRepeat){
				// show the header
				$output .= $this->showHeader();
				$h = 1;
			}
			$row = $this->rows[$x];
			// alternate the row classes
			if ($this->altClasses){
				if ($this->altClasses[$altCounter]!=""){ $altClass = " class=\"".$this->altClasses[$altCounter]."\""; } else { $altClass=""; }
				if ($altCounter==count($this->altClasses)-1){ $altCounter=0; } else { $altCounter++; }
			}
			// set the parameters to nothing
			$params = "";
			// if there are parameters for this row set
			if (count($this->rowParams[$x])>0){
				// loop the parameters
				while (list($attribute, $parameter) = each($this->rowParams[$x])) {
					// if the parameter is 'class'
					if (strtolower($attribute)=="class"){
						// replace the altClass variable
						$altClass = " ".strtolower($attribute)."=\"$parameter\"";
					} else{
						// otherwise build the parameters
						$params .= " ".strtolower($attribute)."=\"$parameter\"";
					}
				}
			}
			// print the row
			$output .= "	<tr$altClass$params>\n";
				// set the colSpan to 0
				$colSpan = 0;
				$colSpanAttribute = "";
				// if this row has less columns than the number of fields
				if (count($row)<count($this->fields)){
					$colSpan = (count($this->fields)-count($row))+1;
				}
				// loop each cell
				for ($i=0; $i<count($row); $i++) {
					$value = $row[$i];
					$value = $this->formatField($i, $x);
					// make the colspan attribute
					if ($colSpan>0 && $i==(count($row)-1)){ $colSpanAttribute = " colspan=\"$colSpan\""; }
					// print the cell
					$output .= "		<td$colSpanAttribute>".$value."</td>\n";
				}
			// end the row
			$output .= "	</tr>\n";
			// increment the header repeat variable
			$h++;
		}
		// end the table
		$output .= "</table>\n\n";
		print $output;
	}
}

?>
