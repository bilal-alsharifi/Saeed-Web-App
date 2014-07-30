<?php

include_once ('../libraries/Model.php');

class Users_model extends Model {

    function getUser($email) {
        $query = "SELECT * FROM user WHERE email = :email LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('email', $email);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function addUser() {
        include_once '../helpers/helper.php';
        $email = helper::sanitize($_POST['email']);
        $password = helper::sanitize($_POST['password']);
        $type = helper::sanitize($_POST['type']);
        $mobile = helper::sanitize($_POST['mobile']);
        $phone = helper::sanitize($_POST['phone']);
        $address = helper::sanitize($_POST['address']);
        $longitude = helper::sanitize($_POST['longitude']);
        $latitude = helper::sanitize($_POST['latitude']);
        $notes = helper::sanitize($_POST['notes']);
        $query = "INSERT INTO user VALUES (NULL, :email, :password, :type, :mobile, :phone, :address, :longitude, :latitude, :notes);";
        $sth = $this->db->prepare($query);
        $sth->bindParam('email', $email);
        $sth->bindParam('password', md5($password));
        $sth->bindParam('type', $type);
        $sth->bindParam('mobile', $mobile);
        $sth->bindParam('phone', $phone);
        $sth->bindParam('address', $address);
        $sth->bindParam('longitude', $longitude);
        $sth->bindParam('latitude', $latitude);
        $sth->bindParam('notes', $notes);
        $result = $sth->execute();
        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return 0;
        }
    }

    function editUser($userID) {
        include_once '../helpers/helper.php';
        $email = helper::sanitize($_POST['email2']);
        $password = helper::sanitize($_POST['password2']);
        $mobile = helper::sanitize($_POST['mobile2']);
        $phone = helper::sanitize($_POST['phone2']);
        $address = helper::sanitize($_POST['address2']);
        $longitude = helper::sanitize($_POST['longitude2']);
        $latitude = helper::sanitize($_POST['latitude2']);
        $notes = helper::sanitize($_POST['notes2']);
        if (strlen($password) > 0) {
            $query = "UPDATE user SET email = :email, password = :password, mobile = :mobile, phone = :phone, address = :address, longitude = :longitude, latitude = :latitude, notes = :notes WHERE id = :userID;";
        } else {
            $query = "UPDATE user SET email = :email, mobile = :mobile, phone = :phone, address = :address, longitude = :longitude, latitude = :latitude, notes = :notes WHERE id = :userID;";
        }
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->bindParam('email', $email);
        if (strlen($password) > 0) {
            $sth->bindParam('password', md5($password));
        }
        $sth->bindParam('mobile', $mobile);
        $sth->bindParam('phone', $phone);
        $sth->bindParam('address', $address);
        $sth->bindParam('longitude', $longitude);
        $sth->bindParam('latitude', $latitude);
        $sth->bindParam('notes', $notes);
        $result = $sth->execute();
        return $result;
    }

    function deleteUser($userID) {
        $query = "DELETE FROM user WHERE id = :userID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $result = $sth->execute();
        return $result;
    }

    function getDoctor($userID) {
        $query = "SELECT * FROM doctor WHERE user_id = :userID LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function addDoctor($userID) {
        include_once '../helpers/helper.php';
        $firstName = helper::sanitize($_POST['firstName']);
        $lastName = helper::sanitize($_POST['lastName']);
        $gender = helper::sanitize($_POST['gender']);
        $specializationID = helper::sanitize($_POST['specialization']);
        $query = "INSERT INTO doctor VALUES (:userID, :firstName, :lastName, :gender, :specializationID);";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->bindParam('firstName', $firstName);
        $sth->bindParam('lastName', $lastName);
        $sth->bindParam('gender', $gender);
        $sth->bindParam('specializationID', $specializationID);
        $result = $sth->execute();
        return $result;
    }

    function editDoctor($userID) {
        include_once '../helpers/helper.php';
        $firstName = helper::sanitize($_POST['firstName2']);
        $lastName = helper::sanitize($_POST['lastName2']);
        $gender = helper::sanitize($_POST['gender2']);
        $specializationID = helper::sanitize($_POST['specialization2']);
        $query = "UPDATE doctor SET firstName = :firstName, lastName = :lastName, gender = :gender, specialization_id = :specializationID WHERE user_id = :userID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->bindParam('firstName', $firstName);
        $sth->bindParam('lastName', $lastName);
        $sth->bindParam('gender', $gender);
        $sth->bindParam('specializationID', $specializationID);
        $result = $sth->execute();
        return $result;
    }

    function getPharmacy($userID) {
        $query = "SELECT * FROM pharmacy WHERE user_id = :userID LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function addPharmacy($userID) {
        include_once '../helpers/helper.php';
        $name = helper::sanitize($_POST['pharmacyName']);
        $query = "INSERT INTO pharmacy VALUES (:userID, :name);";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->bindParam('name', $name);
        $result = $sth->execute();
        return $result;
    }

    function editPharmacy($userID) {
        include_once '../helpers/helper.php';
        $pharmacyName = helper::sanitize($_POST['pharmacyName2']);
        $query = "UPDATE pharmacy SET name = :pharmacyName WHERE user_id = :userID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->bindParam('pharmacyName', $pharmacyName);
        $result = $sth->execute();
        return $result;
    }

    function getHospital($userID) {
        $query = "SELECT * FROM hospital WHERE user_id = :userID LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function addHospital($userID) {
        include_once '../helpers/helper.php';
        $name = helper::sanitize($_POST['hospitalName']);
        $query = "INSERT INTO hospital VALUES (:userID, :name);";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->bindParam('name', $name);
        $result = $sth->execute();
        return $result;
    }

    function editHospital($userID) {
        include_once '../helpers/helper.php';
        $hospitalName = helper::sanitize($_POST['hospitalName2']);
        $query = "UPDATE hospital SET name = :hospitalName WHERE user_id = :userID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->bindParam('hospitalName', $hospitalName);
        $result = $sth->execute();
        return $result;
    }

    function getSpecializations() {
        $query = "SELECT * FROM doctorspecialization;";
        $sth = $this->db->prepare($query);
        $sth->execute();
        $rows = $sth->fetchAll();
        return $rows;
    }

    function searchForUsers($name) {
        $query = "SELECT u.*, null AS name, d.firstName, d.lastName, d.gender, s.name AS specialization
                  FROM user AS u INNER JOIN doctor AS d INNER JOIN doctorspecialization AS s ON (u.id = d.user_id AND d.specialization_id = s.id)
                  WHERE INSTR(CONCAT(d.firstName , ' ' , d.lastName), :name) > 0
                  UNION
                  SELECT u.*, h.name, null AS firstName, null AS lastName, null AS gender, null AS specialization
                  FROM user AS u INNER JOIN hospital AS h ON (u.id = h.user_id)
                  WHERE INSTR(h.name, :name) > 0
                  UNION
                  SELECT u.*, p.name, null AS firstName, null AS lastName, null AS gender, null AS specialization 
                  FROM user AS u INNER JOIN pharmacy AS p ON (u.id = p.user_id)
                  WHERE INSTR(p.name, :name) > 0";
        $sth = $this->db->prepare($query);
        $sth->bindParam('name', $name);
        $sth->execute();
        return $sth->fetchAll();
    }

    function getUsersByType($type) {
        $query = "";
        switch ($type) {
            case 'مستخدم':
                break;
            case 'طبيب':
                $query = "SELECT * FROM user AS u INNER JOIN doctor AS d INNER JOIN doctorspecialization AS s ON (u.id = d.user_id AND d.specialization_id = s.id);";
                break;
            case 'مشفى':
                $query = "SELECT * FROM user AS u INNER JOIN hospital AS h ON (u.id = h.user_id);";
                break;
            case 'صيدلية':
                $query = "SELECT * FROM user AS u INNER JOIN pharmacy AS p ON (u.id = p.user_id);";
                break;
        }
        $sth = $this->db->prepare($query);
        $sth->execute();
        return $sth->fetchAll();
    }

    function getKeyWords() {
        $query = "SELECT CONCAT(firstName , ' ' , lastName) AS name FROM doctor
                  UNION
                  SELECT name FROM hospital
                  UNION
                  SELECT name FROM pharmacy";
        $sth = $this->db->prepare($query);
        $sth->bindParam('name', $name);
        $sth->execute();
        $rows = $sth->fetchAll();
        $result = array();
        foreach ($rows as $row) {
            $result[] = $row['name'];
        }
        return $result;
    }

    function getAllUsersForDataTables() {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('email', 'type', 'mobile', 'phone', 'address', 'longitude', 'latitude');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";

        /* DB table to use */
        $sTable = "user";


        include_once ('../config/config.php');
        /* Database connection information */
        $gaSql['user'] = DB_USER;
        $gaSql['password'] = DB_PASS;
        $gaSql['db'] = DB_NAME;
        $gaSql['server'] = DB_HOST;

        /* REMOVE THIS LINE (it just includes my SQL connection user/pass) */
        //include( $_SERVER['DOCUMENT_ROOT']."/datatables/mysql.php" );


        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP server-side, there is
         * no need to edit below this line
         */

        /*
         * MySQL connection
         */
        $gaSql['link'] = mysql_pconnect($gaSql['server'], $gaSql['user'], $gaSql['password']) or
                die('Could not open connection to server');

        mysql_select_db($gaSql['db'], $gaSql['link']) or
                die('Could not select database ' . $gaSql['db']);
        mysql_set_charset('utf8');

        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " .
                    mysql_real_escape_string($_GET['iDisplayLength']);
        }


        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
                            mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }


        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }


        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }


        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";
        $rResult = mysql_query($sQuery, $gaSql['link']) or die(mysql_error());

        /* Data set length after filtering */
        $sQuery = "
		SELECT FOUND_ROWS()
	";
        $rResultFilterTotal = mysql_query($sQuery, $gaSql['link']) or die(mysql_error());
        $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
        $iFilteredTotal = $aResultFilterTotal[0];

        /* Total data set length */
        $sQuery = "
		SELECT COUNT(`" . $sIndexColumn . "`)
		FROM   $sTable
	";
        $rResultTotal = mysql_query($sQuery, $gaSql['link']) or die(mysql_error());
        $aResultTotal = mysql_fetch_array($rResultTotal);
        $iTotal = $aResultTotal[0];


        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        while ($aRow = mysql_fetch_array($rResult)) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "version") {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[$aColumns[$i]] == "0") ? '-' : $aRow[$aColumns[$i]];
                } else if ($aColumns[$i] != ' ') {
                    /* General output */
                    $row[] = $aRow[$aColumns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }

        return json_encode($output);
    }

//    function whereTemp()
//    {
//        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
//	 * Easy set variables
//	 */
//	
//	/* Array of database columns which should be read and sent back to DataTables. Use a space where
//	 * you want to insert a non-database field (for example a counter or static image)
//	 */
//	 $aColumns = array('email', 'type', 'mobile', 'phone', 'address', 'longitude', 'latitude');
//
//        
//	/* Indexed column (used for fast and accurate table cardinality) */
//	$sIndexColumn = "id";
//	
//	/* DB table to use */
//	$sTable = "user";
//
//	
//	include_once ('../config/config.php');
//	/* Database connection information */
//	$gaSql['user']       = DB_USER;
//	$gaSql['password']   = DB_PASS;
//	$gaSql['db']         = DB_NAME ;
//	$gaSql['server']     = DB_HOST;
//	
//	/* REMOVE THIS LINE (it just includes my SQL connection user/pass) */
//	//include( $_SERVER['DOCUMENT_ROOT']."/datatables/mysql.php" );
//	
//	
//	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
//	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
//	 * no need to edit below this line
//	 */
//	
//	/* 
//	 * MySQL connection
//	 */
//	$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
//		die( 'Could not open connection to server' );
//	
//	mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
//		die( 'Could not select database '. $gaSql['db'] );
//	mysql_set_charset('utf8'); 
//	
//	/* 
//	 * Paging
//	 */
//	$sLimit = "";
//	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
//	{
//		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
//			mysql_real_escape_string( $_GET['iDisplayLength'] );
//	}
//	
//	
//	/*
//	 * Ordering
//	 */
//	$sOrder = "";
//	if ( isset( $_GET['iSortCol_0'] ) )
//	{
//		$sOrder = "ORDER BY  ";
//		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
//		{
//			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
//			{
//				$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
//				 	mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
//			}
//		}
//		
//		$sOrder = substr_replace( $sOrder, "", -2 );
//		if ( $sOrder == "ORDER BY" )
//		{
//			$sOrder = "";
//		}
//	}
//	
//	
//	/* 
//	 * Filtering
//	 * NOTE this does not match the built-in DataTables filtering which does it
//	 * word by word on any field. It's possible to do here, but concerned about efficiency
//	 * on very large tables, and MySQL's regex functionality is very limited
//	 */
//	//$sWhere = "";
//        $sWhere  = " WHERE email like '%'";
//	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
//	{
//		$sWhere = "WHERE (";
//		for ( $i=0 ; $i<count($aColumns) ; $i++ )
//		{
//			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
//		}
//		$sWhere = substr_replace( $sWhere, "", -3 );
//		//$sWhere .= ')';
//                $sWhere .= ") AND email like '%'";
//	}
//	
//	/* Individual column filtering */
//	for ( $i=0 ; $i<count($aColumns) ; $i++ )
//	{
//		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
//		{
//			if ( $sWhere == "" )
//			{
//				$sWhere = "WHERE ";
//			}
//			else
//			{
//				$sWhere .= " AND ";
//			}
//			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
//		}
//	}
//	
//	
//	/*
//	 * SQL queries
//	 * Get data to display
//	 */
//	$sQuery = "
//		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
//		FROM   $sTable
//		$sWhere
//		$sOrder
//		$sLimit
//		";
//	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
//	
//	/* Data set length after filtering */
//	$sQuery = "
//		SELECT FOUND_ROWS()
//	";
//	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
//	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
//	$iFilteredTotal = $aResultFilterTotal[0];
//	
//	/* Total data set length */
//	$sQuery = "
//		SELECT COUNT(`".$sIndexColumn."`)
//		FROM   $sTable
//	";
//	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
//	$aResultTotal = mysql_fetch_array($rResultTotal);
//	$iTotal = $aResultTotal[0];
//	
//	
//	/*
//	 * Output
//	 */
//	$output = array(
//		"sEcho" => intval($_GET['sEcho']),
//		"iTotalRecords" => $iTotal,
//		"iTotalDisplayRecords" => $iFilteredTotal,
//		"aaData" => array()
//	);
//	
//	while ( $aRow = mysql_fetch_array( $rResult ) )
//	{
//		$row = array();
//		for ( $i=0 ; $i<count($aColumns) ; $i++ )
//		{
//			if ( $aColumns[$i] == "version" )
//			{
//				/* Special output formatting for 'version' column */
//				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
//			}
//			else if ( $aColumns[$i] != ' ' )
//			{
//				/* General output */
//				$row[] = $aRow[ $aColumns[$i] ];
//			}
//		}
//		$output['aaData'][] = $row;
//	}
//	
//	return json_encode( $output );        
//    }  
}

?>
