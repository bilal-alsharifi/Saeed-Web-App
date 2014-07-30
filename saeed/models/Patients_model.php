<?php

include_once ('../libraries/Model.php');

class Patients_model extends Model {

    function getPatient($patientID) {
        $query = "SELECT * FROM patient WHERE id = :patientID LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patientID', $patientID);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function getAllCompanies() {
        $query = "SELECT * FROM company;";
        $sth = $this->db->prepare($query);
        $sth->execute();
        $rows = $sth->fetchAll();
        return $rows;
    }

    function addPatient() {
        include_once '../helpers/helper.php';
        $companyID = helper::sanitize($_POST['company']);
        $firstName = helper::sanitize($_POST['firstName']);
        $lastName = helper::sanitize($_POST['lastName']);
        $gender = helper::sanitize($_POST['gender']);
        $nationalNumber = helper::sanitize($_POST['nationalNumber']);
        $email = helper::sanitize($_POST['email']);
        $mobile = helper::sanitize($_POST['mobile']);
        $phone = helper::sanitize($_POST['phone']);
        $address = helper::sanitize($_POST['address']);
        // add a year to the current date
        $currentDate = date("Y-m-d");
        $dateOneYearAdded = strtotime(date("Y-m-d", strtotime($currentDate)) . " +1 year");
        $expiryDate = date("Y-m-d", $dateOneYearAdded);
        $query = "INSERT INTO patient VALUES (NULL, :companyID ,:firstName, :lastName, :gender, :nationalNumber, :email, :mobile, :phone, :address, :expiryDate, 0, 0);";
        $sth = $this->db->prepare($query);
        $sth->bindParam('companyID', $companyID);
        $sth->bindParam('firstName', $firstName);
        $sth->bindParam('lastName', $lastName);
        $sth->bindParam('gender', $gender);
        $sth->bindParam('nationalNumber', $nationalNumber);
        $sth->bindParam('email', $email);
        $sth->bindParam('mobile', $mobile);
        $sth->bindParam('phone', $phone);
        $sth->bindParam('address', $address);
        $sth->bindParam('expiryDate', $expiryDate);
        $result = $sth->execute();
        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return 0;
        }
    }

    function renewAccount($patientID) {
        include_once '../helpers/helper.php';
        // add a year to the current date
        $currentDate = date("Y-m-d");
        $dateOneYearAdded = strtotime(date("Y-m-d", strtotime($currentDate)) . " +1 year");
        $expiryDate = date("Y-m-d", $dateOneYearAdded);
        //
        $query = "UPDATE patient SET expiryDate = :expiryDate, numOfVisits = 0, money = 0 WHERE id = :patientID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patientID', $patientID);
        $sth->bindParam('expiryDate', $expiryDate);
        $result = $sth->execute();
        return $result;
    }

    function editPatient($patientID) {
        include_once '../helpers/helper.php';
        $companyID = helper::sanitize($_POST['company2']);
        $firstName = helper::sanitize($_POST['firstName2']);
        $lastName = helper::sanitize($_POST['lastName2']);
        $gender = helper::sanitize($_POST['gender2']);
        $nationalNumber = helper::sanitize($_POST['nationalNumber2']);
        $email = helper::sanitize($_POST['email2']);
        $mobile = helper::sanitize($_POST['mobile2']);
        $phone = helper::sanitize($_POST['phone2']);
        $address = helper::sanitize($_POST['address2']);
        $query = "UPDATE patient SET company_id = :companyID, firstName = :firstName, lastName = :lastName, gender = :gender, nationalNumber = :nationalNumber, email = :email, mobile = :mobile, phone = :phone, address = :address WHERE id = :patientID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('companyID', $companyID);
        $sth->bindParam('patientID', $patientID);
        $sth->bindParam('firstName', $firstName);
        $sth->bindParam('lastName', $lastName);
        $sth->bindParam('gender', $gender);
        $sth->bindParam('nationalNumber', $nationalNumber);
        $sth->bindParam('email', $email);
        $sth->bindParam('mobile', $mobile);
        $sth->bindParam('phone', $phone);
        $sth->bindParam('address', $address);
        $result = $sth->execute();
        return $result;
    }

    function deletePatient($patientID) {
        $query = "DELETE FROM patient WHERE id = :patientID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patientID', $patientID);
        $result = $sth->execute();
        return $result;
    }

    function getAllPatientsForDataTables() {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'firstName', 'lastName', 'gender', 'nationalNumber', 'address', 'expiryDate', 'numOfVisits', 'money');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";

        /* DB table to use */
        $sTable = "patient";


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

}

?>
