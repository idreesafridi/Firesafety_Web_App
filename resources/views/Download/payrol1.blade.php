<!DOCTYPE html>
<html>
<head>
	<title>Payroll</title>
	<style type="text/css">

body {
			background: #f0f0f0;
			width: 100vw;
			height: 50vh;
			display: flex;
			justify-content: center;
		    padding: 20px;
		    height: 100%;
			
		}

		@import url('https://fonts.googleapis.com/css?family=Roboto:200,300,400,600,700');
		* {
			font-family: 'Roboto', sans-serif;
			font-size: 12px;
			color: #444;
			
		}

		#payslip {
			width: calc( 8.5in - 80px );
			height: calc( 11in - 60px );
			background: #fff;
			padding: 30px 40px;
			margin-top:90px;

		}
		#title {
			margin-bottom: 20px;
			font-size: 30px;
			font-weight: 600;
		}
		#scope {
			border-top: 1px solid #ccc;
			border-bottom: 1px solid #ccc;
			padding: 7px 0 4px 0;
			display: flex;
			justify-content: space-around;
			border: 2px solid #000; /* Add a 1px solid black border */

		}
		#scope > .scope-entry {
			text-align: center;
		}
		#scope > .scope-entry > .value {
			font-size: 14px;
			font-weight: 700;
		}
		#title {
		display: flex;
		justify-content: center;
		align-items: center;

		}
		#amount {
		margin-top: 2;
		}
		
		.content {
			display: flex;
			border-bottom: 1px solid #ccc;
			height: 700px;
			border: 1px solid #000; /* Add a 1px solid black border */
			
			
		}
		.content .left-panel {
			border-right: 1px solid #ccc;
			min-width: 200px;
			padding: 20px 16px 0 0;
			height: 680px;
			border: 1px solid #000; /* Add a 1px solid black border */
		}
		.content .right-panel {
			width: 100%;
			padding: 10px 0  0 16px;
			height: 690px;
			border: 1px solid #000; /* Add a 1px solid black border */
		}

		#employee {
			text-align: center;
			margin-bottom: 20px;
		}
		#employee #name {
			font-size: 15px;
			font-weight: 700;
		}

		#employee #email {
			font-size: 11px;
			font-weight: 300;
		}

		.details, .contributions, .ytd, .gross {
			margin-bottom: 20px;
			
			
		}

		.details .entry, .contributions .entry, .ytd .entry {
			display: flex;
			justify-content: space-between;
			margin-bottom: 6px;
			border: 2px solid #000; /* Add a 1px solid black border */

		}

		.details .entry .value, .contributions .entry .value, .ytd .entry .value {
			font-weight: 700;
			max-width: 130px;
			text-align: right;
					
		}

		.gross .entry .value {
			font-weight: 700;
			text-align: right;
			font-size: 16px;
		}

		.contributions .title, .ytd .title, .gross .title {
			font-size: 15px;
			font-weight: 700;
			border-bottom: 1px solid #ccc;
			padding-bottom: 4px;
			margin-bottom: 6px;
		}

		.content .right-panel .details {
			width: 100%;
		}

		.content .right-panel .details .entry {
			display: flex;
			padding: 0 10px;
			margin: 6px 0;
		}

		.content .right-panel .details .label {
			font-weight: 700;
			width: 120px;
			
		}

		.content .right-panel .details .detail {
			font-weight: 600;
			width: 130px;
		}

		.content .right-panel .details .rate {
			font-weight: 400;
			font-style: italic;
			width: 80px;
			letter-spacing: 1px;
		}

		.content .right-panel .details .amount {
			text-align: right;
			font-weight: 700;
			width: 90px;
		}

		.content .right-panel .details .net_pay div, 
		.content .right-panel .details .nti div {
			font-weight: 600;
			font-size: 12px;
			
		}

		.content .right-panel .details .net_pay, 
		.content .right-panel .details .nti {
			padding: 3px 0 2px 0;
			margin-bottom: 10px;
			border: px solid #000; /* Add a 1px solid black border */

		}
		.header, .header-space
            {
                /*height: 162px!important;*/
                height: 134px!important;
            }

            .footer, .footer-space
            {
                height: 180px!important;
                /*height: 217px!important;*/
            }

            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
            }
            .footer {
                position: fixed;
                bottom: 0; 
                left: 0;
                right: 0;
            }
            html, body {
                width: 210mm;
                height: 250mm; /* height: 297mm; */
                font-size:12px!important;
              }
            @page {
              size: A4;
              margin: 0;
              margin-top: 0;
              margin-bottom: 0;
            }
			@media print {
			html, body {
				width: 210mm;
				height: 250mm; /* height: 297mm; */
			}
		}
		#descriptionDiv p{
			margin-bottom: 0!important;
		}
		h1.amount-class {
			margin-top: 1px;
		}
		.entry.custom-class {
		background-color: bisque;
	   }
	   .entry.custom-sign {
       height: 35px;
       }
	   .label.custom-sign {
			margin-top: 18px;
		}
		.amount.custom-sign {
			margin-top: 18px;
		}
        .container.elevated {
            position: relative;
            top: -60px; /* Adjust the value to move the section higher */
		}
		div#title {
            margin-top: 70px !important;
        }
		.deduction-rate {
			margin-right: 100px;
		}
		.deductionhalfsalary-rate {
			margin-right: 86px;
		}
		.overtime-rate {
    	margin-right: 58px;
	    }
	    .nighthalf-rate {
	        margin-right: 77px;
	    }
	    .nightfull-rate {
	        margin-right: 77px;
	    }
	    
	</style>
</head>
<body  onload="window.print()">

	<div id="payslip">
		<div id="title">Payslip</div>
		<div id="scope">
			<div class="scope-entry">
				<div class="title">Date</div>
				<div class="value">{{ $Salary->dated }}</div>
			</div>
			<div class="scope-entry">
				<div class="title">PAY Month</div>
				<div class="value">{{ date('F', strtotime($Salary->month)) }}</div>
			</div>
		</div>
		<div class="content">
			<div class="left-panel">
				<div id="employee">
					<div id="name">
						{{$staff->name}}
					</div>
					<div id="email">
						{{$staff->type}}
					</div>
				</div>
				<div class="details">
					<div class="entry">
						<div class="label">Employee Name</div>
						<div class="value">{{$staff->name}}</div>
					</div>
					<div class="entry">
						<div class="label">Phone</div>
						<div class="value">{{$staff->phone}}</div>
					</div>
					<div class="entry">
						<div class="label">Email</div>
						<div class="value">{{$staff->email}}</div>
					</div>
					<div class="entry">
						<div class="label">Branch</div>
						<div class="value">{{$staff->branch}}</div>
					</div>
					<div class="entry">
						<div class="label">Designation</div>
						<div class="value">{{$staff->type}}</div>
					</div>
					<div class="entry">
						<div class="label">Address</div>
						<div class="value">{{$staff->address}}</div>
					</div>
				</div>

				<div class="contributions">
					<div class="title">Bank Details</div>
					<div class="entry">
						<div class="label">Bank</div>
						<div class="value">{{$staff->bank}}</div>
					</div>
					<div class="entry">
						<div class="label">Account No</div>
						<div class="value">{{$staff->account_no}}</div>
					</div>
				</div>
			</div>
			<div class="right-panel">
				<div class="details">
					<div class="basic-pay">
						<div class="entry">
							<div class="label">Salary</div>
							<div class="detail"></div>
							<div class="rate"></div>
							<div class="amount">{{$Salary->salary}}</div>
						</div>
					</div>
					<div class="salary">
						<div class="entry">
							<div class="label">Bike Maintenance</div>
							<div class="detail"></div>
							<div class="rate"></div>
							<div class="amount">{{$staff->bike_maintenance}}</div>
						</div>
						<div class="entry">
							<div class="label">Over Time</div>
							<div class="detail"></div>
							<div class="overtime-rate"><?php echo $Salary->over_time . " hours"; ?> </div>
							<?php
							// Assuming $Salary and $staff are already defined with valid values.
							// Calculate the total number of days in the given month.
							$workingdaypermonth = date('t', strtotime($Salary->month));
							$workinghourperday = 10;

							if (is_numeric($staff->salary) && $workingdaypermonth > 0) {
								$monthsalary = $staff->salary;
								$monthhourperday = $monthsalary / $workingdaypermonth;
								$overtimehours = $monthhourperday / $workinghourperday *$Salary->over_time; 
								// Now you can use the $monthhourperday variable for further calculations or display.
								// Use number_format to format the $monthhourperday without decimals.
								echo '<h1 class="amount-class">' . number_format($overtimehours, 0) . "</h1>";
							} else {
								echo "Error: Invalid data provided.";
							}
							?>
						</div>
						<div class="entry">
							<div class="label">Leave Allow</div>
							<div class="detail"></div>
							<div class="rate"></div>
							<div class="amount">{{ $Salary->absent_amount}}</div>
						</div>
						<div class="entry">
							<div class="label">Night Half</div>
							<div class="detail"></div>
							<div class="nighthalf-rate">{{ $Salary->night_half}}</div>
							<?php
							// Assuming $Salary and $staff are already defined with valid values.
							// Calculate the total number of days in the given month.
							$workingdaypermonth = date('t', strtotime($Salary->month));
							$workinghourperday = 10;

							if (is_numeric($staff->salary) && $workingdaypermonth > 0) {
								$monthsalary = $staff->salary;
								$monthhourperday = $monthsalary / $workingdaypermonth;
								$half = $monthhourperday / 2;
								$nighthalf = $half * $Salary->night_half;
								// Now you can use the $monthhourperday variable for further calculations or display.
								// Use number_format to format the $monthhourperday without decimals.
								echo '<h1 class="amount-class">' . number_format($nighthalf, 0) . "</h1>";
							} else {
								echo "Error: Invalid data provided.";
							}
							?>
						</div>
						<div class="entry">
							<div class="label">Night Full</div>
							<div class="detail"></div>
							<div class="nightfull-rate">{{ $Salary->night_full}}</div>
							<?php
							// Assuming $Salary and $staff are already defined with valid values.
							// Calculate the total number of days in the given month.
							$workingdaypermonth = date('t', strtotime($Salary->month));
							$workinghourperday = 10;

							if (is_numeric($staff->salary) && $workingdaypermonth > 0) {
								$monthsalary = $staff->salary;
								$monthhourperday = $monthsalary / $workingdaypermonth;
								$full = $monthhourperday;
								$nightfull = $full * $Salary->night_full;
								// Now you can use the $monthhourperday variable for further calculations or display.
								// Use number_format to format the $monthhourperday without decimals.
								echo '<h1 class="amount-class">' . number_format($nightfull, 0) . "</h1>";
							} else {
								echo "Error: Invalid data provided.";
							}
							?>
						</div>
						@if ($Salary->dns_allounce > 0)
									<div class="entry">
										<div class="label">DNS Allowance</div>
										<div class="detail"></div>
										<div class="rate"></div>
										<div class="amount">{{ $Salary->dns_allounce }}</div>
									</div>
								@endif
								@if($Salary->medical_allounce > 0)	
									<div class="entry">
										<div class="label">Medical Allounce</div>
										<div class="detail"></div>
										<div class="rate"></div>
										<div class="amount">{{ $Salary->medical_allounce}}</div>
									</div>
								@endif
								@if($Salary->house_rent > 0)						
									<div class="entry">
										<div class="label">House Rent</div>
										<div class="detail"></div>
										<div class="rate"></div>
										<div class="amount">{{ $Salary->house_rent}}</div>
									</div>					
								@endif	
								@if($Salary->convence > 0)
									<div class="entry">
										<div class="label">Convence</div>
										<div class="detail"></div>
										<div class="rate"></div>
										<div class="amount">{{ $Salary->convence}}</div>
									</div>					
								@endif
								@if($Salary->ensurance > 0)
								<div class="entry unpaid">
									<div class="label">Ensurance</div>
									<div class="detail"></div>
									<div class="rate"></div>
									<div class="amount">{{ $Salary->ensurance }}</div>
								</div>
							@endif	
							@if($Salary->provident > 0)
								<div class="entry unpaid">
									<div class="label">Provident</div>
									<div class="detail"></div>
									<div class="rate"></div>
									<div class="amount">{{ $Salary->provident }}</div>
								</div>
							@endif	
							@if($Salary->professional > 0)
								<div class="entry unpaid">
									<div class="label">Professional</div>
									<div class="detail"></div>
									<div class="rate"></div>
									<div class="amount">{{ $Salary->professional}}</div>
								</div>
							@endif	
							@if($Salary->tax > 0)
								<div class="entry unpaid">
									<div class="label">Tax</div>
									<div class="detail"></div>
									<div class="rate"></div>
									<div class="amount">{{ $Salary->tax}}</div>
								</div>
							@endif
					</div>
					<?php $grossEarnings = $staff->salary+$overtimehours+$nighthalf+$nightfull+$Salary->dns_allounce+$Salary->medical_allounce+$Salary->house_rent+$Salary->convence+$Salary->ensurance+$Salary->provident+$Salary->professional+$Salary->tax+$staff->bike_maintenance?>
							<div class="entry custom-class">
								<div class="label">Gross Earnings</div>
								<div class="detail"></div>
								<div class="rate"></div>
								<div class="amount">{{ number_format($grossEarnings, 0) }}</div>
							</div>
						<div class="leaves">
							<div class="entry">
								<div class="label">Deduction</div>
								<div class="detail"></div>
								<div class="rate"></div>
								<div class="amount"></div>
							</div>
							<div class="entry paid">
								<div class="label">Advance</div>
								<div class="detail"></div>
								<div class="rate"></div>
								<div class="amount">{{ $Salary->advance }}</div>
							</div>
							<div class="entry unpaid">
							<div class="label">Absent</div>
							<div class="detail"></div>
							<div class="deduction-rate">{{ $Salary->absent_days }}</div>
							<?php
							// Assuming $Salary and $staff are already defined with valid values.
							// Calculate the total number of days in the given month.
							$workingdaypermonth = date('t', strtotime($Salary->month));
							$workinghourperday = 10;

							if (is_numeric($staff->salary) && $workingdaypermonth > 0) {
								$monthsalary = $staff->salary;
								$monthhourperday = $monthsalary / $workingdaypermonth;
								$oneday = $monthhourperday;
								$leaveallow = $monthhourperday * $Salary->absent_amount;

								// Calculate the actual absent days by multiplying with absent days
								$actualAbsentDays = $monthhourperday * $Salary->absent_days;

								// Calculate the absent amount based on different conditions
								if ($Salary->absent_days == $Salary->absent_amount) {
									$absentAmount = 0;
								} elseif ($Salary->absent_days > $Salary->absent_amount) {
								
									$absentAmount = $actualAbsentDays - $leaveallow;

								} elseif($Salary->absent_days < $Salary->absent_amount)
								{
									$absentAmount = 0;
								}
								else
								{
									$absentAmount = $actualAbsentDays;
								}

								// Now you can use the $absentAmount variable for further calculations or display.
								// Use number_format to format the $absentAmount without decimals.
								echo '<h1 class="amount-class">' . number_format($absentAmount, 0) . "</h1>";
							} else {
								echo "Error: Invalid data provided.";
							}
							?>
						</div>
							<div class="entry unpaid">
								<div class="label">Half Day</div>
								<div class="detail"></div>
								<div class="deductionhalfsalary-rate">{{ $Salary->half_day }}</div>
								<?php
									// Assuming $Salary and $staff are already defined with valid values.
									// Calculate the total number of days in the given month.
									$workingdaypermonth = date('t', strtotime($Salary->month));
									$workinghourperday = 10;

									if (is_numeric($staff->salary) && $workingdaypermonth > 0) {
										$monthsalary = $staff->salary;
										$monthhourperday = $monthsalary / $workingdaypermonth;
										$half = $monthhourperday / 2; 
										$halfday = $half * $Salary->half_day;
										// Now you can use the $monthhourperday variable for further calculations or display.
										// Use number_format to format the $monthhourperday without decimals.
										echo '<h1 class="amount-class">' . number_format($halfday, 0) . "</h1>";
									} else {
										echo "Error: Invalid data provided.";
									}
									?>
							</div>
						</div>
						<?php $deduction = $Salary->advance+$absentAmount+$halfday?>
							<div class="entry custom-class">
								<div class="label">Total Deduction</div>
								<div class="detail"></div>
								<div class="rate"></div>
								<div class="amount">{{ number_format($deduction, 0) }}</div>
							</div>
						<?php $netAmount = $grossEarnings-$deduction; ?>
							<div class="entry custom-class">
								<div class="label" style="width: 70%;">Net Salary Transferred</div>
								<div class="detail"></div>
								<div class="rate"></div>
								<div class="amount">{{ number_format($netAmount, 0) }}</div>
							</div>
					<br><br><br><br>
					<div class="container elevated">
    					<div class="withholding_tax">
    						<div class="entry custom-sign">
    							<div class="label custom-sign" style="width: 70%;">Prepared by ({{ $Salary->prepared_by }})</div>
    							<div class="amount custom-sign">_______________</div>
    						</div>
    					</div>
    					<div class="withholding_tax">
    						<div class="entry custom-sign">
    							<div class="label custom-sign">Managing Director</div>
    							<div class="amount custom-sign">_______________</div>
    						</div>
    					</div> 
    					<div class="withholding_tax">
    						<div class="entry custom-sign">
    							<div class="label custom-sign" style="width: 70%;">Signature of the Employee</div>
    							<div class="amount custom-sign">_______________</div>
    						</div>
    					</div>
    				</div>	
				</div>
			</div>
		</div>
	</div>
	<div class="header"><img src="{{ asset('assets/header.jpg') }}"/></div>
     <!--style="width: 210mm!important;" -->
    <div class="footer"><img src="{{ asset('assets/footer.jpg') }}" style="width: 210mm!important;" /></div>
</body>
</html>