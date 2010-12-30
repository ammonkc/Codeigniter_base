<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* Excel library for Code Igniter applications
* Author: Derek Allard, Dark Horse Consulting, www.darkhorse.to, April 2006
*/

function to_excel($query, $filename='exceloutput')
{
     $headers = ''; // just creating the var for field headers to append to below
     $data = ''; // just creating the var for field data to append to below
     $doc_header = '';
	 $doc_footer = '';
	 $columns = '';
	 $k = 1;
     $obj =& get_instance();
     
     $fields = $query->field_data();
     if ($query->num_rows() == 0) {
          echo '<p>The table appears to have no data.</p>';
     } else {
		$doc_header = <<<EOH
			<?xml version="1.0" encoding="UTF-8"?>
			<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
			 xmlns:x="urn:schemas-microsoft-com:office:excel"
			 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
			 xmlns:html="http://www.w3.org/TR/REC-html40">
			 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
		  		<Version>12.00</Version>
			 </DocumentProperties>
			 <Styles>
			  <Style ss:ID="Default" ss:Name="Normal">
			   <Alignment ss:Vertical="Bottom"/>
			   <Borders/>
			   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
			   <Interior/>
			   <NumberFormat/>
			   <Protection/>
			  </Style>
			 </Styles>
			<Worksheet ss:Name="Table1">
			  <Table>
EOH;
			$doc_footer = '</Table></Worksheet></Workbook>';
			$headers .= '<Row>';
			$col_count = count($fields);
          foreach ($fields as $field) {
			 	if ( $k < $col_count ) {
					$columns .= '<Column ss:AutoFitWidth="0" ss:Width="100"/>';
					$k++;
				}
             $headers .= '<Cell><Data ss:Type="String">'.$field->name.'</Data></Cell>';
          }
			$headers .= '</Row>';
     
          foreach ($query->result() as $row) {
               $line = '<Row>';
               foreach($row as $value) {
                    $line .= '<Cell><Data ss:Type="String">'.$value.'</Data></Cell>';
               }
			   $line .= '</Row>';
               $data .= trim($line);
          }
          header("Cache-Control: ");// leave blank to avoid IE errors
		  header("Pragma: ");// leave blank to avoid IE errors
		  header("Content-type: application/octet-stream");
		  header("Content-Disposition: attachment; filename=$filename.xls");
          echo "$doc_header.$columns.$headers.$data.$doc_footer";  
     }
}
?>