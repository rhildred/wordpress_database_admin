<?php
/*
 * 	show the list of table in Database
 *	return Resource on Success
 * 	return 0 on fail
 */

function wda_showTable(){
    global $wpdb, $table_prefix;
    return $wpdb->tables();
}

/*
 * 	show the Columns in Table
 *	@param $TableName
 *	return Resource on Success
 * 	return 0 on fail
 */
 
function wda_showTableStructure($TableName){
    global $wpdb, $table_prefix;

    $rc = array();
    $stmt = $wpdb->prepare("SELECT * FROM " . esc_sql($TableName) . " LIMIT 1", array());
    if($wpdb->last_error !== ''){
        $wpdb->print_error();
    }
    $resultSet = $wpdb->get_results($stmt);
    if(count($resultSet) > 0){
        $oRow = $resultSet[0];
        $aKeys = array_keys((array)$oRow);        
        foreach($aKeys as $sKey){
            $oRow = new stdClass();
            $oRow->name = $sKey;
            array_push($rc, $oRow);
        }
    }
	return($rc);
}
 
/**
 * 	Parse the SQL Query
 *	@param $query
 *	return array
 *		[1] $query [SQL statment]
 *		[2] $action [1=SELECT , 0=INSERT,UPDATE]
 * 	remove the all extra content that may be harm to execution in Database
 *	NOTE : Currenlty not used yet, BUT Fully Functional [just not implemented or use any where]
 */
function parseSqlStatment($qry){
	$sqlCheck=array('select');
	$sql['query']=str_replace('\\','',trim($qry));
	$sql['action'] =in_array(strtolower(substr($qry,0,6)),$sqlCheck)?1:0;
	
	return $sql;
}

/*
 * 	Truncate Query String From Urls
 *	@param Get Variable
 *	@param Url
 *	return '' on fail
 */
/*function wda_TruncQueryString($queryString,$url){
	return  preg_replace_callback('/([?&])'.$queryString.'=[^&]+(&|$)/', function($matches) {
		return $matches[2] ? $matches[1] : '';
	}, $url);	
}*/


/*function wda_QueryParse($query){
	
}
*/
/*
 * 	Truncate Query String From Urls
 *	return HTML for Columns
 *	return FALSE on fail
 */
function wda_ajax_getTableColums(){
	$table = $_POST['table'];
	$rsTableColumn = wda_showTableStructure($table);
	if($rsTableColumn){
        foreach($rsTableColumn as $row){
			$content = $row;
			echo '<div class="col-4" data-table="'.$table.'"><label class="lbl-table-col" title="'.$table.'.'.$row.'" data-table="'.$table.'" data-column="'.$row.'" data-type="string" data-null="null" data-key="n/a" draggable="true">'
				.$content.
				'</label></div>';
		}
		
	}else{
		return false;	
	}
	
	die();
}
add_action( 'wp_ajax_nopriv_wdaGetTableColums', 'wda_ajax_getTableColums' );
add_action( 'wp_ajax_wdaGetTableColums', 'wda_ajax_getTableColums' );


/**
 *	Ajax Function Handle The display Data 
 *	Like Select statement or Table Detail
 */
function wda_ajax_setTableActionResponse(){
	global $wdaDbObj;
	//echo '<div class="row"><div class="col-12" style="text-align:right;min-height:40px;"><a id="popup-close" href="javascript:" class="wda-close" >&times;</a><div class="clear"></div></div></div>';
	if($_POST['request']=='browse'){
		$qryGetTableDetail="SELECT * FROM ".$_POST['table'].";";
		$rsGetTableDetail = $wdaDbObj->ExecuteQuery($qryGetTableDetail);
		$wdaDbObj->DisplayTable($rsGetTableDetail);
	}elseif($_POST['request']=='structure'){
		$wdaDbObj->DisplayTable(wda_showTableStructure($_POST['table']));
	}
	die();
} 
add_action('wp_ajax_nopriv_wdaSetTableActionResponse','wda_ajax_setTableActionResponse');
add_action('wp_ajax_wdaSetTableActionResponse','wda_ajax_setTableActionResponse');
?>