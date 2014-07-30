<div id="accordion">
    <h3>تعديل كلمة المرور</h3>
    <div>
        <form action="" method="post">
            <table> 
                <tr>
                    <td class="titleTD"><label for="password" class="title">كلمة المرور</label></td>
                    <td><input type="password" id="password" name="password"/></td>
                </tr>
                <tr>
                    <td class="titleTD"><label for="repeatPassword" class="title">تأكيد كلمة المرور</label></td>
                    <td><input type="password" id="repeatPassword" name="repeatPassword"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="sumbit" name="submit" value="تعديل"></td>
                </tr>
            </table>
        </form>
        <p id="msg"></p>
    </div>
    <h3>معلومات الحساب</h3>
    <div>
        <form action="" method="post">
            <div id="userInfo">
                <table>
                    <tr>
                        <td class="titleTD"><label for="email" class="title">البريد الالكتروني</label></td>
                        <td><input type="text" id="email" name="email" disabled/></td>
                    </tr>
                    <tr>
                        <td class="titleTD"><label for="type" class="title">نوع الحساب</label></td>
                        <td><select id="type" name="type" disabled>
                                <option value="مستخدم">مستخدم</option>
                                <option value="طبيب">طبيب</option>
                                <option value="صيدلية">صيدلية</option>
                                <option value="مشفى">مشفى</option>
                            </select>   </td>
                    </tr>
                    <tr>
                        <td class="titleTD"><label for="mobile" class="title">الجوال</label></td>
                        <td><input type="text" id="mobile" name="mobile" disabled/></td>
                    </tr>
                    <tr>
                        <td class="titleTD"><label for="phone" class="title">الهاتف</label></td>
                        <td> <input type="text" id="phone" name="phone" disabled/></td>
                    </tr>
                    <tr>
                        <td class="titleTD"><label for="address" class="title">العنوان</label></td>
                        <td><input type="text" id="address" name="address" disabled/></td>
                    </tr>
                    <tr>
                        <td class="titleTD"><label for="notes" class="title">ملاحظات</label></td>
                        <td><textarea id="notes" name ="notes" disabled></textarea></td>
                    </tr>
                </table>
            </div>


            <div id="doctorInfo" style="display: none">
                <table>
                    <tr>
                        <td class="titleTD"><label for="firstName" class="title">الاسم الأول</label></td>
                        <td><input type="text" id="firstName" name="firstName" disabled/></td>
                    </tr>
                    <tr>
                        <td class="titleTD"><label for="lastName" class="title">الاسم الثاني</label></td>
                        <td><input type="text" id="lastName" name="lastName" disabled/></td>
                    </tr>
                    <tr>
                        <td class="titleTD"><label for="gender" class="title">الجنس</label>
                        <td><div id="gender">
                                <input type="radio" id="female" value="female" name="gender" disabled/><label for="female">أنثى</label>
                                <input type="radio" id="male" value="male" name="gender" checked="checked" disabled/><label for="male">ذكر</label>
                            </div></td>
                    </tr>
                    <tr>
                        <td class="titleTD"><label for="specialization" class="title">الاختصاص</label></td>
                        <td><select id="specialization" name="specialization" disabled>
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
                        <td><input type="text" id="pharmacyName" name="pharmacyName" disabled/></td>
                    </tr>
                </table>
            </div>

            <div id="hospitalInfo" style="display: none">
                <table>
                    <tr>
                        <td class="titleTD"><label for="name" class="title">الاسم</label></td>
                        <td><input type="text" id="hospitalName" name="hospitalName" disabled/></td>
                    </tr>
                </table>
            </div>
        </form> 
    </div>


</div>
<script>

    function fillUserInfoFileds(user)
    {    
        $('#type').val(user.type);
        $('#email').val(user.email);
        $('#mobile').val(user.mobile);
        $('#phone').val(user.phone);
        $('#address').val(user.address);
        $('#notes').val(user.notes);
        switch (user.type)
        {  
            case "مستخدم":
                $('#doctorInfo').css('display', 'none');
                $('#pharmacyInfo').css('display', 'none');
                $('#hospitalInfo').css('display', 'none');
                break;
            case "طبيب":
                $('#doctorInfo').css('display', 'block');
                $('#pharmacyInfo').css('display', 'none');
                $('#hospitalInfo').css('display', 'none');
            
                $('#firstName').val(user.firstName);
                $('#lastName').val(user.lastName);
                $('#gender #' + user.gender).attr('checked', true);
                $('#specialization').val(user.specialization_id);
                break;
            case "صيدلية":
                $('#doctorInfo').css('display', 'none');
                $('#pharmacyInfo').css('display', 'block');
                $('#hospitalInfo').css('display', 'none');
            
                $('#pharmacyName').val(user.pharmacyName);
                break;
            case "مشفى":
                $('#doctorInfo').css('display', 'none');
                $('#pharmacyInfo').css('display', 'none');
                $('#hospitalInfo').css('display', 'block');
            
                $('#hospitalName').val(user.hospitalName);
                break;
        } 
    }
    function updateTips(msg) 
    {
        var tips = $( "#msg" );
        tips.html(msg)
        tips.addClass( "ui-state-highlight" );
        setTimeout(function() 
        {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
    }

    function checkPassword(password, passwordRepeat) 
    {   
        var msg;
        var result;
        if (password == passwordRepeat && password.length > 6) 
        {
            msg = "";
            result = true;
        } 
        else 
        {
            msg = "كلمة المرور ضعيفة جدا او غير متطابقة";
            result = false;
        }
        updateTips(msg); 
        return result;
    }

    $(document).ready(function(){
        $('#sumbit').button();
        $( "#accordion" ).accordion({
            heightStyle: "content"
        });
        $( "#gender" ).buttonset();
        var userJson = '<?php echo json_encode($data['user']); ?>';
        var user = eval('('+ userJson + ')');
        fillUserInfoFileds(user);
        $('#sumbit').click(function(){
            var result = checkPassword($('#password').val(), $('#repeatPassword').val());
            if (result)
            {
                $.ajax({
                    type: "POST",
                    url: "../controllers/Profile.php?function=editPasswordForAjax",
                    data: {
                        userID: '<?php echo $data['userID'] ?>',
                        password:$('#password').val()
                    },
                    success: function(msg)
                    {
                        if (msg == 1)
                        { 
                            updateTips('تم تعديل المعلومات بنجاح');
                        }
                        else if (msg == 0)
                        {
                            updateTips('فشلت عملية التعديل');                    
                        }
                        else
                        {
                            updateTips(msg);
                        }
                    }  
                });    
            }        
            return false;   
        });
    });
    
</script>