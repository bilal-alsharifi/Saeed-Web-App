<a id="newNews" href="">خبر جديد</a>
<div id="newNewsDialog" title="إضافة خبر جديد" style="display: none">
    <form id="newNewsForm" action="" method="post">
        <table>
            <tr>
                <td><label for="title" class="title">العنوان</label></td>
                <td><input type="text" id="title" name="title"/></td>
            </tr>
            <tr>
                <td><label for="description"  class="title">الوصف</label></td>
         
                <td><textarea id="description" name="description"></textarea></td>
            </tr>
            <tr>
                <td><label for="link"  class="title">الرابط</label></td>
                <td><input type="text" id="link" name="link"/></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" id="submit" name="submit" value="إضافة"/></td>
            </tr>
        </table>

    </form> 
</div>

<div id="editNewsDialog" title="تعديل خبر" style="display: none">
    <form id="editNewsForm" action="" method="post">
        <table>
            <tr>
                <td><input type="hidden" id="newsID2" name="newsID2" value="">
                    <label for="title2" class="title">العنوان</label></td>
                <td><input type="text" id="title2" name="title2"/></td>
            </tr>
            <tr>
                <td><label for="description2"  class="title">الوصف</label></td>
                <td><textarea id="description2" name="description2"></textarea></td>
            </tr>
            <tr>
                <td><label for="link2"  class="title">الرابط</label></td>
                <td><input type="text" id="link2" name="link2"/></td>
            </tr>
            <tr>
                <td><input type="submit" id="submit2" name="submit2" value="تعديل"/></td>
                <td><a id="deleteNews" href="">حذف الخبر</a></td>
            </tr>
        </table>

    </form> 
</div>


<p id="msg"></p>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="newsTable">
</table>

<script>
    $(document).ready(function(){
        $(document).tooltip();
        $('#submit, #submit2, #deleteNews').button();
        loadNewsTable();
        $('#newNews').button().click(function ()
        {
            $( "#newNewsDialog" ).dialog({
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
                url: "../controllers/ManageNews.php?function=addNews",
                data: $('#newNewsForm').serialize(),
                success: function(msg)
                {      
                    $( "#newNewsDialog" ).dialog('close');
                    updateTips(msg);
                    loadNewsTable();              
                }  
            }); 
            return false;
        });
        
        $('#deleteNews').click(function(){
            $.ajax({
                type: "POST",
                url: "../controllers/ManageNews.php?function=deleteNews",
                data: {
                    newsID: $(this).attr('href')
                },
                success: function(msg)
                {      
                    $( "#editNewsDialog" ).dialog('close');
                    updateTips(msg);
                    loadNewsTable();              
                }  
            }); 
            return false;
        });
        
        $('#submit2').click(function(){
            $.ajax({
                type: "POST",
                url: "../controllers/ManageNews.php?function=editNews",
                data: $('#editNewsForm').serialize(),
                success: function(msg)
                {      
                    $( "#editNewsDialog" ).dialog('close');
                    updateTips(msg);
                    loadNewsTable();              
                }  
            }); 
            return false;
        });
    });
    
    function fillEditNewsFields(news)
    {    
        $('#deleteNews').attr("href", news.id);
        $('#newsID2').val(news.id);
        $('#title2').val(news.title);
        $('#description2').val(news.description);
        $('#link2').val(news.link);
    }
    
    function datatablesReady()
    {
        var news;
        $('.editNews').click(function(){  
            var newsID = decodeURIComponent($(this).html());
            $.ajax({
                type: "GET",
                url: "../controllers/ManageNews.php?function=getNewsForAjax",
                data: {
                    newsID:newsID
                },
                success: function(msg)
                {      
                    news = eval('('+ msg + ')');
                    fillEditNewsFields(news);               
                }  
            }); 
        
            $( "#editNewsDialog" ).dialog({
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
    
    function loadNewsTable() 
    {
        $('#newsTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            //to destroy and rebuild data
            "bDestroy": true,
            "bAutoWidth": false,
            "sAjaxSource": "../controllers/ManageNews.php?function=getAllNewsForDataTables",
            // by default order by this column
            "aaSorting": [[ 0, "asc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",       
            "aoColumns": [
                { "sTitle": "الرقم" },
                { "sTitle": "العنوان" },
                { "sTitle": "الوصف" },
                { "sTitle": "الرابط" },
                { "sTitle": "التاريخ" }  
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1, 2, 3, 4]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                // convert emails to link
                var idCell = $('td:eq(0)', nRow);
                idCell.html( '<a class=editNews title="تعديل" href="">' + idCell.html() + '</a>' );
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