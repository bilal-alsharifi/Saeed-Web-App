<form action="../controllers/Login.php?function=validate" method="post">
    <table>
        <tr>
            <td><label for="email" class="title">البريد الالكتروني</label></td>
            <td><input type="text" id="email" name="email" /></td>
        </tr>
        <tr>
            <td><label for="passwrd" class="title">كلمة المرور</label></td>
            <td><input type="password" id="password" name="password"/></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" id="submit" value="تسجيل الدخول"/></td>
        </tr>
        <tr>
            <td></td>
            <td><a id="forgotPassword" href="">استعادة كلمة المرور</a></td>
        </tr>
    </table>
</form>

<div id="forgotPasswordDialog" title="استعادة كلمة المرور" style="display: none">
    <table>
        <tr>
            <td><label for="email2" class="title">البريد الالكتروني</label></td>
            <td><input type="text" id="email2" name="email2" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" id="submit2" value="ارسال"/></td>
        </tr>
    </table>
    
    
    
</div>

<p id="msg"><?php echo urldecode($data['msg']); ?></p>

<script>
    $(document).ready(function(){
        $('#submit, #submit2').button();
        $('#forgotPassword').click(function(){
            $( "#forgotPasswordDialog" ).dialog({
                modal : true,
                width : 400,
                draggable : true,
                closeOnEscape: true,
                resizable : true,
                show: "blind",
                hide: "explode"
            });
            return false;
        }); 
        $('#submit2').click(function(){
            $.ajax({
                type: "GET",
                url: "../controllers/Login.php?function=forgotPasswordForAjax",
                data: {
                    email:$('#email2').val()
                },
                success: function(msg)
                {
                    $( "#forgotPasswordDialog" ).dialog('close');
                    if (msg == 1)
                    { 
                        updateTips( 'تم ارسال بريد الكتروني');
                    }
                    else 
                    {
                        updateTips( 'البريد الالكروني غير مسجل')
                    }
                   
                }  
            });          
        }); 
    });
   
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
</script>