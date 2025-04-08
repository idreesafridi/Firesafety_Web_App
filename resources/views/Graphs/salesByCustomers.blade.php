@extends('layouts.app')
@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Sales by Customers</h1>
    </div>
</div>


<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
				<div class="col-xl-12 col-12">
					<div class="box-content">
						<h4 class="box-title">Summary ({{ $type }})</h4>
						<div class="dropdown js__drop_down">
							<a href="#" class="dropdown-icon fas fa-ellipsis-v js__drop_down_button"></a>
							<ul class="sub-menu">
								<li><a href="{{ route('salesByCustomers', ['type'=>1]) }}">1 month summary</a></li>
								<li><a href="{{ route('salesByCustomers', ['type'=>3]) }}">3 months summary</a></li>
								<li><a href="{{ route('salesByCustomers', ['type'=>6]) }}">6 months summary</a></li>
								<li><a href="{{ route('salesByCustomers', ['type'=>12]) }}">12 months summary</a></li>
								<li class="split"></li>
								<li><a href="{{ route('salesByCustomers') }}">All time summary</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
            
            @php $type = request('type');@endphp
			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content">
						<h4 class="box-title">Sales by Customers</h4>
						<!-- /.box-title -->
						<div id="chartSalesByCustomers" style="width:100%;max-width:100%;"></div>
						<!-- /#smil-animation-chartist-chart.chartist-chart -->
					</div>
				</div>
			</div>

			<footer class="footer">
				<ul class="list-inline">
					<li>2020 © Al Akhzir Tech.</li>
				</ul>
			</footer>
		</div>
		<!-- /.main-content -->
</div>

<!-- Sales by Product -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript">
 var options = {
	  series: [{
	  data: [
	  		@if(SalesByCustomer($type, 100)->count() > 0)
		  	@foreach(SalesByCustomer($type, 100) as $customer)
		  	'{{ getInvoiceSales($customer->id) }}',
		  	@endforeach
		  	@endif
	  	]
	}],
	 chart: {
	  	type: 'bar',
	  	height: {{ SalesByProduct($type, 100)->count()*30 }},
	  	events: {
            dataPointSelection: function(event, chartContext, opts) {
                switch(opts.w.config.xaxis.categories[opts.dataPointIndex]) {
                    @if(SalesByCustomer($type, 100)->count() > 0)
				  	@foreach(SalesByCustomer($type, 100) as $customer)
				  	case '{{ customer($customer->customer_id)->company_name }}':
                        window.open("{{ route('customer.invoices', $customer->customer_id) }}");
				  	@endforeach
				  	@endif
                }
            }
        }
	},
	plotOptions: {
	  bar: {
	    borderRadius: 4,
	    horizontal: true,
	  }
	},
	dataLabels: {
	  enabled: true
	},
	xaxis: {
	  categories: [
	  	@if(SalesByCustomer($type, 100)->count() > 0)
	  	@foreach(SalesByCustomer($type, 100) as $customer)
	  	'{{ customer($customer->customer_id)->company_name }}',
	  	@endforeach
	  	@endif
	  ],
	}
	};

	var chart = new ApexCharts(document.querySelector("#chartSalesByCustomers"), options);
	chart.render();
</script>

@endsection
