<?php

include_once ('../libraries/Model.php');

class Patient_model extends Model {

    function getPatientInfo($nationalNumber) {
        $nationalNumber = helper::sanitize($nationalNumber);
        $query = "SELECT * FROM patient WHERE nationalNumber = :nationalNumber LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('nationalNumber', $nationalNumber);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function getDoctorVisitsForPatient($doctor_id) {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('date', 'price', 'notes');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";

        /* DB table to use */
        $sTable = "doctorvisit";


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
        //$sWhere = "";
        $sWhere = " WHERE doctor_id = '{$doctor_id}' AND patient_id = '{$_GET['patient_id']}'";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            //$sWhere .= ')';
            $sWhere .= ") AND doctor_id = '{$doctor_id}' AND patient_id = '{$_GET['patient_id']}'";
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

    function ifExistMoney($patientID, $price) {
        $query = "SELECT money FROM patient WHERE id = :patientID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patientID', $patientID);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $row = $sth->fetch();
            $maxMoneyPerYear = $this->getRecordFromConfig('Max Money Per Year');
            $paymentUntilNow = $row['money'] + $price;
            if ($maxMoneyPerYear >= $paymentUntilNow) {
                $query = "UPDATE patient SET money = :paymentUntilNow WHERE id = :patientID;";
                $sth = $this->db->prepare($query);
                $sth->bindParam('patientID', $patientID);
                $sth->bindParam('paymentUntilNow', $paymentUntilNow);
                $result = $sth->execute();
                return true;
            } else {
                return null;
            }
        }
    }

    function ifExistVisits($patientID) {
        $query = "SELECT numOfVisits FROM patient WHERE id = :patientID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patientID', $patientID);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $row = $sth->fetch();
            $maxVisitsPerYear = $this->getRecordFromConfig('Max Visits Per Year');
            $numOfVisits = $row['numOfVisits'] + 1;
            if ($maxVisitsPerYear >= $numOfVisits) {
                $query = "UPDATE patient SET numOfVisits = :numOfVisits WHERE id = :patientID;";
                $sth = $this->db->prepare($query);
                $sth->bindParam('patientID', $patientID);
                $sth->bindParam('numOfVisits', $numOfVisits);
                $result = $sth->execute();
                return true;
            } else {
                return null;
            }
        }
    }

    function editNumberOfVisits($patientID) {
        $query = "SELECT numOfVisits FROM patient WHERE id = :patientID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patientID', $patientID);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $row = $sth->fetch();
            $numOfVisits = $row['numOfVisits'] - 1;
            $query = "UPDATE patient SET numOfVisits = :numOfVisits WHERE id = :patientID;";
            $sth = $this->db->prepare($query);
            $sth->bindParam('patientID', $patientID);
            $sth->bindParam('numOfVisits', $numOfVisits);
            $result = $sth->execute();
            return true;
        }
    }

    function ifNotExpireDate($patientID) {
        $query = "SELECT expiryDate FROM patient WHERE id = :patientID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patientID', $patientID);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $row = $sth->fetch();
            $now = date("Y-m-d H:i:s");
            if ($row['expiryDate'] > $now) {
                return true;
            } else {
                return null;
            }
        }
    }

    function ifPeriodNotEnd($patientID, $doctor_id) {
        $query = "SELECT MAX(date) AS date FROM doctorvisit WHERE patient_id = :patientID AND doctor_id = :doctor_id;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patientID', $patientID);
        $sth->bindParam('doctor_id', $doctor_id);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $row = $sth->fetch();
            $periodBetweenVisit = $this->getRecordFromConfig("Period Between Visit");
            if ($periodBetweenVisit == null) {
                return null;
            }
            //date('Y-m-d H:i:s', strtotime($day . " +".$x." days"))
            $date1 = $row['date'];
            $date2 = date("Y-m-d H:i:s");
            $diff = abs(strtotime($date2) - strtotime($date1));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $allDays = $days + ($months * 30) + ($years * 360);
            //$now = date("Y-m-d H:i:s");
            if ($allDays > $periodBetweenVisit) {
                return true;
            } else {
                return null;
            }
        }
    }

    function getRecordFromConfig($name) {
        $query = "SELECT value FROM config WHERE name = :name;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('name', $name);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $row = $sth->fetch();
            if ($row['value'] > 0) {
                return $row['value'];
            } else {
                return null;
            }
        }
    }

    function addDoctorVisit($doctor_id) {
        include_once '../helpers/helper.php';
        $patient = $this->getPatientInfo($_POST['nationalNumber']);
        $patientID = $patient['id'];
        $date = date("Y-m-d H:i:s");
        if (!isset($_POST['price']) || empty($_POST['price'])) {
            $price = "0";
        } else {
            $price = helper::sanitize($_POST['price']);
        }
        //check if not expire date
        $ifNotExpireDate = $this->ifNotExpireDate($patientID);
        if ($ifNotExpireDate == null) {
            return "فشلت الإضافة بسبب انتهاء مدة التأمين السنوية";
        }
        //check if period betweem treatment
        $ifPeriodNotEnd = $this->ifPeriodNotEnd($patientID, $doctor_id);
        if ($ifPeriodNotEnd == null) {
            return " فشلت الإضافة بسبب عدم مضي المدة الكافية بين زيارتين متتاليتين";
        }
        //check if still visits for treatment
        $ifExistVisits = $this->ifExistVisits($patientID);
        if ($ifExistVisits == null) {
            return "فشلت الإضافة بسبب انتهاء عدد الزيارات الأعظمي";
        }
        //check if still money for treatment
        $ifExistMoney = $this->ifExistMoney($patientID, $price);
        if ($ifExistMoney == null) {
            $this->editNumberOfVisits($patientID);
            return "فشلت الإضافة بسبب نفاذ مبلغ التأمين";
        }
        $notes = helper::sanitize($_POST['notes']);
        $query = "INSERT INTO doctorvisit VALUES (NULL, :price, :date, :doctor_id, :patient_id, :notes, :paid);";
        $sth = $this->db->prepare($query);
        $sth->bindParam('price', $price);
        $sth->bindParam('date', $date);
        $sth->bindParam('doctor_id', $doctor_id);
        $sth->bindParam('patient_id', $patientID);
        $sth->bindParam('notes', $notes);
        $paid = '0';
        $sth->bindParam('paid', $paid);
        $result = $sth->execute();
        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return "فشلت عملية الإضافة";
        }
    }

    function addMedicine($doctor_id) {
        include_once '../helpers/helper.php';
        $date = date("Y-m-d H:i:s");
        $medicineName = helper::sanitize($_POST['medicineName']);
        $notes = helper::sanitize($_POST['notes']);
        $patient = $this->getPatientInfo($_POST['nationalNumber']);
        $patient_id = $patient['id'];
        $doctorVisit = $this->getDoctorVisitID($doctor_id, $patient_id);
        $doctorVisitID = $doctorVisit['id'];
        $paid = '0';
        $query = "INSERT INTO medicine VALUES (NULL, :doctorVisit_id, :date, :name, NULL, NULL, NULL, NULL, :notes, :paid);";
        $sth = $this->db->prepare($query);
        $sth->bindParam('doctorVisit_id', $doctorVisitID);
        $sth->bindParam('date', $date);
        $sth->bindParam('name', $medicineName);
        $sth->bindParam('notes', $notes);
        $sth->bindParam('paid', $paid);
        $result = $sth->execute();
        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return 0;
        }
    }

    function addHospitalService($doctor_id) {
        include_once '../helpers/helper.php';
        $date = date("Y-m-d H:i:s");
        $hospitalService = helper::sanitize($_POST['hospitalService']);
        $notes = helper::sanitize($_POST['notes']);
        $patient = $this->getPatientInfo($_POST['nationalNumber']);
        $patient_id = $patient['id'];
        $doctorVisit = $this->getDoctorVisitID($doctor_id, $patient_id);
        $doctorVisitID = $doctorVisit['id'];
        $paid = '0';
        $query = "INSERT INTO hospitalservice VALUES (NULL, :doctorVisit_id, :date, :name, NULL, NULL, NULL, :notes, :paid)";
        $sth = $this->db->prepare($query);
        $sth->bindParam('doctorVisit_id', $doctorVisitID);
        $sth->bindParam('name', $hospitalService);
        $sth->bindParam('notes', $notes);
        $sth->bindParam('date', $date);
        $sth->bindParam('paid', $paid);
        $result = $sth->execute();
        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return 0;
        }
    }

    function getDoctorVisitID($doctor_id, $patient_id) {
        $query = "SELECT id, MAX(date) FROM doctorvisit WHERE doctor_id = :doctor_id AND patient_id = :patient_id GROUP BY id ORDER BY date DESC LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('doctor_id', $doctor_id);
        $sth->bindParam('patient_id', $patient_id);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function getAllPatientMedicineForDataTables($patient_id) {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('name', 'dateOfDoctorVisit');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "doctorVisit_id";

        /* DB table to use */
        $sTable = "medicine
                    INNER JOIN doctorvisit ON ( medicine.doctorVisit_id = doctorvisit.id )
                    INNER JOIN patient ON ( patient.id = doctorvisit.patient_id )";


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
        //$sWhere = "";
        $sWhere = " WHERE medicine.price IS NULL AND medicine.pharmacy_id IS NULL AND patient.id = '{$patient_id}'";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            //$sWhere .= ')';
            $sWhere .= ") AND medicine.price IS NULL AND medicine.pharmacy_id IS NULL AND patient.id = '{$patient_id}'";
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

    function getMedicine($patient_id) {
        $query = "SELECT medicine.id, medicine.doctorVisit_id, medicine.name, medicine.alternateName,
                    medicine.price, medicine.dateOfDoctorVisit, medicine.pharmacy_id, medicine.notes
                    FROM medicine
                    INNER JOIN doctorvisit ON ( medicine.doctorVisit_id = doctorvisit.id )
                    INNER JOIN patient ON ( patient.id = doctorvisit.patient_id )
                    WHERE medicine.price IS NULL
                    AND medicine.pharmacy_id IS NULL
                    AND patient.id = :patient_id
                    AND medicine.name = :medicineName LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patient_id', $patient_id);
        $sth->bindParam('medicineName', $_GET['name']);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function saveChangesOnMedicine($pharmacy_id) {
        $patient = $this->getPatientInfo($_POST['nationalNumber']);
        $patientID = $patient['id'];
        include_once '../helpers/helper.php';
        $price = helper::sanitize($_POST['price']);
        $secondName = helper::sanitize($_POST['secondName']);
        //check if not expire date
        $ifNotExpireDate = $this->ifNotExpireDate($patientID);
        if ($ifNotExpireDate == null) {
            return "فشلت الإضافة بسبب انتهاء مدة التأمين السنوية";
        }
        //check if not end period between visit and treatment
        $isEnd = $this->ifEndPeriodBetweenVisitAndTreatment($_POST['dateOfDoctorVisit']);
        if ($isEnd == 0) {
            return "انتهاء الفترة الفاصلة بين وصف النشرة الطبية وصرفها";
        }
        //check if still money for treatment
        $ifExistMoney = $this->ifExistMoney($patientID, $price);
        if ($ifExistMoney == null) {
            return "فشلت الإضافة بسبب نفاذ مبلغ التأمين";
        }
        $date = date("Y-m-d H:i:s");
        $query = "UPDATE medicine SET alternateName = :alternateName, price = :price, pharmacy_id = :pharmacy_id, date = :date, notes = :notes WHERE id = :medicineID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('price', $price);
        $sth->bindParam('alternateName', $secondName);
        $sth->bindParam('pharmacy_id', $pharmacy_id);
        $sth->bindParam('date', $date);
        $sth->bindParam('medicineID', $_POST['medicineID']);
        $sth->bindParam('notes', $_POST['notes']);
        $result = $sth->execute();
        return $result;
    }

    function ifEndPeriodBetweenVisitAndTreatment($dateOfDoctorVisit) {
        $periodBetweenVisitAndTreatment = $this->getRecordFromConfig("Period Between Visit And Treatment");
        if ($periodBetweenVisitAndTreatment == null) {
            return 0;
        }
        //date('Y-m-d H:i:s', strtotime($day . " +".$x." days"))
        $date1 = $dateOfDoctorVisit;
        $date2 = date("Y-m-d H:i:s");
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $allDays = $days + ($months * 30) + ($years * 360);
        //$now = date("Y-m-d H:i:s");
        if ($allDays > $periodBetweenVisitAndTreatment) {
            return 0;
        } else {
            return true;
        }
    }

    function getAllHospitalServixesForUserForDataTables($patient_id) {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('name', 'dateOfDoctorVisit');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "doctorVisit_id";

        /* DB table to use */
        $sTable = "hospitalservice
                    INNER JOIN doctorvisit ON ( hospitalservice.doctorVisit_id = doctorvisit.id )
                    INNER JOIN patient ON ( patient.id = doctorvisit.patient_id )";


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
        //$sWhere = "";
        $sWhere = " WHERE hospitalservice.price IS NULL AND hospitalservice.hospital_id IS NULL AND patient.id = '{$patient_id}'";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            //$sWhere .= ')';
            $sWhere .= ") AND hospitalservice.price IS NULL AND hospitalservice.hospital_id IS NULL AND patient.id = '{$patient_id}'";
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

    function getHospital($patient_id) {
        $query = "SELECT hospitalservice.id, hospitalservice.doctorVisit_id, hospitalservice.name,
                    hospitalservice.price, hospitalservice.dateOfDoctorVisit, hospitalservice.hospital_id, hospitalservice.notes
                    FROM hospitalservice
                    INNER JOIN doctorvisit ON ( hospitalservice.doctorVisit_id = doctorvisit.id )
                    INNER JOIN patient ON ( patient.id = doctorvisit.patient_id )
                    WHERE hospitalservice.price IS NULL
                    AND hospitalservice.hospital_id IS NULL
                    AND patient.id = :patient_id
                    AND hospitalservice.name = :hospitalName LIMIT 1;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patient_id', $patient_id);
        $sth->bindParam('hospitalName', $_GET['name']);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows[0];
        }
    }

    function saveChangesOnHospital($hospital_id) {
        $patient = $this->getPatientInfo($_POST['nationalNumber']);
        $patientID = $patient['id'];
        include_once '../helpers/helper.php';
        $price = helper::sanitize($_POST['price']);
        //check if not expire date
        $ifNotExpireDate = $this->ifNotExpireDate($patientID);
        if ($ifNotExpireDate == null) {
            return "فشلت الإضافة بسبب انتهاء مدة التأمين السنوية";
        }
        //check if not end period between visit and treatment
        $isEnd = $this->ifEndPeriodBetweenVisitAndTreatment($_POST['dateOfDoctorVisit']);
        if ($isEnd == 0) {
            return "انتهاء الفترة الفاصلة بين وصف النشرة والحصول على الاستشفاء";
        }
        //check if still money for treatment
        $ifExistMoney = $this->ifExistMoney($patientID, $price);
        if ($ifExistMoney == null) {
            return "فشلت الإضافة بسبب نفاذ مبلغ التأمين";
        }
        $date = date("Y-m-d H:i:s");
        $query = "UPDATE hospitalservice SET price = :price, hospital_id = :hospital_id, date = :date, notes = :notes WHERE id = :hospitalID;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('price', $price);
        $sth->bindParam('hospital_id', $hospital_id);
        $sth->bindParam('date', $date);
        $sth->bindParam('hospitalID', $_POST['hospitalID']);
        $sth->bindParam('notes', $_POST['notes']);
        $result = $sth->execute();
        return $result;
    }

    function getAllDoctorVisitMedicines($doctor_id) {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('name', 'dateOfDoctorVisit');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "dateOfDoctorVisit";

        /* DB table to use */
        $sTable = "medicine
                INNER JOIN (SELECT id, date, patient_id, doctor_id FROM doctorvisit WHERE patient_id = '".$_GET['patient_id']."' ORDER BY date DESC LIMIT 1) AS dv
                ON ( medicine.doctorVisit_id = dv.id )
                INNER JOIN patient ON ( patient.id = dv.patient_id )";


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
        //$sWhere = "";
        $sWhere = " WHERE dv.doctor_id = '".$doctor_id."' AND dv.patient_id = '".$_GET['patient_id']."'";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            //$sWhere .= ')';
            $sWhere .= ") AND dv.doctor_id = '".$doctor_id."' AND dv.patient_id = '".$_GET['patient_id']."'";
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

    function getAllDoctorVisitHospitalServiceForDataTables($doctor_id) {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('name', 'dateOfDoctorVisit');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "dateOfDoctorVisit";

        /* DB table to use */
        $sTable = "hospitalservice
                INNER JOIN (SELECT id, date, patient_id, doctor_id FROM doctorvisit WHERE patient_id = '".$_GET['patient_id']."' ORDER BY date DESC LIMIT 1) AS dv ON ( hospitalservice.doctorVisit_id = dv.id )
                INNER JOIN patient ON ( patient.id = dv.patient_id )";


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
        //$sWhere = "";
        $sWhere = " WHERE dv.doctor_id = '".$doctor_id."' AND dv.patient_id = '".$_GET['patient_id']."'";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            //$sWhere .= ')';
            $sWhere .= ") AND dv.doctor_id = '".$doctor_id."' AND dv.patient_id = '".$_GET['patient_id']."'";
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

    function getMedicinesForPatient($doctor_id, $patient_id) {
        $query = "SELECT name, notes FROM medicine
                INNER JOIN (SELECT id, date, patient_id, doctor_id FROM doctorvisit WHERE patient_id = :patient_id ORDER BY date DESC LIMIT 1) AS dv
                ON ( medicine.doctorVisit_id = dv.id )
                INNER JOIN patient ON ( patient.id = dv.patient_id )
                WHERE dv.doctor_id = :doctor_id AND dv.patient_id = :patient_id;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('doctor_id', $doctor_id);
        $sth->bindParam('patient_id', $patient_id);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows;
        }
    }

    function getHospitalServicesForPatient($doctor_id, $patient_id) {
        $query = "SELECT name, notes FROM hospitalservice
                INNER JOIN (SELECT id, date, patient_id, doctor_id FROM doctorvisit WHERE patient_id = :patient_id ORDER BY date DESC LIMIT 1) AS dv
                ON ( hospitalservice.doctorVisit_id = dv.id )
                INNER JOIN patient ON ( patient.id = dv.patient_id )
                WHERE dv.doctor_id = :doctor_id AND dv.patient_id = :patient_id;";
        $sth = $this->db->prepare($query);
        $sth->bindParam('doctor_id', $doctor_id);
        $sth->bindParam('patient_id', $patient_id);
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows;
        }
    }

    function getAllPatientDoctorVisitsPaymentForDataTables($patient_id, $from, $to) {
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('firstName', 'lastName', 'date', 'notes', 'price');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";

        /* DB table to use */
        $sTable = "doctorvisit
                INNER JOIN doctor ON (doctorvisit.doctor_id = doctor.user_id)";


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
        //$sWhere = "";
        if(isset($from) && $from != "" && isset($to) && $to != "" ) {
            $sWhere = " WHERE doctorvisit.patient_id = '{$patient_id}' AND doctorvisit.date BETWEEN '{$from}' AND '{$to}'";
        } else {
            $sWhere = " WHERE doctorvisit.patient_id = '{$patient_id}' ";
        }
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            //$sWhere .= ')';
            if(isset($from) && $from != "" && isset($to) && $to != "" ) {
            $sWhere .= ") AND doctorvisit.patient_id = '{$patient_id}' AND doctorvisit.date BETWEEN '{$from}' AND '{$to}'";
            } else {
                $sWhere .= ") AND doctorvisit.patient_id = '{$patient_id}' ";
            }
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

    function getAllPatientMedicinePaymentForDataTables($patient_id, $from, $to)
    {
        if(isset ($from) && $from!="" && isset ($to) && $to!="")
        {
            $query = "SELECT pharmacy.name, medicine.date, medicine.notes, medicine.price FROM medicine
                    INNER JOIN pharmacy ON (medicine.pharmacy_id = pharmacy.user_id)
                    INNER JOIN doctorvisit ON (medicine.doctorVisit_id = doctorvisit.id)
                    WHERE doctorvisit.patient_id = :patient_id AND medicine.date BETWEEN :from AND :to;";
        
            $sth = $this->db->prepare($query);
            $sth->bindParam('patient_id', $patient_id);
            $sth->bindParam('from', $from);
            $sth->bindParam('to', $to);
        }
        else
        {
            $query = "SELECT pharmacy.name, medicine.date, medicine.notes, medicine.price FROM medicine
                    INNER JOIN pharmacy ON (medicine.pharmacy_id = pharmacy.user_id)
                    INNER JOIN doctorvisit ON (medicine.doctorVisit_id = doctorvisit.id)
                    WHERE doctorvisit.patient_id = :patient_id;";
            $sth = $this->db->prepare($query);
            $sth->bindParam('patient_id', $patient_id);
        }
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows;
        }
    }

    function getAllPatientHospitalServicePaymentForDataTables($patient_id, $from, $to)
    {
        if(isset ($from) && $from!="" && isset ($to) && $to!="")
        {
            $query = "SELECT hospital.name, hospitalservice.date, hospitalservice.notes, hospitalservice.price FROM hospitalservice
                INNER JOIN hospital ON (hospitalservice.hospital_id = hospital.user_id)
                INNER JOIN doctorvisit ON (hospitalservice.doctorVisit_id = doctorvisit.id)
                WHERE doctorvisit.patient_id = :patient_id AND hospitalservice.date BETWEEN :from AND :to;";
            $sth = $this->db->prepare($query);
            $sth->bindParam('patient_id', $patient_id);
            $sth->bindParam('from', $from);
            $sth->bindParam('to', $to);
        }
        else
        {
            $query = "SELECT hospital.name, hospitalservice.date, hospitalservice.notes, hospitalservice.price FROM hospitalservice
                INNER JOIN hospital ON (hospitalservice.hospital_id = hospital.user_id)
                INNER JOIN doctorvisit ON (hospitalservice.doctorVisit_id = doctorvisit.id)
                WHERE doctorvisit.patient_id = :patient_id;";
            $sth = $this->db->prepare($query);
            $sth->bindParam('patient_id', $patient_id);
        }
        $sth->execute();
        if ($sth->rowCount() == 0) {
            return null;
        } else {
            $rows = $sth->fetchAll();
            return $rows;;
        }
    }
    
}

?>
