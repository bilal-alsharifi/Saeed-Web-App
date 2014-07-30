<a id="newCompany" href="">شركة جديدة</a>
<div id="newCompanyDialog" title="إنشاء شركة جديدة" style="display: none">
    <form id="newCompanyForm" action="../controllers/ManageCompanies.php?function=addCompany" method="post">
        <table>
            <tr>
                <td><label for="name" class="title">الاسم</label></td>
                <td><input type="text" id="name" name="name"/></td>
            </tr>
            <tr>
                <td class="titleTD"><label for="phone"  class="title">الهاتف</label></td>
                <td><input type="text" id="phone" name="phone"/></td>
            </tr>
            <tr>
                <td class="titleTD"><label for="address"  class="title">العنوان</label></td>
                <td><input type="text" id="address" name="address"/></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" id="submit" name="submit" value="إضافة"/></td>
            </tr>
        </table>

    </form> 
</div>

<div id="editCompanyDialog" title="تعديل شركة" style="display: none">
    <form id="editCompanyForm" action="../controllers/ManageCompanies.php?function=addCompany" method="post">
        <table>
            <tr>
                <td><input type="hidden" id="companyID2" name="companyID2" value="">
                    <label for="name2" class="title">الاسم</label></td>
                <td><input type="text" id="name2" name="name2"/></td>
            </tr>
            <tr>
                <td class="titleTD"><label for="phone2"  class="title">الهاتف</label></td>
                <td><input type="text" id="phone2" name="phone2"/></td>
            </tr>
            <tr>
                <td class="titleTD"><label for="address2"  class="title">العنوان</label></td>
                <td><input type="text" id="address2" name="address2"/></td>
            </tr>
            <tr>
                <td><input type="submit" id="submit2" name="submit2" value="تعديل"/></td>
                <td><a id="deleteCompany" href="">حذف  الشركة</a></td>
            </tr>
        </table>

    </form> 
</div>


<p id="msg"></p>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="rolesTable">
</table>

<script>
    $(document).ready(function(){
        $(document).tooltip();
        $('#submit, #submit2, #deleteCompany').button();
        loadCompaniesTable();
        $('#newCompany').button().click(function ()
        {
            $( "#newCompanyDialog" ).dialog({
                width: 300,
                modal: true,
                draggable : true,
                closeOnEscape: true,
                resizable : true,
                show: "blind",
                hide: "explode"
            });
            return false;
        });
        $('#submit').click(function(){
            $.ajax({
                type: "POST",
                url: "../controllers/ManageCompanies.php?function=addCompany",
                data: $('#newCompanyForm').serialize(),
                success: function(msg)
                {      
                    $( "#newCompanyDialog" ).dialog('close');
                    updateTips(msg);
                    loadCompaniesTable();              
                }  
            }); 
            return false;
        });
        
        $('#deleteCompany').click(function(){
            $.ajax({
                type: "POST",
                url: "../controllers/ManageCompanies.php?function=deleteCompany",
                data: {
                    companyID: $(this).attr('href')
                },
                success: function(msg)
                {      
                    $( "#editCompanyDialog" ).dialog('close');
                    updateTips(msg);
                    loadCompaniesTable();              
                }  
            }); 
            return false;
        });
        
        $('#submit2').click(function(){
            $.ajax({
                type: "POST",
                url: "../controllers/ManageCompanies.php?function=editCompany",
                data: $('#editCompanyForm').serialize(),
                success: function(msg)
                {      
                    $( "#editCompanyDialog" ).dialog('close');
                    updateTips(msg);
                    loadCompaniesTable();              
                }  
            }); 
            return false;
        });
    });
    
    function fillEditCompanyFields(company)
    {    
        $('#deleteCompany').attr("href", company.id);
        $('#companyID2').val(company.id);
        $('#name2').val(company.name);
        $('#phone2').val(company.phone);
        $('#address2').val(company.address);
    }
    
    function datatablesReady()
    {
        var company;
        $('.editCompany').click(function(){  
            var companyID = decodeURIComponent($(this).html());
            $.ajax({
                type: "GET",
                url: "../controllers/ManageCompanies.php?function=getCompanyForAjax",
                data: {
                    companyID:companyID
                },
                success: function(msg)
                {      
                    company = eval('('+ msg + ')');
                    fillEditCompanyFields(company);               
                }  
            }); 
        
            $( "#editCompanyDialog" ).dialog({
                width: 400,
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
    
    function loadCompaniesTable() 
    {
        $('#rolesTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            //to destroy and rebuild data
            "bDestroy": true,
            "bAutoWidth": false,
            "sAjaxSource": "../controllers/ManageCompanies.php?function=getAllCompaniesForDataTables",
            // by default order by this column
            "aaSorting": [[ 0, "asc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",       
            "aoColumns": [
                { "sTitle": "الرقم" },
                { "sTitle": "الاسم" },
                { "sTitle": "الهاتف" },
                { "sTitle": "العنوان" }  
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                // convert emails to link
                var idCell = $('td:eq(0)', nRow);
                idCell.html( '<a class=editCompany title="تعديل" href="">' + idCell.html() + '</a>' );
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
    function updateTips(msg) 
    {
        var tips = $( "#msg" );          
        tips.css('visibility', 'visible');
        tips.html(msg)
        tips.addClass( "ui-state-highlight" );
        setTimeout(function() 
        {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
        setTimeout(function() 
        {
            tips.css('visibility', 'hidden');
        }, 2500 );
    }
</script>