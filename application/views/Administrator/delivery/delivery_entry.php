<style>
	.v-select {
		margin-bottom: 5px;
	}

	.v-select.open .dropdown-toggle {
		border-bottom: 1px solid #ccc;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
		height: 25px;
	}

	.v-select input[type=search],
	.v-select input[type=search]:focus {
		margin: 0px;
	}

	.v-select .vs__selected-options {
		overflow: hidden;
		flex-wrap: nowrap;
	}

	.v-select .selected-tag {
		margin: 2px 0px;
		white-space: nowrap;
		position: absolute;
		left: 0px;
	}

	.v-select .vs__actions {
		margin-top: -5px;
	}

	.v-select .dropdown-menu {
		width: auto;
		overflow-y: auto;
	}

	#products label {
		font-size: 13px;
	}

	#products select {
		border-radius: 3px;
	}

	#products .add-button {
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
	}

	#products .add-button:hover {
		background-color: #41add6;
		color: white;
	}

	#products input[type="file"] {
		display: none;
	}

	#products .custom-file-upload {
		border: 1px solid #ccc;
		display: inline-block;
		padding: 5px 12px;
		cursor: pointer;
		margin-top: 5px;
		background-color: #298db4;
		border: none;
		color: white;
	}

	#products .custom-file-upload:hover {
		background-color: #41add6;
	}

	#customerImage {
		height: 100%;
	}
</style>
<div id="delivery">
	<form @submit.prevent="saveDelivery">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom: 15px;">
			<div class="col-md-5 col-md-offset-1">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Date :</label>
					<div class="col-md-7">
						<input type="date" class="form-control" v-model="delivery.date" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Customer Name :</label>
					<div class="col-md-7">
						<input type="text" class="form-control" placeholder="Customer name" v-model="delivery.customer_name" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Delivery Address :</label>
					<div class="col-md-7">
						<textarea class="form-control" placeholder="Delivery address" v-model="delivery.delivery_address" cols="30" rows="3" required></textarea>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Courier Name :</label>
					<div class="col-md-7">
						<input type="text" class="form-control" placeholder="Courier name" v-model="delivery.courier_name" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Memo No :</label>
					<div class="col-md-7">
						<input type="text" class="form-control" placeholder="Memo no" v-model="delivery.memo_no" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Quantity :</label>
					<div class="col-md-7">
						<input type="number" class="form-control" placeholder="Quantity" v-model="delivery.quantity" required>
					</div>
				</div>

			</div>

			<div class="col-md-5">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Delivery Type :</label>
					<div class="col-md-7">
						<input type="text" class="form-control" placeholder="Delivery Type" v-model="delivery.delivery_type" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Packing By :</label>
					<div class="col-md-7">
						<input type="text" class="form-control" placeholder="Packing by" v-model="delivery.packing_by" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Delivery Boy :</label>
					<div class="col-md-7">
						<input type="text" class="form-control" placeholder="Delivery boy" v-model="delivery.delivery_boy" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Status :</label>
					<div class="col-md-7">
						<select class="form-control" v-model="delivery.delivery_status" required style="padding: 1px;">
							<option value="">Select</option>
							<option value="Pending">Pending</option>
							<option value="Complete">Complete</option>
						</select>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Delivery Note :</label>
					<div class="col-md-7">
						<textarea class="form-control" placeholder="Delivery note" v-model="delivery.delivery_note" cols="30" rows="3"></textarea>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4"></label>
					<div class="col-md-7 text-right">
						<input type="submit" name="submit" class="btn btn-info" value="Save Delivery">
					</div>
				</div>



			</div>

		</div>
	</form>

	<div class="row">
		<div class="col-sm-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<datatable :columns="columns" :data="allDeliveries" :filter-by="filter">
					<template scope="{ row }">
						<tr>
							<td>{{ row.delivery_id }}</td>
							<td>{{ row.date }}</td>
							<td>{{ row.customer_name }}</td>
							<td>{{ row.delivery_address }}</td>
							<td>{{ row.courier_name }}</td>
							<td>{{ row.memo_no }}</td>
							<td>{{ row.quantity }}</td>
							<td>{{ row.delivery_type }}</td>
							<td>{{ row.packing_by }}</td>
							<td>{{ row.delivery_boy }}</td>
							<td>{{ row.delivery_status }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editDelivery(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteDelivery(row.delivery_id)">
										<i class="fa fa-trash"></i>
									</button>
								<?php } ?>
							</td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
			</div>
		</div>
	</div>


</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#delivery',
		data() {
			return {
				delivery: {
					delivery_id: '',
					date: moment().format('YYYY-MM-DD'),
					customer_name: '',
					delivery_address: '',
					courier_name: '',
					memo_no: '',
					quantity: 0,
					delivery_type: '',
					packing_by: '',
					delivery_boy: '',
					delivery_status: '',
					delivery_note: '',
				},
				allDeliveries: [],

				columns: [{
						label: 'SL No',
						field: 'delivery_id',
						align: 'center',
						filterable: false
					},
					{
						label: 'Date',
						field: 'date',
						align: 'center'
					},
					{
						label: 'Customer Name',
						field: 'customer_name',
						align: 'center'
					},
					{
						label: 'Delivery Address',
						field: 'delivery_address',
						align: 'center'
					},
					{
						label: 'Courier Name',
						field: 'courier_name',
						align: 'center'
					},
					{
						label: 'Memo No',
						field: 'memo_no',
						align: 'center'
					},
					{
						label: 'Quantity',
						field: 'quantity',
						align: 'center'
					},
					{
						label: 'Delivery Type',
						field: 'delivery_type',
						align: 'center'
					},
					{
						label: 'Packing By',
						field: 'packing_by',
						align: 'center'
					},
					{
						label: 'Delivery Boy',
						field: 'delivery_boy',
						align: 'center'
					},
					{
						label: 'Delivery Status',
						field: 'delivery_status',
						align: 'center'
					},
					{
						label: 'Action',
						align: 'center',
						filterable: false
					}
				],
				page: 1,
				per_page: 10,
				filter: ''
			}
		},

		created() {
			// this.getCategories();
			// this.getSubcategories();
			// this.getBrands();
			// this.getUnits();
			this.getDeliveries();
		},
		methods: {
			getDeliveries() {
				axios.get('/get_deliveries').then(res => {
					this.allDeliveries = res.data;
				})
			},
			saveDelivery() {

				let url = '/save_delivery';
				if (this.delivery.delivery_id != 0) {
					url = '/update_delivery';
				}
				axios.post(url, this.delivery)
					.then(res => {
						let r = res.data;
						alert(r.message);
						if (r.success) {
							this.clearForm();
							this.getDeliveries();
						}
					})

			},
			editDelivery(delivery) {
				let keys = Object.keys(this.delivery);
				keys.forEach(key => {
					this.delivery[key] = delivery[key];
				})
			},
			deleteDelivery(deliveryId) {
				let deleteConfirm = confirm('Are you sure?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/delete_delivery', {
					deliveryId: deliveryId
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.getDeliveries();
					}
				})
			},
			clearForm() {
				let keys = Object.keys(this.delivery);
				keys.forEach(key => {
					if (typeof(this.delivery[key]) == "string") {
						this.delivery[key] = '';
					} else if (typeof(this.delivery[key]) == "number") {
						this.delivery[key] = 0;
					}
				})
			}
		}
	})
</script>