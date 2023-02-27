 
<div class="col-lg-6 m-b-30">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Informe de pedidos de los últimos 6 meses</div>

            <div class="card-controls">
                <a href="#" class="js-card-refresh icon"> </a>
            </div>
        </div>

        <div class="card-body">
            <div id="chart-01"></div>
        </div>

        <div class=""></div>
    
        <div class="card-footer">
            <div class="d-flex  justify-content-between">
                <h6 class="m-b-0 my-auto">
                    <span class="opacity-75">
                        <i class="mdi mdi-information"></i> Quiere ver más datos
                    </span>
                </h6>
                <a href="report" class="btn btn-white shadow-none">Obtener informe completo</a>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-6  m-b-30">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Negocios Top Trending</div>

            <div class="card-controls">
                <a href="#" class="js-card-refresh icon"> </a>
            </div>
        </div>

        <div class="card-body">
            <div id="chart-02"></div>
        </div>
        
        <div class=""></div>
        
        <div class="card-footer">
            <div class="d-flex  justify-content-between">
                <h6 class="m-b-0 my-auto">
                    <span class="opacity-75"> 
                        <i class="mdi mdi-information"></i> Quiere ver más datos
                    </span>
                </h6>
                <a href="report" class="btn btn-white shadow-none">Obtener informe completo</a>
            </div>
        </div>
    </div>
</div> 

 
<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", () => { 


        if ($("#chart-01").length) {

            var options = {
                chart: {
                    type: 'area',
                    stacked: false,
                    zoom: {
                        type: 'x',
                        enabled: true,
                        autoScaleYaxis: true
                    },
                    toolbar: {
                        autoSelected: 'zoom'
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        endingShape: 'rounded',
                        columnWidth: '55%',
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: 'Pedidos Cancelados',
                    data: [
                        "<?php echo $admin->chart(6)['cancel']; ?>", 
                        "<?php echo $admin->chart(5)['cancel']; ?>",
                        "<?php echo $admin->chart(4)['cancel']; ?>", 
                        "<?php echo $admin->chart(3)['cancel']; ?>", 
                        "<?php echo $admin->chart(2)['cancel']; ?>", 
                        "<?php echo $admin->chart(1)['cancel']; ?>", 
                        "<?php echo $admin->chart(0)['cancel']; ?>"
                    ]
                    
                },
                {
                    name: 'Pedidos Completos',
                    data: [
                        "<?php echo $admin->chart(6)['order']; ?>", 
                        "<?php echo $admin->chart(5)['order']; ?>",
                        "<?php echo $admin->chart(4)['order']; ?>", 
                        "<?php echo $admin->chart(3)['order']; ?>", 
                        "<?php echo $admin->chart(2)['order']; ?>", 
                        "<?php echo $admin->chart(1)['order']; ?>", 
                        "<?php echo $admin->chart(0)['order']; ?>"
                    ]
                }],
                xaxis: {
                    categories: [
                        '<?php echo $admin->getMonthName(6); ?>', 
                        '<?php echo $admin->getMonthName(5); ?>', 
                        '<?php echo $admin->getMonthName(4); ?>', 
                        '<?php echo $admin->getMonthName(3); ?>', 
                        '<?php echo $admin->getMonthName(2); ?>', 
                        '<?php echo $admin->getMonthName(1); ?>', 
                        '<?php echo $admin->getMonthName(0); ?>'
                    ],
                },
                yaxis: {
                    title: {
                        text: ''
                    }
                },
                fill: {
                    opacity: 1

                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                }
            }

            new ApexCharts(document.querySelector("#chart-01"), options).render();
        }

        if ($("#chart-02").length) {
            var options = {
                chart: {

                    type: 'bar',
                },
                colors: "#7140d1",
                plotOptions: {
                    bar: {
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    data: [
                        "<?php echo $admin->getStoreData($schart,0,'order'); ?>",
                        "<?php echo $admin->getStoreData($schart,1,'order'); ?>",
                        "<?php echo $admin->getStoreData($schart,2,'order'); ?>",
                        "<?php echo $admin->getStoreData($schart,3,'order'); ?>",
                        "<?php echo $admin->getStoreData($schart,4,'order'); ?>",
                        "<?php echo $admin->getStoreData($schart,5,'order'); ?>",
                        "<?php echo $admin->getStoreData($schart,6,'order'); ?>",
                        "<?php echo $admin->getStoreData($schart,7,'order'); ?>",
                        "<?php echo $admin->getStoreData($schart,8,'order'); ?>",
                        "<?php echo $admin->getStoreData($schart,9,'order'); ?>"
                    ]
                }],
                xaxis: {
                    categories: [
                        "<?php echo $admin->getStoreData($schart,0,'name'); ?>",
                        "<?php echo $admin->getStoreData($schart,1,'name'); ?>",
                        "<?php echo $admin->getStoreData($schart,2,'name'); ?>",
                        "<?php echo $admin->getStoreData($schart,3,'name'); ?>",
                        "<?php echo $admin->getStoreData($schart,4,'name'); ?>",
                        "<?php echo $admin->getStoreData($schart,5,'name'); ?>",
                        "<?php echo $admin->getStoreData($schart,6,'name'); ?>",
                        "<?php echo $admin->getStoreData($schart,7,'name'); ?>",
                        "<?php echo $admin->getStoreData($schart,8,'name'); ?>",
                        "<?php echo $admin->getStoreData($schart,9,'name'); ?>"
                    ],
                },
                yaxis: {},
                tooltip: {}
            };

            new ApexCharts(document.querySelector("#chart-02"), options).render();

        }
        
    });
</script>