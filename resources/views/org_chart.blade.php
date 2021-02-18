<style>
    #orgChart{
        width:100%;
        height:100%;
        background-color: white;
    }
</style>
<script src="{{ asset('js/orgchart.js') }}"></script>
<script src="{{asset('js/jquery.min.js')}}"></script>
<div class="top-heading">
    
    <div class="col-md-12" style="background-color:white;padding:20px 10px 10px 10px;border-radius: 4px;">  
        <select  id="selectTemplate">
            <option>luba</option>
            <option>olivia</option>
            <option>derek</option>
            <option>diva</option>
            <option>mila</option>
            <option>polina</option>
            <option>mery</option>
            <option>rony</option>
            <option>belinda</option>
            <option>ula</option>
            <option>ana</option>
            <option>isla</option>
            <option>deborah</option>
        </select>
    </h5>
    </div>
   

    
 
    <div id="orgChart"/>
</div>
<script>
    
    let jsonData  =  JSON.parse({!! json_encode($datas) !!});
    console.log(jsonData);
    outputData = [];
    $.each(jsonData , function(index, item){
        outputData.push({
            id: item.id,
            pid: item.pid,
            name: item.name,
            position: item.position,
            img: item.img,
        });
    });

    var chart;
    window.onload = function () {
    var chart = new OrgChart(document.getElementById("orgChart"), {
            template: "luba",
            menu: {
                pdf: { text: "Export PDF" },
                png: { text: "Export PNG" },
                svg: { text: "Export SVG" },
                csv: { text: "Export CSV" }
            },
            nodeMenu: {
                pdf: { text: "Export PDF" },
                png: { text: "Export PNG" },
                svg: { text: "Export SVG" }
            },
            nodeBinding: {
                field_0: "name",
                field_1: "position",
                img_0 : 'img'
            },       
            nodes: outputData
        });
        document.getElementById("selectTemplate").addEventListener("change", function () {
        chart.config.template = this.value;
        chart.draw();
});
};


</script>