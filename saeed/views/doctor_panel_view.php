<h1>لوحة تحكم الطبيب</h1>
<div id="nationalnum">
    <label class="title">الرقم الوطني</label>
    <input type="text" title="ادخل الرقم الوطني للمريض" id="nationalnumber" name="nationalnumber">
    <br />
    <a id="submit" href="">ادخال</a>
    <ul id="validateTips">
    </ul>
    <br />
</div>
<br />
<br />
<div id="accordion">
    <h3 title="هنا تعرض معلومات المريض">معلومات المريض</h3>
    <div id="patientInfo"  >
        <table id="patientInfoFieldset" style="display: none">
            <tr>
            <label for="id" class="title"><td>الرقم التأميني</td></label>
            <td><input type="text" id="id" name="id" disabled/></td>
            </tr>
            <tr>
            <label for="firstName" class="title"><td>الاسم</td></label>
            <td><input type="text" id="firstName" name="firstName" disabled/></td>
            </tr>
            <tr>
            <label for="lastName" class="title"><td>الكنية</td></label>
            <td><input type="text" id="lastName" name="lastName" disabled/></td>
            </tr>
            <tr>
            <label for="gender" class="title"><td>الجنس</td></label>
            <td><input type="text" id="gender" name="gender" disabled/></td>
            </tr>
            <tr>
            <label for="mobile" class="title"><td>رقم الموبايل</td></label>
            <td><input type="text" id="mobile" name="mobile" disabled/></td>
            </tr>
            <tr>
            <label for="phone" class="title"><td>رقم الهاتف الثابت</td></label>
            <td><input type="text" id="phone" name="phone" disabled/></td>
            </tr>
            <tr>
            <label for="address" class="title"><td>العنوان</td></label>
            <td><input type="text" id="address" name="address" disabled/></td>
            </tr>
            <tr>
            <label for="email" class="title"><td>الإيميل</td></label>
            <td><input type="text" id="email" name="email" disabled/></td>
            </tr>
        </table>
    </div>
    <h3 title="هنا تعرض الزيارات السابقة عند نفس الطبيب" >زيارات سابقة</h3>
    <div>
        <div id="patientDatatable">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="patientTable">
            </table>
        </div>
    </div>
    <h3 title="هنا يقوم الطبيب بإدخال معلومات الكشف الصحي">ادخال معلومات الكشف الصحي</h3>
    <div>
        <div id="enterInfo">
            <form action="" method="post">
                <table>
                    <tr>
                    <label for="cost" class="title"><td>الكلفة</td></label>
                    <td><input type="text" id="price" name="price" /></td>
                    </tr>
                    <tr>
                    <label for="notes" class="title"><td>ملاحظات</td></label>
                    <td><textarea id="notes" name="notes"></textarea></td>
                    </tr>
                    <td></td>
                    <td><input type="submit" id="submit2" name="submit2" value="إضافة"/></td>
                </table>
            </form>
        </div>
    </div>
    <h3 title="هنا يقوم الطبيب بادخال الأدوية في حال اللزوم">ادخال دواء</h3>
    <div>
        <div id="enterMedicineDiv" style="display: none">
            <form action="" method="post">
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="medicineTable">
                </table>
                <table id="enterMedicine" >
                    <tr>
                            <label for="addNewMedicine" class="title"><td>ادخال دواء جديد:</td></label>
                            <td></td>
                    </tr>
                    <tr>
                    <label for="medicineName" class="title"><td>اسم الدواء</td></label>
                    <td><input type="text" id="medicineName" name="medicineName" /></td>
                    </tr>
                    <tr>
                    <label for="medicineNotes" class="title"><td>ملاحظات</td></label>
                    <td><textarea id="medicineNotes" name="medicineNotes"></textarea></td>
                    </tr>
                    <tr>
                    <td></td>
                    <td><input type="submit" id="submit3" name="submit3" value="إضافة"/></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <h3 title="هنا يقوم الطبيب بإدخال استشفاء في حال اللزوم">ادخال استشفاء</h3>
    <div>
        <div id="enterHospitalDiv" style="display: none">
            <form action="" method="post">
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="hospitalServiceTable">
                </table>
                <table id="enterHospital" >
                    <tr>
                            <label for="addNewMedicine" class="title"><td>ادخال استشفاء جديد:</td></label>
                            <td></td>
                    </tr>
                    <tr>
                    <label for="hospitalService" class="title"><td>خدمة مشفى</td></label>
                    <td><input type="text" id="hospitalService" name="hospitalService" /></td>
                    </tr>
                    <tr>
                    <label for="hospitalServiceNotes" class="title"><td>ملاحظات</td></label>
                    <td><textarea id="hospitalServiceNotes" name="hospitalServiceNotes"></textarea></td>
                    </tr>
                    <tr>
                    <td></td>
                    <td><input type="submit" id="submit4" name="submit4" value="إضافة"/></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<br/>
<div>
    <a id="print" name="print" ><img src="../images/printer.png" height="40" width="40"/></a>
</div>
<script>
    $(document).ready(function(){
        $('#submit, #submit2, #submit3, #submit4').button();
        $( "#accordion" ).accordion({
            heightStyle: "content"
        });
        $(document).tooltip();
    });
    var patient;
    $('#submit').click(function(){
        $('#enterMedicineDiv').css('display', 'none')
        $('#enterHospitalDiv').css('display', 'none')
        $('#medicineName').val("");
        $('#hospitalService').val("");
        var nationalNumber = decodeURIComponent($('#nationalnumber').val());
        if(nationalNumber != "")
        {
            $.ajax({
                type: "GET",
                url: "../controllers/DoctorPanel.php?function=getPatientForAjax",
                data: {
                    nationalNumber:nationalNumber
                },
                success: function(msg)
                {
                    patient = eval('('+ msg + ')');
                    fillEditUserFileds(patient);
                    loadPatientsTable(patient.id);
                }
            });
            $('#patientInfoFieldset').css('display', 'inline')
        }
        return false;
    });
    $('#submit2').click(function()
    {
        var nationalNumber = decodeURIComponent($('#nationalnumber').val());
        var price = decodeURIComponent($('#price').val());
        var notes = decodeURIComponent($('#notes').val());
        var patientID = decodeURIComponent($('#id').val());
        if(nationalNumber != "" && price != "")
        {
            $.ajax({
                type: "POST",
                url: "../controllers/DoctorPanel.php?function=addDoctorVisit",
                data: {
                    nationalNumber:nationalNumber,
                    price:price,
                    notes:notes
                },
                success: function(msg)
                {
                    loadPatientsTable(patientID);
                    updateTips(msg);
                    print();
                }
            });
            $('#price').val("");
            $('#notes').val("");
            $('#enterMedicineDiv').css('display', 'inline');
            $('#enterHospitalDiv').css('display', 'inline');
        }
        return false;
    });
    $('#submit3').click(function()
    {
        var nationalNumber = decodeURIComponent($('#nationalnumber').val());
        var medicineName = decodeURIComponent($('#medicineName').val());
        var patientID = decodeURIComponent($('#id').val());
        var notes = decodeURIComponent($('#medicineNotes').val());
        if(nationalNumber != "" && medicineName != "")
        {
            $.ajax({
                type: "POST",
                url: "../controllers/DoctorPanel.php?function=addMedicine",
                data: {
                    nationalNumber:nationalNumber,
                    medicineName:medicineName,
                    notes:notes
                },
                success: function(msg)
                {
                    updateTips(msg);
                }
            });
        }
        loadMedicinesTable(patientID);
        $('#medicineName').val("");
        $('#medicineNotes').val("");
        return false;
    });
    $('#submit4').click(function()
    {
        var nationalNumber = decodeURIComponent($('#nationalnumber').val());
        var hospitalService = decodeURIComponent($('#hospitalService').val());
        var patientID = decodeURIComponent($('#id').val());
        var notes = decodeURIComponent($('#hospitalServiceNotes').val());
        if(nationalNumber != "" && hospitalService != "")
        {
            $.ajax({
                type: "POST",
                url: "../controllers/DoctorPanel.php?function=addHospitalService",
                data: {
                    nationalNumber:nationalNumber,
                    hospitalService:hospitalService,
                    notes:notes
                },
                success: function(msg)
                {
                    updateTips(msg);
                }
            });
        }
        loadHospitalServicesTable(patientID);
        $('#hospitalService').val("");
        $('#hospitalServiceNotes').val("");
        return false;
    });

    function fillEditUserFileds(patient)
    {
        $('#id').val(patient.id);
        $('#firstName').val(patient.firstName);
        $('#lastName').val(patient.lastName);
        $('#gender').val(patient.gender);
        $('#mobile').val(patient.mobile);
        $('#phone').val(patient.phone);
        $('#address').val(patient.address);
        $('#email').val(patient.email);
    }

    function loadPatientsTable(patient_id)
    {
        $('#patientTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            //for destroy and rebuiled data
            "bDestroy": true,
            //for not auto width
            "bAutoWidth": false,
            //"bRetrieve": true,
            "sAjaxSource": "../controllers/DoctorPanel.php?function=getAllDoctorVisitsForDataTables&patient_id=" + patient_id,
            // by default order by this column
            "aaSorting": [[ 0, "desc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aoColumns": [
                { "sTitle": "تاريخ الزيارة" },
                { "sTitle": "التكلفة" },
                { "sTitle": "ملاحظات", "sClass": "center" },
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1, 2]
                }],
            "fnInitComplete": function(oSettings, json) {
                //datatablesReady();
            },
            "oLanguage": {
                "sProcessing":   "جاري التحميل...",
                "sLengthMenu":   "أظهر مُدخلات _MENU_",
                "sZeroRecords":  "لم يُعثر على أية سجلات",
                "sInfo":         "إظهار _START_ إلى _END_ من أصل _TOTAL_ مُدخل",
                "sInfoEmpty":    "يعرض 0 إلى 0 من أصل 0 سجلّ",
                "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                "sInfoPostFix":  "",
                "sSearch":       "ابحث:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "الأول",
                    "sPrevious": "السابق",
                    "sNext":     "التالي",
                    "sLast":     "الأخير"
                }
            }
        } );
    }

    function loadMedicinesTable(patient_id)
    {
        $('#medicineTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            //for destroy and rebuiled data
            "bDestroy": true,
            //for not auto width
            "bAutoWidth": false,
            //"bRetrieve": true,
            "sAjaxSource": "../controllers/DoctorPanel.php?function=getAllDoctorVisitMedicinesForDataTables&patient_id=" + patient_id,
            // by default order by this column
            "aaSorting": [[ 0, "desc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aoColumns": [
                { "sTitle": "اسم الدواء" },
                { "sTitle": "تاريخ الإضافة" },
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1]
                }],
            "fnInitComplete": function(oSettings, json) {
                //datatablesReady();
            },
            "oLanguage": {
                "sProcessing":   "جاري التحميل...",
                "sLengthMenu":   "أظهر مُدخلات _MENU_",
                "sZeroRecords":  "لم يُعثر على أية سجلات",
                "sInfo":         "إظهار _START_ إلى _END_ من أصل _TOTAL_ مُدخل",
                "sInfoEmpty":    "يعرض 0 إلى 0 من أصل 0 سجلّ",
                "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                "sInfoPostFix":  "",
                "sSearch":       "ابحث:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "الأول",
                    "sPrevious": "السابق",
                    "sNext":     "التالي",
                    "sLast":     "الأخير"
                }
            }
        } );
    }

    function loadHospitalServicesTable(patient_id)
    {
        $('#hospitalServiceTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            //for destroy and rebuiled data
            "bDestroy": true,
            //for not auto width
            "bAutoWidth": false,
            //"bRetrieve": true,
            "sAjaxSource": "../controllers/DoctorPanel.php?function=getAllDoctorVisitHospitalServiceForDataTables&patient_id=" + patient_id,
            // by default order by this column
            "aaSorting": [[ 0, "desc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aoColumns": [
                { "sTitle": "اسم الاستشفاء" },
                { "sTitle": "تاريخ الإضافة" },
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1]
                }],
            "fnInitComplete": function(oSettings, json) {
                //datatablesReady();
            },
            "oLanguage": {
                "sProcessing":   "جاري التحميل...",
                "sLengthMenu":   "أظهر مُدخلات _MENU_",
                "sZeroRecords":  "لم يُعثر على أية سجلات",
                "sInfo":         "إظهار _START_ إلى _END_ من أصل _TOTAL_ مُدخل",
                "sInfoEmpty":    "يعرض 0 إلى 0 من أصل 0 سجلّ",
                "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                "sInfoPostFix":  "",
                "sSearch":       "ابحث:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "الأول",
                    "sPrevious": "السابق",
                    "sNext":     "التالي",
                    "sLast":     "الأخير"
                }
            }
        } );
    }
    
    function updateTips(msg)
    {
        $('#validateTips').css('display', 'block')
        var tips = $( "#validateTips" );
        tips.html("");
        if (msg.length > 0)
        {
            tips.append("<li>" + msg + "</li>");
        }
        tips.addClass( "ui-state-highlight" );
        setTimeout(function()
        {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
        setTimeout(function()
        {
            $('#validateTips').css('display', 'none')
        }, 5000);
    }

    function print()
    {
        var firstName = decodeURIComponent($('#firstName').val());
        var lastName = decodeURIComponent($('#lastName').val());
        var nationalNumber = decodeURIComponent($('#nationalnumber').val());
        var name = firstName+' '+lastName;
        $('#print').click(function(){
            window.location.href = "../controllers/DoctorPanel.php?function=printPrescription&patientName="+name+"&nationalNumber="+nationalNumber;
        });
    }

</script>