<?php
	class wdaDatabaseAccess
	{		
		public function ExecuteNoneQuery($ssQL, $aParams = array())
		{
            global $wpdb, $table_prefix;
            $stmt = $wpdb->prepare($ssQL, $aParams);
            $affectedRow = $wpdb->query($stmt);			
			return $affectedRow;
		}
		public function ExecuteQuery($ssQL, $aParams = array())
		{
            global $wpdb, $table_prefix;
            $stmt = $wpdb->prepare($ssQL, $aParams);
			return $wpdb->get_results($stmt);
		}
	
		public function FillDropDown( $ssQL , $aParams = array(), $Selected=false)
		{
				
            global $wpdb, $table_prefix;
            $stmt = $wpdb->prepare($ssQL, $aParams);
            $resultSet = $wpdb->get_results($stmt);
			echo $resultSet;
			
			if($resultSet)
			{
                $aKeys = array_keys($resultSet);
				foreach($resultSet as $row )
				{
					if($row[$aKeys[0]]==$Selected && $Selected!=false)
					{
						echo '<option value='.$row[$aKeys[0]].' selected>'.$row[$aKeys[1]].'</option>';
					}
					else
					{
						echo '<option value='.$row[$aKeys[0]].'>'.$row[$aKeys[1]].'</option>';	
					}
				}
			}
			return true;

		}
	
		function DisplayTable($resultSet)
		{
            if(count($resultSet) > 0){

                $aKeys = array_keys((array)$resultSet[0]);
                $No=count($aKeys);
                echo "<table border='1' cellspacing='0'  bordercolor='#222' class='table-list' >";
                echo "<tr class='header'>";
                for($i=0;$i<$No;$i++)
                {
                    $FieldName=$aKeys[$i];
                    echo "<th><label>".$FieldName."</label></th>";
                }
                echo "</tr>";
                foreach($resultSet as $row )
                {
                    echo "<tr>";
                    for($i=0;$i<$No;$i++)
                    {
                            echo "<td> <label>".$row->{$aKeys[$i]}."</label></td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
            return true;
	}
	
}
?>
