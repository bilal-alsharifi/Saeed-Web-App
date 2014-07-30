
<?php
include_once ('../libraries/Controller.php');

class Report extends Controller {

    function index() {
        include_once '../config/config.php';
        include_once('../libraries/PHPMailer_5.2.2/class.phpmailer.php');
        $this->loadModel('Report_model');
        $model = new Report_model();
        $patients = $model->getAllPatients();
        foreach ($patients as $p) {
            $patientID = $p['id'];
            $patientEmail = $p['email'];
            
            //build reports
            $data['doctorVisits'] = $model->getPatientDoctorVisits($patientID);
            $data['hospitalServices'] = $model->getPatientHospitalServices($patientID);
            $data['medicines'] = $model->getPatientMedicines($patientID);
            $data['fileName'] = '../reportFiles/' . $patientID . '_' . date("Y-m-d");
            $this->createAllFiles($data);
            //
            
            
            //send mail with Attachments
            if ($patientEmail != null) {
                $mail = new PHPMailer();
                $mail->AddReplyTo(SUPPORT_MAIL, "Saeed for insurance");
                $mail->SetFrom(SUPPORT_MAIL, 'Saeed for insurance');
                $mail->AddAddress($patientEmail);
                $mail->Subject = "Saeed for insurance - monthly report";
                $mail->MsgHTML('لمشاهدة التقارير يرجى تحميل المرفقات');
                $mail->AddAttachment($data['fileName'].'.xlsx');  
                $mail->AddAttachment($data['fileName'].'.xml');
                $mail->AddAttachment($data['fileName'].'.pdf');
                $mail->Send();
            }
            //
        }
    }

    private function createAllFiles($data) {
        $this->createExcelFile($data);
        $this->createXMLFile($data);
        $this->createPDFFile($data);
    }

    private function createExcelFile($data) {
        /** PHPExcel */
        include_once '../libraries/PHPExcel_1.7.8/Classes/PHPExcel.php';

        /** PHPExcel_Writer_Excel2007 */
        include_once '../libraries/PHPExcel_1.7.8/Classes/PHPExcel/Writer/Excel2007.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // add doctor visits
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $objPHPExcel->getActiveSheet()->setTitle('المعاينات');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'اسم الطبيب');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'التاريخ');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'الملاحظات');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'السعر');

        for ($i = 0; $i < count($data['doctorVisits']); $i++) {
            $cellIndex = $i + 2;
            $doctorName = $data['doctorVisits'][$i]['firstName'] . ' ' . $data['doctorVisits'][$i]['lastName'];
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $cellIndex, $doctorName);
            $date = $data['doctorVisits'][$i]['date'];
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $cellIndex, $date);
            $notes = $data['doctorVisits'][$i]['notes'];
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $cellIndex, $notes);
            $price = $data['doctorVisits'][$i]['price'];
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $cellIndex, $price);
        }


        // add hospital services
        $objPHPExcel->createSheet(NULL, 1);
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $objPHPExcel->getActiveSheet()->setTitle('الاستشفاءات');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'اسم المشفى');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'التاريخ');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'الملاحظات');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'السعر');

        for ($i = 0; $i < count($data['hospitalServices']); $i++) {
            $cellIndex = $i + 2;
            $name = $data['hospitalServices'][$i]['name'];
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $cellIndex, $name);
            $date = $data['hospitalServices'][$i]['date'];
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $cellIndex, $date);
            $notes = $data['hospitalServices'][$i]['notes'];
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $cellIndex, $notes);
            $price = $data['hospitalServices'][$i]['price'];
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $cellIndex, $price);
        }

        // add medicines
        $objPHPExcel->createSheet(NULL, 2);
        $objPHPExcel->setActiveSheetIndex(2);
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $objPHPExcel->getActiveSheet()->setTitle('الأدوية');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'اسم الصيدلية');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'التاريخ');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'الملاحظات');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'السعر');

        for ($i = 0; $i < count($data['medicines']); $i++) {
            $cellIndex = $i + 2;
            $name = $data['medicines'][$i]['name'];
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $cellIndex, $name);
            $date = $data['medicines'][$i]['date'];
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $cellIndex, $date);
            $notes = $data['medicines'][$i]['notes'];
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $cellIndex, $notes);
            $price = $data['medicines'][$i]['price'];
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $cellIndex, $price);
        }

        // Save Excel 2007 file
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($data['fileName'] . '.xlsx');
    }

    private function createXMLFile($data) {
        $xml = '<items>';
        
        // add doctor visits
        $xml .= '<doctorVistits>';
        for ($i = 0; $i < count($data['doctorVisits']); $i++) {
            $xml .= '<doctorVistit>';
            $xml .= '<name>'.$data['doctorVisits'][$i]['firstName'] . ' ' . $data['doctorVisits'][$i]['lastName'].'</name>';
            $xml .= '<date>'.$data['doctorVisits'][$i]['date'].'</date>';
            $xml .= '<notes>'.$data['doctorVisits'][$i]['notes'].'</notes>';
            $xml .= '<price>'.$data['doctorVisits'][$i]['price'].'</price>';
            $xml .= '</doctorVistit>';
        }
        $xml .= '</doctorVistits>';
        
        // add hospitalServices
        $xml .= '<hostpitalServices>';
        for ($i = 0; $i < count($data['hospitalServices']); $i++) {
            $xml .= '<hostpitalService>';
            $xml .= '<name>' . $data['hospitalServices'][$i]['name'] . '</name>';
            $xml .= '<date>' . $data['hospitalServices'][$i]['date'] . '</date>';
            $xml .= '<notes>' . $data['hospitalServices'][$i]['notes'] . '</notes>';
            $xml .= '<price>' . $data['hospitalServices'][$i]['price'] . '</price>';
            $xml .= '</hostpitalService>';
        }
        $xml .= '</hostpitalServices>';
        
        // add hospitalServices
        $xml .= '<medicines>';
        for ($i = 0; $i < count($data['hospitalServices']); $i++) {
            $xml .= '<medicine>';
            $xml .= '<name>' . $data['medicines'][$i]['name'] . '</name>';
            $xml .= '<date>' . $data['medicines'][$i]['date'] . '</date>';
            $xml .= '<notes>' . $data['medicines'][$i]['notes'] . '</notes>';
            $xml .= '<price>' . $data['medicines'][$i]['price'] . '</price>';
            $xml .= '</medicine>';
        }
        $xml .= '</medicines>';
        
        $xml .= '</items>';
        file_put_contents ( $data['fileName'] . '.xml', $xml);
    }
    
    private function createPDFFile($data) {
        include_once '../libraries/PDFLib/PDFLib.php';
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';
        $pdf->setLanguageArray($lg);
        $pdf->SetFont('aefurat', '', 10);
        $pdf->AddPage();
        $htmlcontent = 'تقرير المعاينات';
        $pdf->Ln();
        $pdf->WriteHTML($htmlcontent, true, 0, true, 0);
        $header = array('اسم الطبيب', 'تاريخ الزيارة', 'الكلفة', 'ملاحظات');
        $pdf->ColoredTableForDoctorVisit($header,$data['doctorVisits']);
        $pdf->Ln();
        $pdf->Ln();
        $htmlcontent = 'تقرير المشافي';
        $pdf->Ln();
        $pdf->WriteHTML($htmlcontent, true, 0, true, 0);
        $header = array('اسم المشفى', 'تاريخ الزيارة', 'الكلفة', 'ملاحظات');
        $pdf->ColoredTableForMedicine($header,$data['medicines']);
        $pdf->Ln();
        $pdf->Ln();
        $htmlcontent = 'تقرير الصيدليات';
        $pdf->Ln();
        $pdf->WriteHTML($htmlcontent, true, 0, true, 0);
        $header = array('اسم الصيدلية', 'تاريخ الصرف', 'الكلفة', 'ملاحظات');
        $pdf->ColoredTableForHospital($header,$data['hospitalServices']);
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $htmlcontent = 'مع تحيات شركة ساعد للتأمين :)';
        $pdf->WriteHTML($htmlcontent, true, 0, true, 0);

        $pdf->Output($data['fileName'] . '.pdf', 'F');
    }
}

new Report();
?>
