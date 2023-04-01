<style>
    .v-select{
		margin-top:-2.5px;
        float: right;
        min-width: 180px;
        margin-left: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
        height: 25px;
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
	#searchForm select{
		padding:0;
		border-radius: 4px;
	}
	#searchForm .form-group{
		margin-right: 5px;
	}
	#searchForm *{
		font-size: 13px;
	}
	.record-table{
		width: 100%;
		border-collapse: collapse;
	}
	.record-table thead{
		background-color: #0097df;
		color:white;
	}
	.record-table th, .record-table td{
		padding: 3px;
		border: 1px solid #454545;
		text-align: center;
	}
    .record-table th{
        text-align: center;
    }
</style>
<div id="salesRecord">
	<div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
		<div class="col-md-12">
			<form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
				<div class="form-group">
					<label>Record Type</label>
					<select class="form-control" v-model="recordType" @change="services = []">
						<option value="without_details">Without Details</option>
						<option value="with_details">With Details</option>
						<option value="pending">By Pending</option>
						<option value="delivery">By Delivered</option>
						<option value="transfer">By Transfer</option>
						<option value="received">By Received</option>
						<option value="customer">By Customer</option>
						<option value="invoice">By Invoice</option>
					</select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: recordType == 'customer' && customers.length > 0 ? '' : 'none'}">
					<label>Customer</label>
					<v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name"></v-select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: recordType == 'invoice' && invoices.length > 0 ? '' : 'none'}">
					<label>Invoice</label>
					<v-select v-bind:options="invoices" v-model="selectedInvoice" label="invoice"></v-select>
				</div>

				<div class="form-group">
					<input type="date" class="form-control" v-model="dateFrom">
				</div>

				<div class="form-group">
					<input type="date" class="form-control" v-model="dateTo">
				</div>

				<div class="form-group" style="margin-top: -5px;">
					<input type="submit" value="Search">
				</div>
			</form>
		</div>
	</div>

	<div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: services.length > 0 ? '' : 'none'}">
		<div class="col-md-12" style="margin-bottom: 10px;">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
				<table 
					class="record-table" 
					v-if="recordType == 'without_details' || recordType == 'customer' || recordType == 'invoice'" 
					style="display:none" 
					v-bind:style="{display: recordType == 'without_details' || recordType == 'customer' || recordType == 'invoice' ? '' : 'none'}"
				>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Customer Name</th>
							<th>Quantity</th>
							<th>Total</th>
							<th>Paid</th>
							<th>Due</th>
							<th>Saved By</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
                        <tr v-for="service in services">
                            <td>{{ service.invoice }}</td>
                            <td>{{ service.date }}</td>
                            <td>{{ service.Customer_Name }}</td>
                            <td>{{ service.quantity }}</td>
                            <td>{{ service.total }}</td>
                            <td>{{ service.paid }}</td>
                            <td>{{ service.due }}</td>
                            <td>{{ service.added_by }}</td>
                            <td class="text-center">
								<a href="" title="Repair Invoice" v-bind:href="`/service_invoice/${service.id}`" target="_blank"><i class="fa fa-file"></i></a>
                                <?php if($this->session->userdata('accountType') != 'u'){?>
								<a href="" title="Edit Service" v-bind:href="`/service/${service.id}`"><i class="fa fa-edit"></i></a>
								<a href="" title="Delete Service" @click.prevent="deleteService(service.id)"><i class="fa fa-trash"></i></a>
								<?php }?>
                            </td>
                        </tr>
					</tbody>
				</table>
				<table 
					class="record-table" 
					v-if="recordType == 'with_details'" 
					style="display:none" 
					v-bind:style="{display: recordType == 'with_details' ? '' : 'none'}"
				>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Customer Name</th>
							<th>Product Name</th>
							<th>Model</th>
							<th>Imei</th>
							<th>Quantity</th>
							<th>Status</th>
							<th>Saved By</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<template v-for="service in services">
							<tr>
								<td>{{ service.invoice }}</td>
								<td>{{ service.date }}</td>
								<td>{{ service.Customer_Name }}</td>
								<td>{{ service.serviceDetails[0].product_name }}</td>
								<td>{{ service.serviceDetails[0].model }}</td>
								<td>{{ service.serviceDetails[0].imei }}</td>
								<td>{{ service.serviceDetails[0].quantity }}</td>
								<td>
									<div v-if=" service.serviceDetails[0].service_status == 'p'">Pending</div>
                                    <div v-else-if=" service.serviceDetails[0].service_status == 'd'">Delivered</div>
                                    <div v-else-if=" service.serviceDetails[0].service_status == 't'">Transfer</div>
                                    <div v-else>Received</div>
								</td>
								<td>{{ service.added_by }}</td>
								<td class="text-center">
								<a href="" title="Repair Invoice" v-bind:href="`/service_invoice/${service.id}`" target="_blank"><i class="fa fa-file"></i></a>
									<?php if($this->session->userdata('accountType') != 'u'){?>
									<a href="" title="Edit Service" v-bind:href="`/service/${service.id}`"><i class="fa fa-edit"></i></a>
									<a href="" title="Delete Service" @click.prevent="deleteService(service.id)"><i class="fa fa-trash"></i></a>
									<?php }?>
								</td>
							</tr>
							<tr v-for="product in service.serviceDetails.slice(1)">
								<td colspan="3"></td>
								<td>{{ product.product_name }}</td>
								<td>{{ product.model }}</td>
								<td>{{ product.imei }}</td>
								<td>{{ product.quantity }}</td>
								<td>
                                    <div v-if="product.service_status == 'p'">Pending</div>
                                    <div v-else-if="product.service_status == 'd'">Delivered</div>
                                    <div v-else-if="product.service_status == 't'">Transfer</div>
                                    <div v-else>Received</div>
                                </td>
								<td></td>
								<td></td>
							</tr>
						</template>
					</tbody>
				</table>

				<table 
					class="record-table" 
					style="display:none" 
					v-bind:style="{display: recordType != 'with_details'  && recordType != 'without_details' && recordType != 'customer' && recordType != 'invoice'? '' : 'none'}"
				>
					<thead>
						<tr>
							<th>Sl</th>
							<th>Product Name</th>
							<th>Model</th>
							<th>Imei</th>
							<th>Quantity</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(product, ind) in services">
							<td>{{ ind + 1 }}</td>
							<td>{{ product.product_name }}</td>
							<td>{{ product.model }}</td>
							<td>{{ product.imei }}</td>
							<td>{{ product.quantity }}</td>
							<td>
								<div v-if="product.service_status == 'p'">Pending</div>
								<div v-else-if="product.service_status == 'd'">Delivered</div>
								<div v-else-if="product.service_status == 't'">Transfer</div>
								<div v-else>Received</div>
							</td>
						</tr>
					</tbody>
				</table>


			</div>
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
		el: '#salesRecord',
		data(){
			return {
				recordType: 'without_details',
				dateFrom: moment().format('YYYY-MM-DD'),
				dateTo: moment().format('YYYY-MM-DD'),
                services: [],
				customers: [],
				selectedCustomer: null,
				invoices: [],
				selectedInvoice: null,
			}
		},
		created() {
			this.getCustomers();
			this.getInvoices()
		},
		methods: {
			getCustomers(){
				axios.get('/get_customers').then(res => {
					this.customers = res.data;
				})
			},
			getInvoices() {
				axios.get('/get_service_invoice')
				.then(res => {
					this.invoices = res.data
				})
			},
			getSearchResult(){
				let status = null;
				if(this.recordType == 'pending') {
					status = 'p';
				} else if(this.recordType == 'delivery') {
					status = 'd';
				} else if(this.recordType == 'transfer') {
					status = 't';
				} else if(this.recordType == 'received') {
					status = 'r';
				}

				if(this.recordType == 'invoice') {
					this.dateFrom = '';
					this.dateTo = '';
					this.selectedCustomer = null;
				}

				let filter = {
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					status: status,
					customerId: this.selectedCustomer == null ? null : this.selectedCustomer.Customer_SlNo,
					id: this.selectedInvoice == null ? null : this.selectedInvoice.id
				}

				let url = '/get_service';
				if(this.recordType == 'with_details'){
					url = '/get_service_record';
				} else if(this.recordType != 'with_details' && this.recordType != 'without_details' && this.recordType != 'customer' && this.recordType != 'invoice') {
					url = '/get_service_stock';
				}

				axios.post(url, filter)
				.then(res => {
					if(this.recordType != 'without_details' &&  this.recordType != 'customer' && this.recordType != 'invoice'){
						this.services = res.data;
					} else {
						this.services = res.data.services;
					}
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},
            deleteService(id) {
                if(confirm('Are you sure?')) {
                    axios.post('/delete_service', { id: id })
                    .then(res => {
                        alert(res.data.message)
                        if(res.data.success) {
                            this.getSearchResult();
                        }
                    })
                }
            },
			async print(){
				var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
				reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				reportWindow.document.head.innerHTML += `
					<style>
						.record-table{
							width: 100%;
							border-collapse: collapse;
						}
						.record-table thead{
							background-color: #0097df;
							color:white;
						}
						.record-table th, .record-table td{
							padding: 3px;
							border: 1px solid #454545;
						}
						.record-table th{
							text-align: center;
						}
					</style>
				`;
				reportWindow.document.body.innerHTML += reportContent;

				if(this.searchType == '' || this.searchType == 'user'){
					let rows = reportWindow.document.querySelectorAll('.record-table tr');
					rows.forEach(row => {
						row.lastChild.remove();
					})
				}


				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}
	})
</script>