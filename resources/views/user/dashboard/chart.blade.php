<!-- Reports -->
<div class="col-12">
    <div class="card">

      <div class="card-body">
        <h5 class="card-title">Informe de pedido de los últimos 6 meses</h5>

        <!-- Line Chart -->
        <div id="reportsChart"></div>

        <script>
          document.addEventListener("DOMContentLoaded", () => {
            new ApexCharts(document.querySelector("#reportsChart"), {
              series: [{
                    name: 'Pedidos completados',
                    data: [
                        "<?php echo $admin->chart(6,1)['order']; ?>", 
                        "<?php echo $admin->chart(5,1)['order']; ?>", 
                        "<?php echo $admin->chart(4,1)['order']; ?>", 
                        "<?php echo $admin->chart(3,1)['order']; ?>", 
                        "<?php echo $admin->chart(2,1)['order']; ?>", 
                        "<?php echo $admin->chart(1,1)['order']; ?>", 
                        "<?php echo $admin->chart(0,1)['order']; ?>"
                    ]
                },{
                    name: 'Pedidos cancelados',
                    data: [
                        "<?php echo $admin->chart(6,1)['cancel']; ?>", 
                        "<?php echo $admin->chart(5,1)['cancel']; ?>", 
                        "<?php echo $admin->chart(4,1)['cancel']; ?>", 
                        "<?php echo $admin->chart(3,1)['cancel']; ?>", 
                        "<?php echo $admin->chart(2,1)['cancel']; ?>", 
                        "<?php echo $admin->chart(1,1)['cancel']; ?>", 
                        "<?php echo $admin->chart(0,1)['cancel']; ?>"
                    ]
                }],
                chart: {
                  type: 'bar',
                  height: 350
                },
                plotOptions: {
                  bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
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
              xaxis: {
                categories: ['<?php echo $admin->getMonthName(6); ?>', '<?php echo $admin->getMonthName(5); ?>', '<?php echo $admin->getMonthName(4); ?>', '<?php echo $admin->getMonthName(3); ?>', '<?php echo $admin->getMonthName(2); ?>', '<?php echo $admin->getMonthName(1); ?>', '<?php echo $admin->getMonthName(0); ?>'],
              }
            }).render();
          });
        </script>
        <!-- End Line Chart -->

      </div>

    </div>
  </div><!-- End Reports -->