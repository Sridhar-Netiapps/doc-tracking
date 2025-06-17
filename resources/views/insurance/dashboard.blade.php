@extends('layouts.insurance-app')
@section('content')

<div class="container-fluid">
  <div class="d-flex">
     <div class="ms-auto">
      <div class="d-flex">
         <a class="nav-link form-btn" href="{{ route('insurance_list') }}"><button class="btn btn-success btn-text p-2">Insurance Leads</button></a>
         <div class="ms-auto">
            <select class="form-control">
               <option>FY : 2025-2026</option>
             </select>
         </div>
      </div>
      
      
       
     </div>
  </div>
  
 <div class="row py-4">
  <div class="col">
    <div class="card card-row1-bg">
      <div class="card-content">
        <div class="card-body">
          <div class="media d-flex">
            <div class="media-body text-left">
              <h3 class="danger">278</h3>
              <span>Total</span>
            </div>
            <div class="align-self-center">
              <i class="icon-rocket danger font-large-2 float-right"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card card-row1-bg">
      <div class="card-content">
        <div class="card-body">
          <div class="media d-flex">
            <div class="media-body text-left">
              <h3 class="success">156</h3>
              <span>Claimed</span>
            </div>
            <div class="align-self-center">
              <i class="icon-user success font-large-2 float-right"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card card-row1-bg">
      <div class="card-content">
        <div class="card-body">
          <div class="media d-flex">
            <div class="media-body text-left">
              <h3 class="warning">64.89 %</h3>
              <span>Conversion Rate</span>
            </div>
            <div class="align-self-center">
              <i class="icon-pie-chart warning font-large-2 float-right"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card card-row1-bg">
      <div class="card-content">
        <div class="card-body">
          <div class="media d-flex">
            <div class="media-body text-left">
              <h3 class="primary">423</h3>
              <span>Rejected</span>
            </div>
            <div class="align-self-center">
              <i class="icon-support primary font-large-2 float-right"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card card-row1-bg">
      <div class="card-content">
        <div class="card-body">
          <div class="media d-flex">
            <div class="media-body text-left">
              <h3 class="primary">43</h3>
              <span>Progress</span>
            </div>
            <div class="align-self-center">
              <i class="icon-support primary font-large-2 float-right"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<div class="row py-4">
	<div class="col">
	  <div class="card shadow-lg p-3 mb-5 bg-white rounded">
	  	<div id="piechart"></div>
	  </div>	
	</div>

	<div class="col">
		<div class="card shadow-lg p-3 mb-5 bg-white rounded">
		 <div id="linechart"></div>
		</div>
	</div>
</div>

<div class="row py-4">
	<div class="col">
	  <div class="card shadow-lg p-3 mb-5 bg-white rounded">
	  	<div id="regionwise"></div>
	  </div>	
	</div>

	<div class="col">
		
	</div>

	<div class="col">
		
</div>

</div>

<script type="text/javascript" nonce="wUDPhZ1Z60inspnMCukimCi">
	var options = {
      series: [44, 55, 13, 43],
      chart: {
      width: 400,
      type: 'pie',
    },
    labels: ['Birla', 'Bajaj', 'HDFC', 'MAX Life'],
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          width: 200,
        },
        legend: {
          position: 'right'
        }
      }
    }]
    };

    var chart = new ApexCharts(document.querySelector("#piechart"), options);
    chart.render();


     var options_line = {
          series: [{
          name: 'Claimed',
          data: [55,20,73,84,25,64,17,55,20,73,84,25]
        }],
          chart: {
          type: 'area',
          stacked: false,
          height: 250,
          zoom: {
            type: 'x',
            enabled: true,
            autoScaleYaxis: true
          },
          toolbar: {
            autoSelected: 'zoom'
          }
        },
        colors:['#3EC7A1'],
        dataLabels: {
          enabled: false
        },
        markers: {
          size: 0,
        },
        title: {
          text: 'Claim Movement',
          align: 'left'
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 0.5,
            opacityTo: 0,
            stops: [0, 90, 100]
          },
        },
        yaxis: {
          labels: {
            formatter: function (val) {
              return (val / 1000000).toFixed(0);
            },
          },
          title: {
            text: 'Price'
          },
        },
        xaxis: {
         // type: 'datetime',
         categories: ['Apr','May','June','July','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar'
          ],
        },
        tooltip: {
          shared: false,
          y: {
            formatter: function (val) {
              return (val / 1000000).toFixed(0)
            }
          }
        }
        };

        var linechart = new ApexCharts(document.querySelector("#linechart"), options_line);
        linechart.render();


    var stackoptions = {
          series: [{
          name: 'Claimed',
          data: [44, 55, 41, 67]
        }, {
          name: 'Rejected',
          data: [13, 23, 20, 8]
        }, {
          name: 'In Progress',
          data: [11, 17, 15, 15]
        }, /*{
          name: 'South',
          data: [21, 7, 25, 13]
        }*/],
          chart: {
          type: 'bar',
          height: 250,
          stacked: true,
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
        },
         colors: ['#3EC7A1','#FF0B55','#FFD63A'],
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],
        plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 10,
            borderRadiusApplication: 'end', // 'around', 'end'
            borderRadiusWhenStacked: 'last', // 'all', 'last'
            dataLabels: {
              total: {
                enabled: true,
                style: {
                  fontSize: '13px',
                  fontWeight: 900
                }
              }
            }
          },
        },
        xaxis: {
          type: 'text',
          categories: ['East','West','North','South'
          ],
        },
        legend: {
          position: 'right',
          offsetY: 40
        },
        fill: {
          opacity: 1
        }
        };

        var stackchart = new ApexCharts(document.querySelector("#regionwise"), stackoptions);
        stackchart.render();    
</script>
@endsection	