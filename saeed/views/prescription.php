<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <title>طباعة</title>
    </head>
    <body>
        <div id="prescriptionHeader" >
            <h1>شركة ساعد للتأمينات</h1>
            <h1>___________________</h1>
        </div>
        <div id="patientInfo">
            <table>
                <tr>
                    <td class="odd">
                        <h2>اسم المريض:</h2>
                    </td>
                    <td class="even">
                        <?php echo $data['name']; ?>
                    </td>
                    <td class="odd">
                        <h2>الجنس:</h2>
                    </td>
                    <td class="even">
                        <?php echo $data['gender']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="odd">
                        <h2>تاريخ الزيارة:</h2>
                    </td>
                    <td class="even">
                        <?php echo $data['date']; ?>
                    </td>
                    <td class="odd">
                        <h2>العنوان:</h2>
                    </td>
                    <td class="even">
                        <?php echo $data['address']; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div id="dovtorVisitInfo">
            <h1>_____________________________________________</h1>
            <br />
            <br />
            <table style="font-size: 26px">
                <tr>
                    <td>
                        <h4>الأدوية المقترحة:</h4>
                    </td>
                    <td></td>
                </tr>
                <?php
                    $visitMedicines = $data['visitMedicines'];
                    $even = 'even';
                    if(isset ($visitMedicines) && $visitMedicines!="")
                    {
                        foreach ($visitMedicines as $visitMedicine) {
                            echo "<tr>";
                            echo "<td class=".$even.">";
                            echo $visitMedicine['name'];
                            echo "</td>";
                            echo "<td class=".$even.">";
                            echo ": ".$visitMedicine['notes'];
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                ?>
                <tr>
                    <td>
                        <h4>الاستشفاء المقترح:</h4>
                    </td>
                    <td></td>
                </tr>
                <?php
                    $visitHospitalServices = $data['visitHospitalServices'];
                    $even = 'even';
                    if(isset ($visitMedicines) && $visitMedicines!="")
                    {
                        foreach ($visitHospitalServices as $visitHospitalService) {
                            echo "<tr>";
                            echo "<td class=".$even.">";
                            echo $visitHospitalService['name'];
                            echo "</td>";
                            echo "<td class=".$even.">";
                            echo ": ".$visitHospitalService['notes'];
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                ?>
            </table>
            <br />
            <br />
            <h1>_____________________________________________</h1>
        </div>
        <div id="doctorInfo">
            <table>
                <tr>
                    <td class="odd">
                        <h2>اسم الطبيب:</h2>
                    </td>
                    <td class="even">
                        <?php echo $data['doctorName']; ?>
                    </td>
                    <td class="odd">
                        <h2>التوقيع:</h2>
                    </td>
                    <td class="even">

                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>