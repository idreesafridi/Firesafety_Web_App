<table>
    @if($branches->count() > 0)
    @foreach($branches as $branch)
        @if($branch->expenses->count() > 0)
        <thead>
            <tr>
                <th colspan="3">
                    <h1>Expense Report {{ $branch->branch_name }}</h1>
                </th>
            </tr>


            </thead>
            @for($i = $begin; $i <= $end; $i->modify('+1 day'))
                <?php
                $dated = $i->format("Y-m-d");
                $expenses = App\Models\Expense::where('branch_id', $branch->id)->where('dated', $dated)->latest()->get(); 
                ?>
                @if($expenses->count() > 0)
                    <tbody>
                        <tr>
                            <th>Date</th>
                            <th colspan="2">{{ $i->format("D d M Y") }}</th>
                        </tr>
                        <tr>
                            <th>EMPLOYEE INFORMATION:</th>
                            <th colspan="2"></th>
                        </tr>
                        <tr>
                            <th>S.No</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                        @php $count=1; @endphp
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ ($expense->category) ? $expense->category->name : '' }} ({{ $expense->description }} = {{ $expense->amount }})</td>
                                <td>Rs {{ $expense->amount }}</td>
                            </tr>

                            <tr colspan="3"></tr>
                            <tr colspan="3"></tr>
                        @php $count++; @endphp
                        @endforeach
                    </tbody>
                @endif
            @endfor
        @endif
    @endforeach
    @endif
</table>