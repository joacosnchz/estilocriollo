var easyads_chart;

function data_select(widget_name, data) {
    var $select = $('<select>');
    $.each(data.options, function(i, option){
        var $option = $('<option>', {value: option.id_hook});
        if (data.selected === option.id_hook) {
            $option.prop('selected', true);
        }
        $option.text(option.name);
        $select.append($option);
    });
    
    $select.change(function(){
        var change_id_hook = $(this).val();
        var chart_stats = null;
        
        $.each(data.options, function(i, hook){
            if (hook.id_hook == change_id_hook) {
                chart_stats = hook.stats;
                return false; // break
            }
        });
        
        if (chart_stats) {
            d3.select('#'+data.chart+' svg')
                .datum(chart_stats)
                .call(easyads_chart);
        }
    });
    
    $('#'+data.el).append($select);
}

function easyads_line_chart(widget_name, chart_details){
    nv.addGraph(function() {
        var chart = nv.models.lineChart().useInteractiveGuideline(true);

        chart.xAxis.tickFormat(function(d) {
            return d3.time.format('%m/%d/%y')(new Date(d * 1000))
        });

        chart.yAxis.tickFormat(d3.format(',r'));
        
        easyads_chart = chart;
        console.log(chart_details.data);
        d3.select('#'+chart_details.chart_type+' svg')
                .datum(chart_details.data)
                .transition().duration(500)
                .call(chart);

        nv.utils.windowResize(chart.update);

        return chart;
    });
}