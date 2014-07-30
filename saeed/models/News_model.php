<?php

include_once ('../libraries/Model.php');

class News_model extends Model {

    function getAllNews() {
        $query = "SELECT * FROM news ORDER BY date DESC;";
        $sth = $this->db->prepare($query);
        $sth->execute();
        $rows = $sth->fetchAll();
        return $rows;
    }

    function getNews($newsID) {
        $query = "SELECT * FROM news WHERE id = :newsID LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('newsID', $newsID);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function addNews() {
        include_once '../helpers/helper.php';
        $title = helper::sanitize($_POST['title']);
        $description = helper::sanitize($_POST['description']);
        $link = helper::sanitize($_POST['link']);
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO news VALUES (NULL, :title, :description, :link, :date);";
        $sth = $this->db->prepare($query);
        $sth->bindParam('title', $title);
        $sth->bindParam('description', $description);
        $sth->bindParam('link', $link);
        $sth->bindParam('date', $date);
        $result = $sth->execute();
        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return 0;
        }
    }

    function editNews($newsID) {
        include_once '../helpers/helper.php';
        $title = helper::sanitize($_POST['title2']);
        $description = helper::sanitize($_POST['description2']);
        $link = helper::sanitize($_POST['link2']);
        $query = "UPDATE news SET title = :title, description = :description, link = :link WHERE id = :newsID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('title', $title);
        $sth->bindParam('description', $description);
        $sth->bindParam('link', $link);
        $sth->bindParam('newsID', $newsID);
        $result = $sth->execute();
        return $result;
    }

    function deleteNews($newsID) {
        $query = "DELETE FROM news WHERE id = :newsID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('newsID', $newsID);
        $result = $sth->execute();
        return $result;
    }

    function getAllNewsForDataTables() {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'title', 'description', 'link', 'date');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";

        /* DB table to use */
        $sTable = "news";


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
