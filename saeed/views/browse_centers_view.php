<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDppsz2CgdMgA_vBkGTmTeiDuD9Zg_onaE&sensor=true&language=ar"></script>

<div id="browseCentersTabs">
    <ul>
        <li><a href="#tabs-1">بحث عن مركز</a></li>
        <li><a href="#tabs-2">تصفح المراكز</a></li>
    </ul>
    <div id="tabs-1">
        <div>
            <input type="text" id="searchBox" name="searchBox" />
            <input type="submit" id="searchButton" name="searchButton" value="بحث"/>
        </div>
        <table id="searchResults">
        </table>
    </div>
    <div  id="tabs-2">
        <div>
            <label for="type">اختر نوع المركز</label>
            <select id="type" name="type">
                <option value=""></option>
                <option value="طبيب">طبيب</option>
                <option value="صيدلية">صيدلية</option>
                <option value="مشفى">مشفى</option>
            </select>   
        </div>
        <table id="browseResults">
        </table>
    </div>
</div>
<div id="map_canvas" style="display: none">
</div>

<script>
    $(document).ready(function(){
        // enable auto complete for search keywords
        var keywordsString = '<?php echo $data['keywords']; ?>';
        var keywords = eval('('+ keywordsString + ')');
        $('#searchBox').autocomplete({
            source:keywords
        });
        //
        $("#browseCentersTabs").tabs();
        $('#searchButton').button().click(function (){
            var users;
            $.ajax({
                type: "GET",
                url: "../controllers/BrowseCenters.php?function=searchForUsersForAjax",
                data: {
                    name:$('#searchBox').val()
                },
                success: function(msg)
                {
                    users = eval('('+ msg + ')');
                    $('#searchResults').empty();
                    $('#searchResults').css('display', 'none');
                    if (users.length > 0)
                    {
                        for (var i = 0; i < users.length; i++)
                        {
                            $('#searchResults').append(viewUser(users[i]));
                        }
                    }
                    else
                    {
                        $('#searchResults').append("<tr><td>لا توجد نتائج مطابفة للبحث</td></tr>");
                    }
                    $('#searchResults').effect("bounce", {direction: "up"}, "slow" );
                    setMapLinks();
                }  
            });  
            return false;
        });    
        $('#type').change(function (){
            var users;
            $.ajax({
                type: "GET",
                url: "../controllers/BrowseCenters.php?function=getUsersByType",
                data: {
                    type:$('#type').val()
                },
                success: function(msg)
                {
                    users = eval('('+ msg + ')');
                    $('#browseResults').empty();
                    $('#browseResults').css('display', 'none');
                    if (users.length > 0)
                    {
                        for (var i = 0; i < users.length; i++)
                        {
                            $('#browseResults').append(viewUser(users[i]));
                        }
                    }
                    else
                    {
                        $('#browseResults').append("<tr><td>لا توجد نتائج مطابفة للبحث</td></tr>");
                    }
                    $('#browseResults').effect("bounce", {direction: "up"}, "slow" );
                    setMapLinks();
                }  
            });  
            return false;
        });    
    });
    function viewUser(user)
    {
        var result;
        var br = "<br />";
        var titleClass = "title";
        var contentClass = "content";
        //build the type
        type = "";
        if (user.type != null)
        {
            type += "<span class = '" + titleClass + "'>"+ "النوع" +" : </span>";
            type += "<span class='"+ contentClass +"'>" + user.type + "</span>";
            type += br;
        }
        // build the name
        name = "<span class = '" + titleClass + "'>"+ "الاسم" +" : </span>";
        if (user.firstName != null && user.lastName != null)
        {
            name += "<span class='"+ contentClass +"'>" + user.firstName + " " + user.lastName + "</span>";
            name += br;
        }
        if (user.name != null)
        {
            name += user.name;
            name += br;
        }
        //build the specialization
        specialization = "";
        if (user.specialization != null)
        {
            specialization += "<span class = '" + titleClass + "'>"+ "الاختصاص" +" : </span>";
            specialization += "<span class='"+ contentClass +"'>" + user.specialization + "</span>";
            specialization += br;
        }
        //build the specialization
        specialization = "";
        if (user.specialization != null)
        {
            specialization += "<span class = '" + titleClass + "'>"+ "الاختصاص" +" : </span>";
            specialization += "<span class='"+ contentClass +"'>" + user.specialization + "</span>";
            specialization += br;
        }
        //build the mobile
        mobile = "";
        if (user.mobile != null)
        {
            mobile += "<span class = '" + titleClass + "'>"+ "الجوال" +" : </span>";
            mobile += "<span class='"+ contentClass +"'>" + user.mobile + "</span>";
            mobile += br;
        }
        //build the phone
        phone = "";
        if (user.phone != null)
        {
            phone += "<span class = '" + titleClass + "'>"+ "الهاتف" +" : </span>";
            phone += "<span class='"+ contentClass +"'>" + user.phone + "</span>";
            phone += br;
        }
        //build the adderss
        address = "";
        if (user.address != null)
        {
            address += "<span class = '" + titleClass + "'>"+ "العنوان" +" : </span>";
            address += "<span class='"+ contentClass +"'>" + user.address + "</span>";
            address += br;
        }
        //build the notes
        notes = "";
        if (user.notes != null)
        {
            notes += "<span class = '" + titleClass + "'>"+ "ملاحظات" +" : </span>";
            notes += "<span class='"+ contentClass +"'>" + user.notes + "</span>";
            notes += br;
        }
        // buil the view map link
        mapLink = "";
        if (user.longitude != null && user.latitude != null)
        {
            mapLink += "<span class = '" + titleClass + "'>"+ "الخريطة" +" : </span>";
            mapLink += "<span class='"+ contentClass +"'>" + "<a class='mapLink'href='' longitude='" + user.longitude + "' latitude='" + user.latitude + "'>اضغط هنا</a>" + "</span>";
            mapLink += br;
        }
        //build the final result
        result = "<tr><td>" + type + name + specialization + mobile + phone + address + mapLink + notes + "</td></tr>";
        return result;
    }
    function setMapLinks()
    {
        $('.mapLink').click(function()
        {
            $( "#map_canvas" ).dialog({
                height :500,
                width: 500,
                modal: true,
                draggable : true,
                closeOnEscape: true,
                resizable : true,
                show: "blind",
                hide: "explode"
            });
            var longitude = $(this).attr('longitude');
            var latitude = $(this).attr('latitude');
            initializeMap(longitude, latitude, 17, "map_canvas");
            return false;
        });
    }
    function initializeMap(lat, lng, zoom, divID) 
    {
        var mapOptions = {
            center: new google.maps.LatLng(lat, lng),
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById(divID), mapOptions);               
        return map;
    }
</script>
