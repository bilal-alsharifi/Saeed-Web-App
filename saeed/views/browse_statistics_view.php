<script type="text/javascript" src="../js/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="../js/jqplot/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="../js/jqplot/jqplot.logAxisRenderer.min.js"></script>
<script type="text/javascript" src="../js/jqplot/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="../js/jqplot/jqplot.cursor.min.js"></script>
<link rel="stylesheet" type="text/css" href="../css/jqplot/jquery.jqplot.min.css" />

<div id="chart">
</div>
<button id="button-reset">اعادة المخطط للحالة الافتراضية</button>
<script>
    $(document).ready(function(){
        var valuesString = '<?php echo $data['graphArray']; ?>';
        setGraph(valuesString);
    });
    function setGraph(valuesString)
    {
        var valuesString 
        var values = valuesString.split(";", -1);
        for (var i=0; i<values.length; i++)
        {
            values[i] = values[i].split(",", -1);
        }      
        // the values must be in this format $.jqplot ('chart', [[[4,8],[10,6]]]);
        // note : dates must be passed as strings
        var plot = $.jqplot('chart', [values], {
            title:'الخط البياني لمدفوعات الشركة',
            seriesDefaults: {
                showMarker:false,
                pointLabels: { show:true }
            },
            axes:{
                xaxis:{
                    label:'التاريخ',
                    renderer:$.jqplot.DateAxisRenderer,
                    tickOptions:{formatString:'%b %#d, %y'}
                    
                },
                yaxis:{
                    label:'المدفوعات',
                    renderer:$.jqplot.LogAxisRenderer
                }
            },
            highlighter: {
                show: true
            },
            cursor: {
                show: true,
                zoom:true,
                showTooltip:true
            }
        });
        $('#button-reset').button().click(function() { plot.resetZoom() });
    }
    
</script>