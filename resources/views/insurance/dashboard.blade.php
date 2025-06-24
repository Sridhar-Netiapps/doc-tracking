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
              <h3 class="danger">{{ $total}}</h3>
              <span>Intimation Received</span>
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
              <h3 class="success">{{ $claimed}}</h3>
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
              <h3 class="warning">{{ $noneligible }}</h3>
              <span>Non Eligible</span>
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
              <h3 class="primary">{{ $rejected}}</h3>
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
              <h3 class="primary">{{ $inprogress}}</h3>
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
  @if(Auth::user()->branch_id == '1100')
	<div class="col-4">
	  <div class="card shadow-lg p-3 mb-5 bg-white rounded">
	  	<div id="regionwise"></div>
	  </div>	
	</div>
  @endif
	<div class="col-4">
		<div class="card shadow-lg p-3 mb-5 bg-white rounded">
      <div id="causeofdeath"></div>
    </div>
	</div>

	<div class="col">
		
</div>

</div>

<script type="text/javascript" nonce="wUDPhZ1Z60inspnMCukimCi">
	var options = {
      series: @json($partnerChart['counts']),
      chart: {
      width: 400,
      type: 'pie',

    },
    labels: @json($partnerChart['names']),
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


    /* var options_line = {
          series: [{
            name: 'Claimed',
            data: @json($claimedAmount),
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
          },
        },
        colors:['#3EC7A1'],
        dataLabels: {
          enabled: false
        },
        markers: {
          size: 0,
        },
        title: {
          text: 'Claimed Amount',
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
          tickAmount: 5,
          labels: {
            formatter: function (val) {
              return (val ).toFixed(0);
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
              return (val ).toFixed(0);
            }
          }
        }
        };

        

        var linechart = new ApexCharts(document.querySelector("#linechart"), options_line);
        linechart.render();*/

        var options_line = {
        series: [{
          name: 'Leads count',
          type: 'column',
          data: @json($claimchart['0'])
        }, {
          name: 'Settled Amount',
          type: 'area',
          data: @json($claimchart['1'])
        }, {
          name: 'Claimed Amount',
          type: 'line',
          data: @json($claimchart['2'])
        }],
        chart: {
          height: 250,
          type: 'line',
          stacked: false,
        },
        stroke: {
          width: [0, 2, 5],
          curve: 'smooth'
        },
        plotOptions: {
          bar: {
            columnWidth: '50%'
          }
        },
        fill: {
          opacity: [0.85, 0.25, 1],
          gradient: {
            inverseColors: false,
            shade: 'light',
            type: "vertical",
            opacityFrom: 0.85,
            opacityTo: 0.55,
            stops: [0, 100, 100, 100]
          }
        },
        labels: ['Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
        markers: {
          size: 0
        },
        xaxis: {
          type: 'category' // changed from 'datetime' to 'category'
        },
        yaxis: {
          title: {
            text: 'Points',
          }
        },
        tooltip: {
        shared: true,
        intersect: false,
        y: {
          formatter: function (value, { series, seriesIndex, dataPointIndex, w }) {
            if (typeof value !== "undefined") {
              if (seriesIndex === 0) {
                // TEAM A – plain number with "points"
                return value.toFixed(0) + " Lead(s)";
              } else {
                // TEAM B and TEAM C – formatted as rupees
                return "₹" + value.toFixed(2);
              }
            }
            return value;
          }
        }
      }
      };

      var linechart = new ApexCharts(document.querySelector("#linechart"), options_line);
        linechart.render();



    var stackoptions = {
          series: [{
          name: 'Claimed',
          data: @json($regionChart['0']),
        },{
          name: 'Non-Eligible',
          data: @json($regionChart['1']),
        }, {
          name: 'Rejected',
          data: @json($regionChart['2']),
        }, {
          name: 'In Progress',
          data: @json($regionChart['3']),
        },],
          chart: {
          type: 'bar',
          height: 250,
          stacked: true,

          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          },

        },
         colors: ['#3EC7A1','#a9a39f','#FF0B55','#0000FF'],
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
          categories: ['North','South','East','West'],
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


        var options_death = {
          series: [{
          name: 'Claimed',
          data: @json($claimedAmount),
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
          },
        },
        colors:['#3EC7A1'],
        dataLabels: {
          enabled: false
        },
        markers: {
          size: 0,
        },
        title: {
          text: 'Claimed Amount',
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
          tickAmount: 5,
          labels: {
            formatter: function (val) {
              return (val ).toFixed(0);
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
              return (val ).toFixed(0);
            }
          }
        }
        };

        var death_linechart = new ApexCharts(document.querySelector("#causeofdeath"), options_death);
        death_linechart.render();  
</script>
@endsection	