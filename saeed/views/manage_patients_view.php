<a id="newPatient" href="">مريض جديد</a>

<div id="newPatientDialog" title="مريض جديد" style="display: none">
    <form action="../controllers/ManagePatients.php?function=addPatient" method="post">

        <table>
            <tr>
                <td></td>
                <td>
                    <ul id="validateTips">
                    </ul>
                </td>
            </tr>
            <tr>
                <td><label for="firstName" class="title">الشركة</label></td>
                <td>                    
                    <select id="company" name="company">
                        <?php
                        foreach ($data['companies'] as $company) {
                            echo "<option value='{$company['id']}'>{$company['name']}</option>";
                        }
                        ?>    
                    </select> </td>
            </tr>
            <tr>
                <td><label for="firstName" class="title">الاسم الأول</label></td>
                <td><input type="text" id="firstName" name="firstName"/></td>
            </tr>
            <tr>
                <td> <label for="lastName" class="title">الاسم الثاني</label></td>
                <td><input type="text" id="lastName" name="lastName"/></td>
            </tr>
            <tr>
                <td><label for="gender" class="title">الجنس</label> </td>
                <td><div id="gender">
                        <input type="radio" id="female" name="gender" value="female"/>
                        <label for="female">أنثى</label>
                        <input type="radio" id="male" name="gender" value="male" checked="checked" />
                        <label for="male">ذكر</label>
                    </div></td>
            </tr>
            <tr>
                <td><label for="nationalNumber" class="title">الرقم الوطني</label></td>
                <td><input type="text" id="nationalNumber" name="nationalNumber"/></td>
            </tr>
            <tr>
                <td><label for="email" class="title">البريد الالكتروني</label></td>
                <td><input type="text" id="email" name="email"/></td>
            </tr>
            <tr>
                <td><label for="mobile" class="title">الجوال</label></td>
                <td><input type="text" id="mobile" name="mobile"/>
            </tr>
            <tr>
                <td><label for="phone" class="title">الهاتف</label></td>
                <td><input type="text" id="phone" name="phone"/></td>
            </tr>
            <tr>
                <td><label for="address" class="title">العنوان</label></td>
                <td><input type="text" id="address" name="address"/></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" id="submit" name="submit" value="إضافة"/></td>
            </tr>
        </table>

    </form> 
</div>

<div id="editPatientDialog" title="تعديل مريض" style="display: none">
    <form action="../controllers/ManagePatients.php?function=editPatient" method="post">

        <table>
            <tr>
                <td></td>
                <td><ul id="validateTips2">
                    </ul>
                    <input type="hidden" id="patientID2" name="patientID2" value="">
                </td>
            </tr>
            <tr>
                <td><label for="firstName" class="title">الشركة</label></td>
                <td>                    
                    <select id="company2" name="company2">
                        <?php
                        foreach ($data['companies'] as $company) {
                            echo "<option id='c{$company['id']}' value='{$company['id']}'>{$company['name']}</option>";
                        }
                        ?>    
                    </select> </td>
            </tr>
            <tr>
                <td><label for="firstName2" class="title">الاسم الأول</label></td>
                <td><input type="text" id="firstName2" name="firstName2"/></td>
            </tr>
            <tr> 
                <td><label for="lastName2" class="title">الاسم الثاني</label></td>
                <td><input type="text" id="lastName2" name="lastName2"/></td>
            </tr>
            <tr>
                <td><label for="gender2" class="title">الجنس</label></td>
                <td> <div id="gender2">
                        <input type="radio" id="female2" value="female" name="gender2" /><label for="female2">أنثى</label>
                        <input type="radio" id="male2" value="male" name="gender2" /><label for="male2">ذكر</label>
                    </div></td>
            </tr>
            <tr>
                <td><label for="nationalNumber2" class="title">الرقم الوطني</label></td>
                <td><input type="text" id="nationalNumber2" name="nationalNumber2"/></td>
            </tr>
            <tr>
                <td><label for="email2" class="title">البريد الالكتروني</label></td>
                <td><input type="text" id="email2" name="email2"/></td>
            </tr>
            <tr>
                <td><label for="mobile2" class="title">الجوال</label></td>
                <td><input type="text" id="mobile2" name="mobile2"/></td>
            </tr>
            <tr>
                <td><label for="phone2" class="title">الهاتف</label></td>
                <td><input type="text" id="phone2" name="phone2"/></td>
            </tr>
            <tr>
                <td><label for="address2" class="title">العنوان</label></td>
                <td><input type="text" id="address2" name="address2"/></td>

            </tr>
            <tr>
                <td><input type="submit" id="submit2" name="submit2" value="تعديل"/></td>
                <td>

                    <a id="renewAccount" href="">تجديد الاشتراك</a>
                    <a id="deletePatient" href="">حذف المريض</a>   
                </td>
            </tr>
        </table>
    </form> 
</div>


<p id="msg"><?php echo $data['msg']; ?></p>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="patientsTable">
</table>


<script>
    $(document).ready(function(){

        var errors = new Array();    
        errors['firstName'] = "";
        errors['lastName'] = "";
        errors['nationalNumber'] = "";
    
        $(document).tooltip();
        loadPatientsTable(); 
 
        $('#newPatientDialog input').focus(function(){
            checkAll(errors);    
        });
        $('#newPatientDialog input').blur(function(){
            checkAll(errors);    
        });
        //convert inputs to jquery ui style
        $( "#gender, #gender2" ).buttonset();
        $('#newPatient, #deletePatient, #submit2, #renewAccount').button();
        $('#newPatient').click(function(){
            $( "#newPatientDialog" ).dialog({
                width : 400,
                modal: true,
                draggable : true,
                closeOnEscape: true,
                resizable : true,
                show: "blind",
                hide: "explode"
            });
            return false;
        }); 
        $('#submit').button().click(function()
        {
            var result = checkAll(errors);
            return result;
        });
   
    });
    function datatablesReady()
    {
        var patient;
        $('.editPatient').click(function(){  
            var patientID = decodeURIComponent($(this).html());
            $.ajax({
                type: "GET",
                url: "../controllers/ManagePatients.php?function=getPatientForAjax",
                data: {
                    patientID:patientID
                },
                success: function(msg)
                {
                    patient = eval('('+ msg + ')');
                    fillEditPatientFileds(patient);               
                }  
            }); 
        
            $( "#editPatientDialog" ).dialog({
                width : 400,
                modal: true,
                draggable : true,
                closeOnEscape: true,
                resizable : true,
                show: "blind",
                hide: "explode"
            });
         
            return false;
        }); 
    }

    function fillEditPatientFileds(patient)
    {    
        $('#deletePatient').attr("href", "../controllers/ManagePatients.php?function=deletePatient&patientID=" + patient.id);
        $('#renewAccount').attr("href", "../controllers/ManagePatients.php?function=renewAccount&patientID=" + patient.id);
        $('#patientID2').val(patient.id);
        $('#firstName2').val(patient.firstName);
        $('#lastName2').val(patient.lastName);
        $('#gender2 #' + patient.gender + '2').attr('checked', "true");
        $('#gender2').buttonset("refresh"); 
        $('#nationalNumber2').val(patient.nationalNumber);
        $('#email2').val(patient.email);
        $('#mobile2').val(patient.mobile);
        $('#phone2').val(patient.phone);
        $('#address2').val(patient.address);
        $('#company option').removeAttr("selected");    
        $('#c' + patient.company_id).attr('selected', 'selected');   
    }
    function loadPatientsTable() 
    {
        $('#patientsTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "../controllers/ManagePatients.php?function=getAllPatientsForDataTables",
            // by default order by this column
            "aaSorting": [[ 0, "asc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",       
            "aoColumns": [
                { "sTitle": "الرقم التاميني" },
                { "sTitle": "الاسم الأول" },
                { "sTitle": "الاسم الثاني" },
                { "sTitle": "الجنس" },
                { "sTitle": "الرقم الوطني" },
                { "sTitle": "العنوان"},
                { "sTitle": "تاريخ الانتهاء"},
                { "sTitle": "عدد الزيارات" },
                { "sTitle": "المبلغ التأميني المستهلك" },
                
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1, 2, 3, 4, 5, 6, 7, 8]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                // convert emails to link
                var patientIDCell = $('td:eq(0)', nRow);
                patientIDCell.html( '<a class=editPatient title="تعديل" href="">' + patientIDCell.html() + '</a>' );
                return nRow;
            },  
            "fnInitComplete": function(oSettings, json) {
                datatablesReady();
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

    //validaters for new user
    function updateTips(errors) 
    {
        var tips = $( "#validateTips" );
        tips.html("");
        for(var e in errors)
        {
            if (errors[e].length > 0)
            {
                tips.append("<li>" + errors[e] + "</li>");
            }
        }
        tips.addClass( "ui-state-highlight" );
        setTimeout(function() 
        {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
    }
    function checkAll(errors)
    {
        checkFirstName($('#firstName').val(), errors);
        checkLastName($('#lastName').val(), errors);
        checkNationalNumber($('#nationalNumber').val(), errors);
        for (var e in errors)
        {
            if(errors[e].length > 0)
            {
                return false;
            }
        }
        return true;
    }
    
    function checkFirstName(firstName, errors)
    {
        if (firstName.length < 3)
        {
            errors['firstName'] = 'اسم المريض  قصير جدا';        
        }
        else
        {
            errors['firstName'] = "";
        }
        updateTips(errors);
    }


    function checkLastName(lastName, errors)
    {
        if (lastName.length < 3)
        {
            errors['lastName'] = 'اسم المريض  الثاني قصير جدا';        
        }
        else
        {
            errors['lastName'] = "";
        }
        updateTips(errors);
    }
    
    function checkNationalNumber(nationalNumber, errors)
    {
        if (nationalNumber.length < 3)
        {
            errors['nationalNumber'] = 'الرقم الوطني قصير جدا';        
        }
        else
        {
            errors['nationalNumber'] = "";
        }
        updateTips(errors);
    }
        

</script>
