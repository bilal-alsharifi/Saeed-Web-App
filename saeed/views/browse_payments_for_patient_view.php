<h1>استعراض الدفعات الخاصة بمريض</h1>
<div id="nationalnum">
    <table>
        <tr>
        <label class="title"><td>الرقم الوطني</td></label>
        <td>
            <input type="text" title="ادخل الرقم الوطني للمريض" id="nationalnumber" name="nationalnumber">
        </td>
        <td></td>
        <td></td>
        </tr>
        <tr>
        <label class="title"><td>من</td></label>
        <td>
            <input type="text" id="from" name="from" readonly/>
        </td>
        <label class="title"><td>إلى</td></label>
        <td>
            <input type="text" id="to" name="to" readonly/>
        </td>
        </tr>
        <tr>
            <td>
                <a id="submit" href="">ادخال</a>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>
<br />
<br />
<div id="accordion">
    <h3 title="هنا تعرض معلومات المريض">معلومات المريض</h3>
    <div id="patientInfo"  >
        <table id="patientInfoTable" style="display: none">
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
    <h3 title="هنا يعرض السجل التفصيلي من أجل المعاينات">السجل التفصيلي للمعاينات</h3>
    <div>
        <div id="doctorVisitsPayment">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="browsedoctorVisitsPaymentTable">
            </table>
        </div>
    </div>
    <h3 title="هنا يعرض السجل التفصيلي من أجل الأدوية">السجل التفصيلي للأدوية</h3>
    <div>
        <div id="medicinePayment">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="browsemedicinePaymentTable">
            </table>
        </div>
    </div>
    <h3 title="هنا يعرض السجل التفصيلي من أجل الاستشفاءات">السجل التفصيلي للاستشفاءات</h3>
    <div>
        <div id="hospitalServicePayment">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="browsehospitalServicePaymentTable">
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#submit').button();
        $(document).tooltip();
        $('#from, #to').datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true
        });
        $('#accordion').accordion({
            heightStyle: "content"
        });
        $('#tabs').tabs({
            collapsible: true
        });
    });
    $('#submit').click(function(){
        var nationalNumber = decodeURIComponent($('#nationalnumber').val());
        var from = decodeURIComponent($('#from').val());
        var to = decodeURIComponent($('#to').val());
        var patient;
        if(nationalNumber != "")
        {
            $.ajax({
                type: "GET",
                url: "../controllers/BrowsePaymentsForPatient.php?function=getPatientForAjax",
                data: {
                    nationalNumber:nationalNumber
                },
                success: function(msg)
                {
                    patient = eval('('+ msg + ')');
                    fillEditUserFileds(patient);
                    loadDoctorVisitsPaymentTable(nationalNumber, from, to);
                    loadMedicinePaymentTable(nationalNumber, from, to);
                    loadHospitalServicePaymentTableTable(nationalNumber, from, to);
                }
            });
            $('#patientInfoTable').css('display', 'inline')
        }
        $('#browsemedicinePaymentTable tbody').empty();
        $('#browsehospitalServicePaymentTable tbody').empty();
        return false;
    });
    
    function loadDoctorVisitsPaymentTable(nationalNumber, from, to)
    {
        $('#browsedoctorVisitsPaymentTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            //for destroy and rebuiled data
            "bDestroy": true,
            //for not auto width
            "bAutoWidth": false,
            //"bRetrieve": true,
            "sAjaxSource": "../controllers/BrowsePaymentsForPatient.php?function=getAllPatientDoctorVisitsPaymentForDataTables&nationalNumber=" + nationalNumber
                + "&from=" + from + "&to=" + to,
            // by default order by this column
            "aaSorting": [[ 0, "desc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aoColumns": [
                { "sTitle": "اسم الطبيب" },
                { "sTitle": "الكنية" },
                { "sTitle": "تاريخ المعاينة" },
                { "sTitle": "الملاحظات" },
                { "sTitle": "الكلفة" },
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1 ,2 ,3 ,4]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            },
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

    function loadMedicinePaymentTable(nationalNumber, from, to)
    {
        $('#browsemedicinePaymentTable').dataTable(
        {
            "bProcessing": true,
            //"bServerSide": true,
            //for destroy and rebuiled data
            "bDestroy": true,
            //for not auto width
            "bAutoWidth": false,
            "bRetrieve": true,
            //            "sAjaxSource": "../controllers/BrowsePaymentsForPatient.php?function=getAllPatientMedicinePaymentForDataTables&nationalNumber=" + nationalNumber
            //                + "&from=" + from + "&to=" + to,
            // by default order by this column
            "aaSorting": [[ 0, "desc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aoColumns": [
                { "sTitle": "اسم الصيدلية" },
                { "sTitle": "تاريخ الصرف" },
                { "sTitle": "الملاحظات" },
                { "sTitle": "الكلفة" },
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1 ,2 ,3]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            },
            "fnInitComplete": function(oSettings, json) {
                //datatablesReady();
            },
            "oLanguage": {
                "sProcessing":   "جاري التحميل...",
                "sLengthMenu":   "أظهر مُدخلات _MENU_",
                "sZeroRecords":  "",
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
        }
    );
        $('#browsemedicinePaymentTable tbody').empty();

        var PatientMedicinePayments;
        $.ajax({
            type: "GET",
            url: "../controllers/BrowsePaymentsForPatient.php?function=getAllPatientMedicinePaymentForDataTables",
            data: {
                nationalNumber:nationalNumber,
                from:from,
                to:to
            },
            success: function(msg)
            {
                PatientMedicinePayments = eval('('+ msg + ')');
                fillPatientMedicinePaymentsFileds(PatientMedicinePayments);
            }
        });
    }

    function loadHospitalServicePaymentTableTable(nationalNumber, from, to)
    {
        $('#browsehospitalServicePaymentTable').dataTable(
        {
            "bProcessing": true,
            //"bServerSide": true,
            //for destroy and rebuiled data
            "bDestroy": true,
            //for not auto width
            "bAutoWidth": false,
            "bRetrieve": true,
            //            "sAjaxSource": "../controllers/BrowsePaymentsForPatient.php?function=getAllPatientMedicinePaymentForDataTables&nationalNumber=" + nationalNumber
            //                + "&from=" + from + "&to=" + to,
            // by default order by this column
            "aaSorting": [[ 0, "desc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aoColumns": [
                { "sTitle": "اسم الاستشفاء" },
                { "sTitle": "تاريخ الصرف" },
                { "sTitle": "الملاحظات" },
                { "sTitle": "الكلفة" },
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1 ,2 ,3]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            },
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
        });
        $('#browsehospitalServicePaymentTable tbody').empty();
        var PatientHospitalServicePayments;
        $.ajax({
            type: "GET",
            url: "../controllers/BrowsePaymentsForPatient.php?function=getAllPatientHospitalServicePaymentForDataTables",
            data: {
                nationalNumber:nationalNumber,
                from:from,
                to:to
            },
            success: function(msg)
            {
                PatientHospitalServicePayments = eval('('+ msg + ')');
                fillPatientHospitalServicePaymentsFileds(PatientHospitalServicePayments);
            }
        });
    }

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

    function fillPatientMedicinePaymentsFileds(PatientMedicinePayments)
    {
        var data = PatientMedicinePayments;
        var html;
        var odd = "odd";
        var even = "even"
        for (var i = 0, len = data.length; i < len; ++i) {
            var row;
            if(i % 2 == 0)
            {
                row = even;
            }
            else
            {
                row = odd;
            }
            html = '<tr class="'+row+'">';
            html += '<td class="center">' + data[i].name + '</td>';
            html += '<td class="center">' + data[i].date + '</td>';
            html += '<td class="center">' + data[i].notes + '</td>';
            html += '<td class="center">' + data[i].price + '</td>';
            html += "</tr>";
            $('#browsemedicinePaymentTable').append(html);
        }
    }

    function fillPatientHospitalServicePaymentsFileds(PatientHospitalServicePayments)
    {
        var data = PatientHospitalServicePayments;
        var html;
        var odd = "odd";
        var even = "even"
        for (var i = 0, len = data.length; i < len; ++i) {
            var row;
            if(i % 2 == 0)
            {
                row = even;
            }
            else
            {
                row = odd;
            }
            html = '<tr class="'+row+'">';
            html += '<td class="center">' + data[i].name + '</td>';
            html += '<td class="center">' + data[i].date + '</td>';
            html += '<td class="center">' + data[i].notes + '</td>';
            html += '<td class="center">' + data[i].price + '</td>';
            html += "</tr>";
            $('#browsehospitalServicePaymentTable').append(html);
        }
    }
    
</script>