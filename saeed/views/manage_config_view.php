<h1>ادارة اعدادات النظام</h1>
<br />
<br />
<ul id="validateTips">
</ul>
<div id="configDiv">
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="configTable">
    </table>
</div>
<div id="configData" style="display: none">
    <table>
        <tr>
        <label for="name" class="title"><td>الاسم</td></label>
        <td>
            <input type="text" id="name" name="name" disabled/>
        </td>
        </tr>
        <tr>
        <label for="value" class="title"><td>القيمة</td></label>
        <td>
            <input type="text" id="value" name="value"/>
        </td>
        </tr>
        <tr>
        <label for="desc" class="title"><td>الوصف</td></label>
        <td>
            <textarea id="desc" name="desc"></textarea>
        </td>
        </tr>
        <tr>
        <label for="law" class="title"><td>ملاحظات قانونية</td></label>
        <td>
            <textarea id="law" name="law"></textarea>
        </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" id="submit" name="submit" value="تعديل">
            </td>
        </tr>
    </table>
</div>
<script>
    $(document).ready(function(){
        $('#submit').button();
        loadConfigTable();
    });

    function loadConfigTable()
    {
        $('#configTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            //for destroy and rebuiled data
            "bDestroy": true,
            //for not auto width
            "bAutoWidth": false,
            //"bRetrieve": true,
            "sAjaxSource": "../controllers/ManageConfig.php?function=getAllConfigurationForDataTables",
            // by default order by this column
            "aaSorting": [[ 0, "desc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aoColumns": [
                { "sTitle": "الاسم" },
                { "sTitle": "القيمة" },
                { "sTitle": "الوصف" },
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1, 2]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                // convert names to link
                var name = $('td:eq(0)', nRow);
                name.html( '<a class=editConfig href="">' + name.html() + '</a>' );
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
        var config;
        $('.editConfig').click(function(){
            var configName = decodeURIComponent($(this).html());
            $.ajax({
                type: "POST",
                url: "../controllers/ManageConfig.php?function=getConfigForAjax",
                data: {
                    configName:configName
                },
                success: function(msg)
                {
                    config = eval('('+ msg + ')');
                    fillConfigFileds(config);
                    saveChangesOnConfig();
                }
            });

            $( "#configData" ).dialog({
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
    
    function fillConfigFileds(config)
    {
        $('#name').val(config.displayName);
        $('#value').val(config.value);
        $('#desc').val(config.description);
        $('#law').val(config.law);
    }

    function saveChangesOnConfig()
    {
        $('#submit').click(function(){
            var displayName = $('#name').val();
            var value = $('#value').val();
            var desc = $('#desc').val();
            var law = $('#law').val();
            $.ajax({
                type: "POST",
                url: "../controllers/ManageConfig.php?function=saveChangesOnConfig",
                data: {
                    displayName:displayName,
                    value:value,
                    desc:desc,
                    law:law
                },
                success: function(msg)
                {
                    loadConfigTable();
                    updateTips(msg);
                }
            });
            $( "#configData" ).dialog('close');
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