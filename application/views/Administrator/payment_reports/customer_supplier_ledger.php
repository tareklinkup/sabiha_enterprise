<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
    .table>tbody>tr>td {
        padding: 3px 1px !important;
    }
</style>
<div class="row" id="customerPaymentReport">
	<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right"> Customer </label>
			<div class="col-sm-2">
				<v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name"></v-select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right"> Date from </label>
			<div class="col-sm-2">
				<input type="date" class="form-control" v-model="dateFrom">
			</div>
			<label class="col-sm-1 control-label no-padding-right text-center" style="width:30px"> to </label>
			<div class="col-sm-2">
				<input type="date" class="form-control" v-model="dateTo">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-1">
				<input type="button" class="btn btn-primary" value="Show" v-on:click="getReport" style="margin-top:0px;border:0px;height:28px;">
			</div>
		</div>
	</div>

	<div class="col-sm-12" style="display:none;" v-bind:style="{display: showTable ? '' : 'none'}">
		<a href="" style="margin: 7px 0;display:block;width:50px;" v-on:click.prevent="print">
			<i class="fa fa-print"></i> Print
		</a>
		<div class="table-responsive" id="reportTable">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th style="text-align:center">Date</th>
						<th style="text-align:center">Description</th>
						<th style="text-align:center">Pur. Amount</th>
						<th style="text-align:center">Paid</th>
						<th style="text-align:center">Inv. Due</th>
						<th style="text-align:center">Pur. Return</th>
						<th style="text-align:center">Supplier Payment</th>
						<th style="text-align:center">Sale Amount</th>
						<th style="text-align:center">Paid</th>
						<th style="text-align:center">Inv. Due</th>
						<th style="text-align:center">Sale Return</th>
						<th style="text-align:center">Customer Received</th>
						<th style="text-align:center">Balance</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td></td>
						<td style="text-align:left;">Previous Balance</td>
						<td colspan="10"></td>
						<td style="text-align:right;">{{ parseFloat(previousBalance).toFixed(2) }}</td>
					</tr>
					<tr v-for="payment in payments">
						<td>{{ payment.date }}</td>
						<td style="text-align:left;width:20%">{{ payment.description }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.pur_amount).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.pur_paid).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.pur_due).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.pur_return).toFixed(2) }}</td>
						<td>
							<div v-if="payment.supplier_received != 0" style="text-align:left">({{ parseFloat(payment.supplier_received).toFixed(2) }})</div>
							
							<div v-else-if="payment.supplier_payment != 0" style="text-align:right">{{ parseFloat(payment.supplier_payment).toFixed(2) }}</div>
							<div v-else style="text-align:right">0.00</div>
							
						</td>
						<td style="text-align:right;">{{ parseFloat(payment.sale_amount).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.sale_paid).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.sale_due).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.sale_return).toFixed(2) }}</td>
						<td>
							<div v-if="payment.customer_paid != 0" style="text-align:left">({{ parseFloat(payment.customer_paid).toFixed(2) }})</div>
							<div v-else-if="payment.customer_received != 0" style="text-align:right">{{ parseFloat(payment.customer_received).toFixed(2) }}</div>
							<div v-else style="text-align:right">0.00</div>
						</td>
						<td style="text-align:right;">{{ parseFloat(payment.balance).toFixed(2) }}</td>
					</tr>
				</tbody>
                <!-- <tfoot style="font-weight: bold;">
                <tr>
                    <td colspan="2" style="text-align: right;">Total</td>
                    <td style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.pur_amount) }, 0).toFixed(2) }}</td>
                    <td style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.pur_paid) }, 0).toFixed(2) }}</td>
                    <td style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.pur_due) }, 0).toFixed(2) }}</td>
                    <td style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.pur_return) }, 0).toFixed(2) }}</td>
					<td>
						<div style="text-align: left;">({{ payments.reduce((p, c) => { return p + parseFloat(c.supplier_received) }, 0).toFixed(2) }})</div>
						<div style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.supplier_payment) }, 0).toFixed(2) }}</div>
					</td>
					<td style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.sale_amount) }, 0).toFixed(2) }}</td>
					<td style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.sale_paid) }, 0).toFixed(2) }}</td>
					<td style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.sale_due) }, 0).toFixed(2) }}</td>
					<td style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.sale_return) }, 0).toFixed(2) }}</td>
                    <td>
						<div style="text-align: left;">({{ payments.reduce((p, c) => { return p + parseFloat(c.customer_paid) }, 0).toFixed(2) }})</div>
						<div style="text-align: right;">{{ payments.reduce((p, c) => { return p + parseFloat(c.customer_received) }, 0).toFixed(2) }}</div>
					</td>
					<td></td>
                </tr>
                </tfoot> -->
				<tbody v-if="payments.length == 0">
					<tr>
						<td colspan="13">No records found</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#customerPaymentReport',
		data(){
			return {
				customers: [],
				selectedCustomer: null,
				dateFrom: null,
				dateTo: null,
				payments: [],
				previousBalance: 0.00,
				showTable: false
			}
		},
		created(){
			let today = moment().format('YYYY-MM-DD');
			this.dateTo = today;
			this.dateFrom = moment().format('YYYY-MM-DD');
			this.getCustomers();
		},
		methods:{
			getCustomers(){
				axios.get('/get_customers').then(res => {
					this.customers = res.data.filter(item => item.is_supplier == true);
				})
			},
			getReport(){
				if(this.selectedCustomer == null){
					alert('Select customer');
					return;
				}
				let data = {
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					customerId: this.selectedCustomer.Customer_SlNo
				}

				axios.post('/get_combine_ledger', data).then(res => {
					this.payments = res.data.payments;
					this.previousBalance = res.data.previousBalance;
					this.showTable = true;
				})
			},
			async print(){
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Customer payment report</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-6" style="font-size:12px;">
								<strong>Customer Code: </strong> ${this.selectedCustomer.Customer_Code}<br>
								<strong>Name: </strong> ${this.selectedCustomer.Customer_Name}<br>
								<strong>Address: </strong> ${this.selectedCustomer.Customer_Address}<br>
								<strong>Mobile: </strong> ${this.selectedCustomer.Customer_Mobile}<br>
							</div>
							<div class="col-xs-6 text-right">
								<strong>Statement from</strong> ${this.dateFrom} <strong>to</strong> ${this.dateTo}
							</div>
						</div>
					</div>
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportTable').innerHTML}
							</div>
						</div>
					</div>
				`;

				var mywindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
				mywindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				mywindow.document.body.innerHTML += reportContent;

				mywindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				mywindow.print();
				mywindow.close();
			}
		}
	})
</script>