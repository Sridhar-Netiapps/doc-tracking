@extends('layouts.insurance-app')
@section('content')

<div class="container-fluid">
  <div class="d-flex">
     <div class="ms-auto">
      <div class="d-flex">
         <a class="atags form-btn" href="{{ route('list') }}"><button class="btn btn-success ">Insurance Form</button></a>
         <div class="ms-auto">
            <select class="form-control form-select">
               <option>FY : 2025-2026</option>
             </select>
         </div>
      </div>
      
      
       
     </div>
  </div>
  
 <div class="row py-4">
  <div class="col-xl-3 col-sm-6 col-12">
    <div class="card">
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
  <div class="col-xl-3 col-sm-6 col-12">
    <div class="card">
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

  <div class="col-xl-3 col-sm-6 col-12">
    <div class="card">
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
  <div class="col-xl-3 col-sm-6 col-12">
    <div class="card">
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

</div>

<div class="row py-4">
	<div class="col">
	  <div class="card">
	  	<div id="piechart"></div>
	  </div>	
	</div>

	<div class="col">
		<div class="card">
		 <div id="linechart"></div>
		</div>
	</div>
</div>

<div class="row py-4">
	<div class="col">
	  <div class="card">
	  	<div id="regionwise"></div>
	  </div>	
	</div>

	<div class="col">
		<div class="card">
		 <div ></div>
		</div>
	</div>

	<div class="col">
		<div class="card">
		 <div ></div>
		</div>
	</div>
</div>

</div>

<script type="text/javascript" nonce="wUDPhZ1Z60inspnMCukimCi">
	var options = {
      series: [44, 55, 13, 43, 22],
      chart: {
      width: 400,
      type: 'pie',
    },
    labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
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
          name: 'XYZ MOTORS',
          data: [55,20,73,84,25,64,17]
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
          name: 'West',
          data: [44, 55, 41, 67, 22, 43]
        }, {
          name: 'East',
          data: [13, 23, 20, 8, 13, 27]
        }, {
          name: 'North',
          data: [11, 17, 15, 15, 21, 14]
        }, {
          name: 'South',
          data: [21, 7, 25, 13, 22, 8]
        }],
          chart: {
          type: 'bar',
          height: 200,
          stacked: true,
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
        },
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
          type: 'datetime',
          categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
            '01/05/2011 GMT', '01/06/2011 GMT'
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