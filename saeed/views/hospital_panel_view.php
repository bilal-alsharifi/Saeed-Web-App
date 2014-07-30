<h1>لوحة تحكم المشفى</h1>
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
<div id="hospitalDatatable">
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="hospitalTable">
    </table>
</div>
<div id="addHospitalDiv" title="إضافة خدمة استشفاء" style="display: none">
    <div id="hospitalInfo">
        <input type="hidden" id="hospitalID" name="hospitalID" value="">
        <input type="hidden" id="doctorVisitID" name="doctorVisitID" value="">
        <input type="hidden" id="dateOfDoctorVisit" name="dateOfDoctorVisit" value="">
        <table>
            <tr>
            <label for="name" class="title"><td>الاسم</td></label>
            <td>
                <input type="text" id="name" name="name" disabled/>
            </td>
            </tr>
            <tr>
            <label for="price" class="title"><td>السعر</td></label>
            <td>
                <input type="text" id="price" name="price"/>
            </td>
            </tr>
            <tr>
            <label for="notes" class="title"><td>ملاحظات</td></label>
            <td>
                <textarea id="notes" name="notes"></textarea>
            </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" id="submit2" name="submit2" value="حفظ"/>
                </td>
            </tr>
        </table>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#submit, #submit2').button();
        $(document).tooltip();
    });
    $('#submit').click(function(){
        var nationalNumber = decodeURIComponent($('#nationalnumber').val());
        if(nationalNumber != "")
        {
            loadUsersTable(nationalNumber);
            saveMedicine();
        }
        return false;
    });

    function loadUsersTable(nationalNumber)
    {
        $('#hospitalTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            //for destroy and rebuiled data
            "bDestroy": true,
            //for not auto width
            "bAutoWidth": false,
            //"bRetrieve": true,
            "sAjaxSource": "../controllers/HospitalPanel.php?function=getAllHospitalServixesForUserForDataTables&nationalNumber=" + nationalNumber,
            // by default order by this column
            "aaSorting": [[ 0, "desc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aoColumns": [
                { "sTitle": "اسم الاستشفاء" },
                { "sTitle": "تاريخ وصف الاستشفاء" },
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                // convert names to link
                var name = $('td:eq(0)', nRow);
                name.html( '<a class=addHospital href="">' + name.html() + '</a>' );
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

    function datatablesReady()
    {
        var nationalNumber = decodeURIComponent($('#nationalnumber').val());
        var hospital;
        $('.addHospital').click(function(){
            var name = decodeURIComponent($(this).html());
            if(nationalNumber != "")
            {
                $.ajax({
                    type: "GET",
                    url: "../controllers/HospitalPanel.php?function=getHospitalForAjax",
                    data: {
                        name:name,
                        nationalNumber:nationalNumber
                    },
                    success: function(msg)
                    {
                        hospital = eval('('+ msg + ')');
                        fillEditUserFileds(hospital);
                    }
                });

                $( "#addHospitalDiv" ).dialog({
                    modal: true,
                    draggable : true,
                    closeOnEscape: true,
                    resizable : true,
                    show: "blind",
                    hide: "explode"
                });
            }
            return false;
        });
    }

    function fillEditUserFileds(hospital)
    {
        $('#hospitalID').val(hospital.id);
        $('#doctorVisitID').val(hospital.doctorVisit_id);
        $('#name').val(hospital.name);
        $('#price').val(hospital.price);
        $('#dateOfDoctorVisit').val(hospital.dateOfDoctorVisit);
        $('#notes').val(hospital.notes);
    }

    function saveMedicine()
    {
        $('#submit2').click(function(){
            var nationalNumber = decodeURIComponent($('#nationalnumber').val());
            var price = decodeURIComponent($('#price').val());
            var hospitalID = decodeURIComponent($('#hospitalID').val());
            var dateOfDoctorVisit = decodeURIComponent($('#dateOfDoctorVisit').val());
            var notes = decodeURIComponent($('#notes').val());
            if(price != "" && nationalNumber != "")
            {
                $.ajax({
                    type: "POST",
                    url: "../controllers/HospitalPanel.php?function=saveChangesOnHospital",
                    data: {
                        price:price,
                        hospitalID:hospitalID,
                        dateOfDoctorVisit:dateOfDoctorVisit,
                        nationalNumber:nationalNumber,
                        notes:notes
                    },
                    success: function(msg)
                    {
                        updateTips(msg);
                    }
                });
                loadUsersTable(nationalNumber);
            }
            $( "#addHospitalDiv" ).dialog('close');
            return false;
        });
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

</script>