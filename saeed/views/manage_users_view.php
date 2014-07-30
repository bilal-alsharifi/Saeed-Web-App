<script type="text/javascript" src="../js/multiselect/jquery-multiselect-2.0.js"></script> 
<script type="text/javascript" src="../js/multiselect/locales/jquery-multiselect-2_ar.js"></script>
<link rel="stylesheet" type="text/css" href="../css/multiselect/jquery-multiselect-2.0.css" />  

<a id="newUser" href="">مستخدم جديد</a>

<div id="newUserDialog" title="مستخدم جديد" style="display: none">
    <form action="../controllers/ManageUsers.php?function=addUser" method="post">
        <div id="userInfo">
            <table> 
                <tr>
                    <td class="titleTD"></td>
                    <td>
                        <ul id="validateTips">
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="email"  class="title">البريد الالكتروني</label></td>
                    <td><input type="text" id="email" name="email"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="password"  class="title">كلمة المرور</label></td>
                    <td><input type="password" id="password" name="password"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="repeatPassword"  class="title">تأكيد كلمة المرور</label></td>
                    <td><input type="password" id="repeatPassword" name="repeatPassword"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="type"  class="title">نوع الحساب</label></td>
                    <td><select id="type" name="type">
                            <option value="مستخدم">مستخدم</option>
                            <option value="طبيب">طبيب</option>
                            <option value="صيدلية">صيدلية</option>
                            <option value="مشفى">مشفى</option>
                        </select>   </td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="mobile"  class="title">الجوال</label></td>
                    <td><input type="text" id="mobile" name="mobile"/></td>
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
                    <td class="titleTD"><label for="longitude"  class="title">خط الطول</label></td>
                    <td><input type="text" id="longitude" name="longitude"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="latitude"  class="title">خط العرض</label></td>
                    <td><input type="text" id="latitude" name="latitude"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="notes"  class="title">ملاحظات</label></td>
                    <td><textarea id="notes" name ="notes"></textarea></td>
                </tr>
            </table>
        </div>


        <div id="doctorInfo" style="display: none">
            <table>
                <tr>
                    <td class="titleTD"><label for="firstName"  class="title">الاسم الأول</label></td>
                    <td><input type="text" id="firstName" name="firstName"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="lastName"  class="title">الاسم الثاني</label></td>
                    <td><input type="text" id="lastName" name="lastName"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="gender" class="title">الجنس</label></td>
                    <td><div id="gender">
                            <input type="radio" id="female" value="female" name="gender" /><label for="female">أنثى</label>
                            <input type="radio" id="male" value="male" name="gender" checked="checked" /><label for="male">ذكر</label>
                        </div></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="specialization" class="title">الاختصاص</label></td>
                    <td><select id="specialization" name="specialization">
                            <?php
                            foreach ($data['specializations'] as $item) {
                                $id = $item['id'];
                                $name = $item['name'];
                                echo "<option value='{$id}'>{$name}</option>";
                            }
                            ?>
                        </select></td>
                </tr>
            </table>
        </div>


        <div id="pharmacyInfo" style="display: none">
            <table>
                <tr>
                    <td class="titleTD"><label for="name" class="title">الاسم</label></td>
                    <td><input type="text" id="pharmacyName" name="pharmacyName"/> </td>   
                </tr>
            </table>
        </div>

        <div id="hospitalInfo" style="display: none">
            <table>
                <td class="titleTD"><label for="name" class="title">الاسم</label></td>
                <td><input type="text" id="hospitalName" name="hospitalName"/></td>
            </table>
        </div>

        <input type="submit" id="submit" name="submit" value="إضافة"/>
    </form> 
</div>

<div id="editUserDialog" title="تعديل مستخدم" style="display: none">
    <form action="../controllers/ManageUsers.php?function=editUser" method="post">

        <div id="userInfo2">
            <table>
                <tr>
                    <td></td>
                    <td>
                        <ul id="validateTips2">
                        </ul>
                        <input type="hidden" id="userID2" name="userID2" value="">
                        <input type="hidden" id="type2" name="type2" value="">
                    </td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="email2" class="title">البريد الالكتروني</label></td>
                    <td><input type="text" id="email2" name="email2"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="password2" class="title">كلمة المرور</label></td>
                    <td><input type="password" id="password2" name="password2"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="repeatPassword2" class="title">تأكيد كلمة المرور</label></td>
                    <td><input type="password" id="repeatPassword2" name="repeatPassword2"/> </td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="mobile2" class="title">الجوال</label></td>
                    <td><input type="text" id="mobile2" name="mobile2"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="phone2" class="title">الهاتف</label></td>
                    <td><input type="text" id="phone2" name="phone2"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="address2" class="title">العنوان</label></td>
                    <td><input type="text" id="address2" name="address2"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="longitude2" class="title">خط الطول</label></td>
                    <td><input type="text" id="longitude2" name="longitude2"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="latitude2" class="title">خط العرض</label></td>
                    <td><input type="text" id="latitude2" name="latitude2"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="notes2" class="title">ملاحظات</label></td>
                    <td><textarea id="notes2" name ="notes2"></textarea></td>
                </tr>
            </table>
        </div>


        <div id="doctorInfo2" style="display: none">
            <table>
                <tr>
                    <td class="titleTD"><label for="firstName2" class="title">الاسم الأول</label></td>
                    <td><input type="text" id="firstName2" name="firstName2"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="lastName2" class="title">الاسم الثاني</label></td>
                    <td><input type="text" id="lastName2" name="lastName2"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="gender2" class="title">الجنس</label></td>
                    <td><div id="gender2">
                            <input type="radio" id="female2" value="female" name="gender2" /><label for="female2">أنثى</label>
                            <input type="radio" id="male2" value="male" name="gender2" checked="checked" /><label for="male2">ذكر</label>
                        </div></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="specialization2" class="title">الاختصاص</label></td>
                    <td><select id="specialization2" name="specialization2">
                            <?php
                            foreach ($data['specializations'] as $item) {
                                $id = $item['id'];
                                $name = $item['name'];
                                echo "<option value='{$id}'>{$name}</option>";
                            }
                            ?>
                        </select></td>
                </tr>
            </table>
        </div>


        <div id="pharmacyInfo2" style="display: none">
            <table>
                <tr>
                    <td class="titleTD"><label for="pharmacyName2" class="title">الاسم</label></td>
                    <td><input type="text" id="pharmacyName2" name="pharmacyName2"/></td>
                </tr>
            </table>
        </div>

        <div id="hospitalInfo2" style="display: none">
            <table>
                <tr>
                    <td class="titleTD"><label for="hospitalName2" class="title">الاسم</label></td>
                    <td><input type="text" id="hospitalName2" name="hospitalName2"/></td>
                </tr>
            </table>
        </div>
        <table>
            <tr>
                <td><a id="deleteUser" href="">حذف المستخدم</a></td>
                <td>
                    <input type="submit" id="submit2" name="submit2" value="تعديل"/>
                    <a id="manageRoles" href="">ادارة الأدوار</a>
                </td>
            </tr>
        </table>
    </form> 
</div>


<div id="manageRolesDilaog" title="إدارة الأدوار" style="display: none">
    <form action="../controllers/ManageUsers.php?function=addRoles" method="post">
        <table>
            <tr>
                <td>
                    <input type="hidden" id="userID3" name="userID3" value="">
                    <select id="roles" name="roles[]" class="multiselect" multiple="multiple">
                        <?php
                        foreach ($data['roles'] as $role) {
                            echo "<option id='r{$role['id']}' value='{$role['id']}' title='{$role['description']}'>{$role['name']}</option>";
                        }
                        ?>    
                    </select> 
                </td>
            </tr>
            <tr>
                <td>
                    <br />
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" id="submit3" name="submit3" value="تعديل"/>
                </td>
            </tr>
        </table>
    </form> 
</div>

<p id="msg"><?php $data['msg']; ?></p>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="usersTable">
</table>


<script>
    $(document).ready(function(){

        var errors = new Array();    
        errors['email'] = "";
        errors['password'] = "";
        errors['firstName'] = "";
        errors['lastName'] = "";
        errors['pharmacyName'] = "";
        errors['hospitalName'] = "";  
    
        //$(document).tooltip();
        loadPatientsTable();
  

        $('#newUserDialog input').blur(function(){
            checkAll(errors);    
        });
             
        //convert inputs to jquery ui style
        $( "#gender, #gender2" ).buttonset();
        $('#newUser, #deleteUser, #submit2, #submit3, #manageRoles').button();
        $('#newUser').click(function(){
            $( "#newUserDialog" ).dialog({  
                width:400,
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
        
        
              
        $('#manageRoles').click(function(){
            $( "#manageRolesDilaog" ).dialog({
                width : 500,
                modal: true,
                draggable : true,
                closeOnEscape: true,
                resizable : true,
                show: "blind",
                hide: "explode"
            });
            $('#roles').multiselect();
            $('#roles').multiselect('locale', 'ar' );
            return false;
        }); 
   
    });
    function datatablesReady()
    {
        var user;
        $('.editUser').click(function(){  
            var email = decodeURIComponent($(this).html());
            $.ajax({
                type: "GET",
                url: "../controllers/ManageUsers.php?function=getUserForAjax",
                data: {
                    email:email
                },
                success: function(msg)
                {
                    user = eval('('+ msg + ')');
                    fillEditUserFileds(user);               
                }  
            }); 
        
            $( "#editUserDialog" ).dialog({
                width:400,
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

    function fillEditUserFileds(user)
    {    
        $('#deleteUser').attr("href", "../controllers/ManageUsers.php?function=deleteUser&userID=" + user.id);
        $('#userID2').val(user.id);
        $('#userID3').val(user.id);
        $('#type2').val(user.type);
        $('#email2').val(user.email);
        $('#mobile2').val(user.mobile);
        $('#phone2').val(user.phone);
        $('#address2').val(user.address);
        $('#longitude2').val(user.longitude);
        $('#latitude2').val(user.latitude);
        $('#notes2').val(user.notes);
        switch (user.type)
        {  
            case "مستخدم":
                $('#doctorInfo2').css('display', 'none');
                $('#pharmacyInfo2').css('display', 'none');
                $('#hospitalInfo2').css('display', 'none');
                break;
            case "طبيب":
                $('#doctorInfo2').css('display', 'block');
                $('#pharmacyInfo2').css('display', 'none');
                $('#hospitalInfo2').css('display', 'none');
            
                $('#firstName2').val(user.firstName);
                $('#lastName2').val(user.lastName);
                $('#gender2 #' + user.gender + '2').attr('checked', "true");
                $('#gender2').buttonset("refresh"); 
                $('#specialization2').val(user.specialization_id);
                break;
            case "صيدلية":
                $('#doctorInfo2').css('display', 'none');
                $('#pharmacyInfo2').css('display', 'block');
                $('#hospitalInfo2').css('display', 'none');
            
                $('#pharmacyName2').val(user.pharmacyName);
                break;
            case "مشفى":
                $('#doctorInfo2').css('display', 'none');
                $('#pharmacyInfo2').css('display', 'none');
                $('#hospitalInfo2').css('display', 'block');
            
                $('#hospitalName2').val(user.hospitalName);
                break;
        } 
        var roles = user.roles;
        $('#roles option').removeAttr("selected");    
        for (var r in roles)
        {
            var id = roles[r].id;
            $('#r' + id).attr('selected', 'selected');         
        }  
        $("#roles").multiselect('refresh');
    }
    function loadPatientsTable() 
    {
        $('#usersTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "../controllers/ManageUsers.php?function=getAllUsersForDataTables",
            // by default order by this column
            "aaSorting": [[ 0, "asc" ]],
            //take the jQueryUI style
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",       
            "aoColumns": [
                { "sTitle": "البريد الالكتروني" },
                { "sTitle": "النوع" },
                { "sTitle": "الجوال" },
                { "sTitle": "الهاتف"},
                { "sTitle": "العنوان"},
                { "sTitle": "خط الطول"},
                { "sTitle": "خط العرض"},
            ],
            // edit the style of the target columns
            "aoColumnDefs": [{
                    "sClass": "center",
                    "aTargets": [ 0 ,1, 2, 3, 4, 5, 6]
                }],
            // edit a specific column
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                // convert emails to link
                var emailCell = $('td:eq(0)', nRow);
                emailCell.html( '<a class=editUser title="تعديل" href="">' + emailCell.html() + '</a>' );
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
        checkEmail($('#email').val(), errors);
        checkPassword($('#password').val(), $('#repeatPassword').val(), errors);
        checkPharmacyName($('#pharmacyName').val(), errors);
        checkHospitalName($('#hospitalName').val(), errors);
        checkFirstName($('#firstName').val(), errors);
        checkLastName($('#lastName').val(), errors);
        for (var e in errors)
        {
            if(errors[e].length > 0)
            {
                return false;
            }
        }
        return true;
    }

    function checkEmail(email, errors)
    {
        $.ajax({
            type: "GET",
            url: "../controllers/ManageUsers.php?function=getUserForAjax",
            data: {
                email:email
            },
            success: function(msg)
            {
                var regexp = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/ ;
                if (msg == 0 && regexp.test(email))
                { 
                    errors['email'] = "";
                }
                else
                {
                    errors['email'] = 'اسم المستخدم غير صحيح او محجوز مسبقا';
                }
            }  
        });  
        updateTips(errors);
    }

    function checkPassword(password, passwordRepeat, errors) 
    {   
        if (password == passwordRepeat && password.length > 6) 
        {
            errors['password'] = "";
        } 
        else 
        {
            errors['password'] = "كلمة المرور ضعيفة جدا او غير متطابقة";
        }
        updateTips(errors);    
    }

    function checkPharmacyName(pharmacyName, errors)
    {
        if ($('#type').val() =='صيدلية' && pharmacyName.length < 3)
        {
            errors['pharmacyName'] = 'اسم الصيدلية قصير جدا';      
        }
        else
        {
            errors['pharmacyName'] = "";
        }
        updateTips(errors);
    }

    function checkHospitalName(hospitalName, errors)
    {
        if ($('#type').val() =='مشفى' && hospitalName.length < 3)
        {
            errors['hospitalName'] = 'اسم المشفى قصير جدا';        
        }
        else
        {
            errors['hospitalName'] = "";
        }
        updateTips(errors);
    }

    function checkFirstName(firstName, errors)
    {
        if ($('#type').val() =='طبيب' && firstName.length < 3)
        {
            errors['firstName'] = 'اسم الطبيب قصير جدا';        
        }
        else
        {
            errors['firstName'] = "";
        }
        updateTips(errors);
    }


    function checkLastName(lastName, errors)
    {
        if ($('#type').val() =='طبيب' && lastName.length < 3)
        {
            errors['lastName'] = 'اسم الطبيب الثاني قصير جدا';        
        }
        else
        {
            errors['lastName'] = "";
        }
        updateTips(errors);
    }
        
    $('#type').change(function()
    {
        var type = $('#type').val();
        switch (type)
        {  
            case "مستخدم":
                $('#doctorInfo').css('display', 'none');
                $('#pharmacyInfo').css('display', 'none');
                $('#hospitalInfo').css('display', 'none');
                break;
            case "طبيب":
                $('#doctorInfo').effect("slide", {direction: "up"}, "slow" );
                $('#pharmacyInfo').css('display', 'none');
                $('#hospitalInfo').css('display', 'none');
                break;
            case "صيدلية":
                $('#doctorInfo').css('display', 'none');
                $('#pharmacyInfo').effect("slide", {direction: "up"}, "slow" );
                $('#hospitalInfo').css('display', 'none');
                break;
            case "مشفى":
                $('#doctorInfo').css('display', 'none');
                $('#pharmacyInfo').css('display', 'none');
                $('#hospitalInfo').effect("slide", {direction: "up"}, "slow" );
                break;
        }
    });
</script>
