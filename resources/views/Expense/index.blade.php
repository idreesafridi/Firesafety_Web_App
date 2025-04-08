@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Expenses</h1>
    </div>
</div>



    <div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">    
			    <div class="col-12">
    				<div class="box-content">
                    
    					<div class="row">
    						<div class="col-xl-12">
                                <table id="example2" class="table table-striped table-bordered display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Previous Balance</th>
                                            <th>Cash Received</th>
                                            <th>Current in Hand</th>
                                            <th>Expense</th>
                                            <th>Remaining Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Previous Balance</th>
                                            <th>Cash Received</th>
                                            <th>Current in Hand</th>
                                            <th>Expense</th>
                                            <th>Remaining Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($Expenses as $expense )
                                        <tr>
                                            <td>{{ date("m-d-Y", strtotime($expense->dated)) }}</td>
                                            <td>{{$expense->pbalance}}</td>
                                            <td>{{$expense->cashreceived}}</td>
                                            <td>{{$expense->cashinhand}}</td>
                                            <td>{{$expense->amount}}</td>
                                            <td>{{$expense->remainingbalance}}</td>
                                            <td>
                                                <a href="{{ route('Expenses.edit',$expense->id) }}" style="display: inline-block;color: #000">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </a>
                                                <a href="{{ route('Expenses.show',$expense->dated) }}" target="_blank" style="display: inline-block;color: #000">
                                                    <i class="fa fa-download" aria-hidden="true"></i>
                                                </a>
                                                <form action="{{ route('Expenses.destroy', $expense->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this expense?');" style="display: inline-block;background: transparent; border:none;">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>           
                           <!-- Add this inside your view where you want to provide the year selection for downloading -->
<form method="get" action="{{ route('downloadExpenses') }}">
    @csrf
    <label for="year">Select Year:</label>
    <select name="year" id="year">
        @for ($y = date('Y'); $y >= 2010; $y--)
            <option value="{{ $y }}">{{ $y }}</option>
        @endfor
    </select>
    <button type="submit" class="btn btn-primary">Download Expenses</button>
</form>

                    </div>
                </div>
            </div>
			<footer class="footer">
				<ul class="list-inline">
					<li>2020 © Al Akhzir Tech.</li>
				</ul>
			</footer>
		</div>
    </div>

<script>
    function searchTableByDate() {
        const input = document.getElementById('searchBar');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('table-container');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
            const td = tr[i].getElementsByTagName('td')[0]; // Get the first column (Date)
            if (td) {
                const txtValue = td.textContent || td.innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    }
</script>
@endsection
