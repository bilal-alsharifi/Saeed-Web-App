<form action="" method="get">
    <table>
        <tr>
            <td><label for="name" class="title">الاسم</label></td>
            <td><input type="text" id="name" name="name"/></td>
        </tr>
        <tr>
            <td><label for="phone" class="title">الهاتف</label></td>
            <td><input type="text" id="phone" name="phone"/></td>
        </tr>
        <tr>
            <td><label for="email" class="title">البريد الالكتروني</label></td>
            <td><input type="text" id="email" name="email"/></td>
        </tr>
        <tr>
            <td><label for="text" class="title">نص الرسالة</label></td>
            <td><textarea id="text" name ="text"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" id="submit" name="submit" value="ارسال"/></td>
        </tr>
    </table>
</form>
<p id="msg"></p>
<script>
    $(document).ready(function(){
        $('#submit').button().click(function(){
            var validInfo = checkEmail($('#email').val());
            if (validInfo)
            {
                $.ajax({
                    type: "GET",
                    url: "../controllers/Contact.php?function=sendMailUsForAjax",
                    data: {
                        name:$('#name').val(),
                        phone:$('#phone').val(),
                        email:$('#email').val(),
                        text:$('#text').val()
                    },
                    success: function(msg)
                    {      
                        if (msg == 1)
                        {
                            updateTips('تم ارسال البريد بنجاح'); 
                        }
                        else
                        {
                            updateTips('فشلت عملية ارسال البريد');        
                        }
                    }  
                }); 
            }   
            return false;
        });
    });
    
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
    function checkEmail(email) 
    {   
        var result;
        var regexp = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/ ;
        if (regexp.test(email))
        { 
            result = true;
        }
        else
        {
            result = false;
            updateTips('البريد الالكتروني غير صحيح'); 
        }    
        return result;
    }

</script>