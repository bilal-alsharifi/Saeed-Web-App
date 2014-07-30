<script type="text/javascript" src="../js/multiselect/jquery-multiselect-2.0.js"></script> 
<script type="text/javascript" src="../js/multiselect/locales/jquery-multiselect-2_ar.js"></script>
<link rel="stylesheet" type="text/css" href="../css/multiselect/jquery-multiselect-2.0.css" />  

<a id="newRole" href="">دور جديد</a>
<div id="newRoleDialog" title="إنشاء دور جديد" style="display: none">
    <form action="../controllers/ManageRoles.php?function=addRole" method="post">
        <table>
            <tr>
                <td></td>
                <td>            
                    <ul id="validateTips">
                    </ul>
                </td>
            </tr>
            <tr>
                <td><label for="name" class="title">الاسم</label></td>
                <td><input type="text" id="name" name="name"/></td>
            </tr>
            <tr>
                <td><label for="description" class="title">الوصف</label></td>
                <td><textarea id="description" name ="description"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" id="submit" name="submit" value="إضافة"/></td>
            </tr>
        </table>

    </form> 
</div>

<div id="editRoleDialog" title="تعديل دور" style="display: none">
    <form action="../controllers/ManageRoles.php?function=editRole" method="post">

        <table>
            <tr>
                <td></td>
                <td>
                    <ul id="validateTips2">
                    </ul>
                    <input type="hidden" id="roleID2" name="roleID2" value="">
                </td>
            </tr>
            <tr>
                <td><label for="name2" class="title">الاسم</label></td>
                <td><input type="text" id="name2" name="name2"/></td>
            </tr>
            <tr>
                <td><label for="description2" class="title">الوصف</label></td>
                <td><textarea id="description2" name ="description2"></textarea></td>
            </tr>
            <tr>
                <td><input type="submit" id="submit2" name="submit2" value="تعديل"/></td>
                <td><a id="deleteRole" href="">حذف الدور</a>
                    <a id="managePermissions" href="">ادارة الصلاحيات</a></td>
            </tr>

        </table>

    </form> 
</div>

<div id="managePermissionsDilaog" title="إدارة الصلاحيات" style="display: none">
    <form action="../controllers/ManageRoles.php?function=addPermissions" method="post">
        <table>
            <tr>
                <td>
                    <input type="hidden" id="roleID3" name="roleID3" value="">
                    <select id="permissions" name="permissions[]" class="multiselect" multiple="multiple">
                        <?php
                        foreach ($data['permissions'] as $permissions) {
                            echo "<option id='p{$permissions['id']}' value='{$permissions['id']}' title='{$permissions['description']}'>{$permissions['displayName']}</option>";
                        }
                        ?>    
                    </select>  
                </td>
            </tr>

            <tr>
                <td><br /></td>
            </tr>
            <tr>
                <td><input type="submit" id="submit3" name="submit3" value="تعديل"/></td>
            </tr>
        </table>
    </form> 
</div>

<p id="msg"><?php echo $data['msg']; ?></p>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="rolesTable">
</table>


<script>
    //validation errors array for new role
    var errors = new Array();    
    errors['name'] = "";
        
    //validation errors array for edit role
    var errors2 = new Array();    
    errors2['name'] = "";
    
           
    $(document).ready(function (){ 
        //$(document).tooltip();
        //$('#permissions option').tooltip();
        loadRolesTable();
            
        $('#newRoleDialog input,#newRoleDialog textarea').focus(function(){          
            checkAll();       
        });
 
        $('#editRoleDialog input,#newRoleDialog textarea').blur(function(){
            checkAll2();    
        });

 
        
        $('input[type=submit], #newRole, #deleteRole, #managePermissions').button();
        $('#newRole').click(function(){
            $( "#newRoleDialog" ).dialog({
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
        $('#submit').button().click(function()
        {
            var result = checkAll();
            return result;
        });
        
        $('#submit2').button().click(function()
        {
            var result = checkAll2();
            return result;
        });
        
        
        $('#managePermissions').click(function(){
            $( "#managePermissionsDilaog" ).dialog({
                width : 500,
                modal: true,
                draggable : true,
                closeOnEscape: true,
                resizable : true,
                show: "blind",
                hide: "explode"
            });
            $('#permissions').multiselect();
            $('#permissions').multiselect('locale', 'ar' );
            return false;
        }); 
        
    });
    
    function datatablesReady()
    {
        var role;
        $('.editRole').click(function(){  
            var roleName = decodeURIComponent($(this).html());
            $.ajax({
                type: "GET",
                url: "../controllers/ManageRoles.php?function=getRoleForAjax",
                data: {
                    roleName:roleName
                },
                success: function(msg)
                {      
                    role = eval('('+ msg + ')');
                    fillEditRoleFields(role);               
                }  
            }); 
        
            $( "#editRoleDialog" ).dialog({
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

    function fillEditRoleFields(role)
    {    
        $('#deleteRole').attr("href", "../controllers/ManageRoles.php?function=deleteRole&roleID=" + role.id);
        $('#roleID2').val(role.id);
        $('#roleID3').val(role.id);
        $('#name2').val(role.name);
        $('#description2').val(role.description);
        var permissions = role.permissions;
        $('#permissions option').removeAttr("selected");    
        for (var p in permissions)
        {
            var id = permissions[p].id;
            $('#p' + id).attr('selected', 'selected');         
        }  
        $("#permissions").multiselect('refresh');
    }
    
    function loadRolesTable() 
    {
        $('#rolesTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "../controllers/ManageRoles.php?function=getAllRolesForDataTables",
            // by default order by this column
            "aaSorting": [[ 0, "asc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",       
            "aoColumns": [
                { "sTitle": "الاسم" },
                { "sTitle": "الوصف" }         
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                // convert emails to link
                var nameCell = $('td:eq(0)', nRow);
                nameCell.html( '<a class=editRole title="تعديل" href="">' + nameCell.html() + '</a>' );
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
    function updateTips(errorsList, tips) 
    {
        tips.html("");
        for(var e in errorsList)
        {
            if (errorsList[e].length > 0)
            {
                tips.append("<li>" + errorsList[e] + "</li>");
            }
        }
        tips.addClass( "ui-state-highlight" );
        setTimeout(function() 
        {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
    }
    function checkAll()
    {
        var errorsList = errors;
        var tips =  $( "#validateTips" );
        checkName($('#name').val(), errorsList, tips);
        for (var e in errorsList)
        {
            if(errorsList[e].length > 0)
            {
                return false;
            }
        }
        return true;
    }
    
    function checkAll2()
    {
        var errorsList = errors2;
        var tips =  $( "#validateTips2" );
        checkName($('#name2').val(), errorsList, tips);
        for (var e in errorsList)
        {
            if(errorsList[e].length > 0)
            {
                return false;
            }
        }
        return true;
    }
    
    function checkName(name, errorsList, tips)
    {
        if (name.length < 3)
        {
            errorsList['name'] = 'اسم الدور قصير جدا';        
        }
        else
        {
            errorsList['name'] = "";
        }
        updateTips(errorsList, tips);
    }


</script>