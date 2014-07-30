<?php

include_once ('../libraries/Model.php');

class Membership_model extends Model {

    var $userID;

    function __construct($userID = null) {
        parent::__construct();
        if (!isset($_SESSION)) {
            session_start();
        }
        if ($userID == null && isset($_SESSION['userID']) && $_SESSION['userID'] != null) {
            $this->userID = $_SESSION['userID'];
        } else {
            $this->userID = $userID;
        }
    }

    function validate($email, $password) {
        $query = "SELECT * FROM user WHERE email = :email AND password = :password LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('email', $email);
        $sth->bindParam('password', md5($password));
        $sth->execute();
        if ($sth->rowCount() != 0) {
            $rows = $sth->fetchAll();
            $row = $rows[0];
            $this->userID = $row[id];
            return $row;
        } else {
            return null;
        }
    }

    function editPassword($password) {
        $password = md5($password);
        $query = "UPDATE user SET password = :password WHERE id = :userID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $this->userID);
        $sth->bindParam('password', $password);
        return $sth->execute();
    }

    function getUser() {
        $query = "SELECT * FROM user WHERE id = :userID LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $this->userID);
        $sth->execute();
        $rows = $sth->fetchAll();
        $row = $rows[0];
        return $row;
    }

    function getAllPermissions() {
        $query = "SELECT * FROM permission;";
        $sth = $this->db->prepare($query);
        $sth->execute();
        return $sth->fetchAll();
    }

    function getPermissions($roleID) {
        $query = "SELECT p.id, p.name, p.displayName, p.description
                  FROM permission AS p INNER JOIN role_permission AS rp
                  ON (p.id = rp.permission_id)
                  WHERE rp.role_id = :roleID ;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('roleID', $roleID);
        $sth->execute();
        return $sth->fetchAll();
    }

    function addPermissions($roleID, $permissions) {
        if (count($permissions) == 0) {
            return true;
        }
        $query1 = "DELETE FROM role_permission WHERE role_id = :roleID ;";
        $sth1 = $this->db->prepare($query1);
        $sth1->bindParam('roleID', $roleID);
        $success1 = $sth1->execute();
        $query2 = "INSERT INTO role_permission VALUES";
        $date = date("Y-m-d");
        $i = 0;
        foreach ($permissions as $p) {
            $query2 .= "(NULL, {$roleID}, {$p}, '{$date}')";
            if ($i < (count($permissions) - 1)) {
                $query2 .= " , ";
            }
            $i++;
        }
        $query2 .= ";";
        $sth2 = $this->db->prepare($query2);
        $success2 = $sth2->execute();
        return ($success1 && $success2);
    }

    function getUserPermissionsArray() {
        $query = "SELECT * FROM
                (SELECT * FROM user WHERE id = :userID) AS u
                INNER JOIN 
                (SELECT * FROM user_role) AS ur 
                INNER JOIN 
                (SELECT * FROM role_permission) AS rp
                INNER JOIN 
                (SELECT * FROM permission) AS p
                ON (u.id = ur.user_id AND ur.role_id = rp.role_id AND rp.permission_id = p.id)";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $this->userID);
        $sth->execute();
        $rows = $sth->fetchAll();
        $result = array();
        foreach ($rows as $row) {
            $result[$row['name']] = TRUE;
        }
        return $result;
    }

    function getAllRoles() {
        $query = "SELECT * FROM role;";
        $sth = $this->db->prepare($query);
        $sth->execute();
        return $sth->fetchAll();
    }

    function getRole($roleName) {
        $query = "SELECT * FROM role WHERE name = :roleName LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('roleName', $roleName);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function getRoles($userID) {
        $query = "SELECT r.id, r.name, r.description
                  FROM role AS r INNER JOIN user_role AS ur
                  ON (r.id = ur.role_id)
                  WHERE ur.user_id = :userID ;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('userID', $userID);
        $sth->execute();
        return $sth->fetchAll();
    }

    function addRoles($userID, $roles) {
        if (count($roles) == 0) {
            return true;
        }
        $query1 = "DELETE FROM user_role WHERE user_id = :userID ;";
        $sth1 = $this->db->prepare($query1);
        $sth1->bindParam('userID', $userID);
        $success1 = $sth1->execute();
        $query2 = "INSERT INTO user_role VALUES";
        $date = date("Y-m-d");
        $i = 0;
        foreach ($roles as $r) {
            $query2 .= "(NULL, {$userID}, {$r}, '{$date}')";
            if ($i < (count($roles) - 1)) {
                $query2 .= " , ";
            }
            $i++;
        }
        $query2 .= ";";
        $sth2 = $this->db->prepare($query2);
        $success2 = $sth2->execute();
        return ($success1 && $success2);
    }

    function addRole() {
        include_once '../helpers/helper.php';
        $name = helper::sanitize($_POST['name']);
        $description = helper::sanitize($_POST['description']);
        $query = "INSERT INTO role VALUES (NULL, :name, :description);";
        $sth = $this->db->prepare($query);
        $sth->bindParam('name', $name);
        $sth->bindParam('description', $description);
        $result = $sth->execute();
        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return 0;
        }
    }

    function editRole($roleID) {
        include_once '../helpers/helper.php';
        $name = helper::sanitize($_POST['name2']);
        $description = helper::sanitize($_POST['description2']);
        $query = "UPDATE role SET name = :name, description = :description WHERE id = :roleID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('roleID', $roleID);
        $sth->bindParam('name', $name);
        $sth->bindParam('description', $description);
        $result = $sth->execute();
        return $result;
    }

    function deleteRole($roleID) {
        $query = "DELETE FROM role WHERE id = :roleID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('roleID', $roleID);
        $result = $sth->execute();
        return $result;
    }

    function getAllRolesForDataTables() {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('name', 'description');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";

        /* DB table to use */
        $sTable = "role";


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
