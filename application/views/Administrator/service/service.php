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
</style>
<div id="services" class="row">
	<div class="col-xs-12 col-md-7 col-lg-7">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Repair Information</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>

					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="col-sm-4 control-label no-padding-right"> Invoice No </label>
								<div class="col-sm-8">
									<input type="text"  class="form-control" v-model="service.invoice" required @change="getServices" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label no-padding-right"> Repair Date </label>
								<div class="col-sm-8">
									<input type="date"  class="form-control" v-model="service.date" required />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label no-padding-right">Customer </label>
								<div class="col-sm-8">
									<v-select v-bind:options="customers" label="display_name" v-model="selectedCustomer"></v-select>
								</div>
							</div>

							<div class="form-group" style="display:none;" v-bind:style="{display: selectedCustomer.Customer_Type == 'G' ? '' : 'none'}">
								<label class="col-xs-4 control-label no-padding-right"> Name </label>
								<div class="col-xs-8">
									<input type="text" id="customerName" placeholder="Customer Name" class="form-control" v-model="selectedCustomer.Customer_Name" v-bind:disabled="selectedCustomer.Customer_Type == 'G' ? false : true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Mobile No </label>
								<div class="col-xs-8">
									<input type="text" id="mobileNo" placeholder="Mobile No" class="form-control" v-model="selectedCustomer.Customer_Mobile" v-bind:disabled="selectedCustomer.Customer_Type == 'G' ? false : true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Address </label>
								<div class="col-xs-8">
									<textarea id="address" placeholder="Address" class="form-control" v-model="selectedCustomer.Customer_Address" v-bind:disabled="selectedCustomer.Customer_Type == 'G' ? false : true"></textarea>
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<form @submit.prevent="addToCart">
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right">Pro. Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="product.product_name" required>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right"> Model </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="product.model" required>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right"> Serial No </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="product.imei" @change="checkImeiNo" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right">Rep. Status </label>
									<div class="col-sm-8">
										<select class="form-control" style="height: 30px;border-radius:3px" v-model="product.status">
											<option value="p">Pending</option>
											<option value="d">Delivered</option>
											<option value="t">Transfer</option>
											<option value="r">Received</option>
										</select>
									</div>
								</div>
								<div class="form-group" style="display:none ;" :style="{display: product.status == 't' || product.status == 'r' ? '' : 'none'}">
									<label class="col-sm-4 control-label no-padding-right">Company </label>
									<div class="col-sm-8">
									<v-select v-bind:options="companies" label="name" v-model="company"></v-select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label no-padding-right"> </label>
									<div class="col-sm-8">
										<button type="submit" class="btn btn-default btn-sm pull-right">{{ cartProductEditIndex == null ? 'Add': 'Update'}}</button>
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px;padding-right: 0px;">
			<div class="table-responsive">
				<table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
					<thead>
						<tr class="">
							<th style="width:5%;color:#000;">Sl</th>
							<th style="width:20%;color:#000;">Product Name</th>
							<th style="width:10%;color:#000;">Model</th>
							<th style="width:10%;color:#000;">IMEI</th>
							<th style="width:15%;color:#000;">Quantity</th>
							<th style="width:15%;color:#000;">Status</th>
							<th style="width:5%;color:#000;">Action</th>
						</tr>
					</thead>
					<tbody style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
						<tr v-for="(product, sl) in cart">
							<td>{{ sl + 1 }}</td>
							<td>{{ product.product_name }}</td>
							<td>{{ product.model }}</td>
							<td>{{ product.imei }}</td>
							<td>{{ product.quantity }}</td>
							<td>
								<div v-if="product.status == 'p'">Pending</div>
								<div v-else-if="product.status == 'd'">Delivered</div>
								<div v-else-if="product.status == 't'">Transfer</div>
								<div v-else>Received</div>
							</td>
							<td>
								<!-- <a href="" v-on:click.prevent="removeFromCart(sl)"><i class="fa fa-trash"></i></a> -->
								<a href="" v-on:click.prevent="editCartItem(sl)" v-if="service.serviceId != 0"><i class="fa fa-edit"></i></a>
								<a href="" v-on:click.prevent="removeFromCart(sl)" v-if="cartProductEditIndex != sl">
									<i class="fa fa-trash"></i>
								</a>
							</td>
						</tr>

						<tr>
							<td colspan="7"></td>
						</tr>

						<tr style="font-weight: bold;">
							<td colspan="3">Note</td>
							<td colspan="2">Paid Amount</td>
							<td colspan="2">Total Quantity</td>
						</tr>

						<tr>
							<td colspan="3"><textarea style="width: 100%;font-size:13px;" placeholder="Note" v-model="service.note"></textarea></td>
							<td colspan="2"><input type="text" v-model="service.paid" class="form-control"></td>
							<td colspan="2" style="padding-top: 15px;font-size:18px;">{{ cart.reduce((p, c) => {return +p + +c.quantity}, 0 ) }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Costing Details</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>

					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row">
						<div class="col-sm-12">
							<form @submit.prevent="addToCosting">
								<div class="form-group">
									<label for="" class="control-lable col-sm-4">Select Expense</label>
									<div class="col-sm-7">
										<v-select v-bind:options="expenses" label="name" v-model="cost"></v-select>
									</div>
									<div class="col-sm-1">
										<a href="<?= base_url('expense')?>" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -17px;" target="_blank" title="Add New Expense"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="control-lable col-sm-4">Expense Rate</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="cost.rate" @input="calculateTotal" required>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="control-lable col-sm-4">Unit</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="cost.unit" @input="calculateTotal" required>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="control-lable col-sm-4">Amount</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="cost.amount" required readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="control-lable col-sm-4"></label>
									<div class="col-sm-8">
										<button type="submit" class="btn btn-default btn-sm pull-right">Add</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px;padding-right: 0px;">
			<div class="table-responsive">
				<table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
					<thead>
						<tr class="">
							<th style="width:10%;color:#000;">Sl</th>
							<th style="width:20%;color:#000;">Expense</th>
							<th style="width:7%;color:#000;">Rate</th>
							<th style="width:8%;color:#000;">Unit</th>
							<th style="width:15%;color:#000;">Amount</th>
							<th style="width:15%;color:#000;">Action</th>
						</tr>
					</thead>
					<tbody style="display:none;" v-bind:style="{display: costCart.length > 0 ? '' : 'none'}">
						<tr v-for="(product, sl) in costCart">
							<td>{{ sl + 1 }}</td>
							<td>{{ product.expense }}</td>
							<td>{{ product.price }}</td>
							<td>{{ product.unit }}</td>
							<td>{{ product.amount }}</td>
							<td><a href="" v-on:click.prevent="removeFromCostCart(sl)"><i class="fa fa-trash"></i></a></td>
						</tr>

					
						<tr>
							<td colspan="4" style="text-align: right;"><strong>Total</strong></td>
							<td colspan="2" style="font-size:18px;">{{ costCart.reduce((p, c) => {return +p + +c.amount}, 0 ) }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<button class="btn btn-sm pull-right" @click.prevent="saveService"  v-bind:disabled="serviceOnProgress ? true : false" >Save</button>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#services',
		data(){
			return {
				service:{
					serviceId: parseInt('<?php echo $serviceId;?>'),
					invoice: '<?php echo $invoice?>',
					date: moment().format('YYYY-MM-DD'),
					customerId: '',
					total: 0,
					paid: 0,
					due: 0,
					note: ''
				},
				customers: [],
				selectedCustomer:{
					Customer_SlNo: '',
					Customer_Code: '',
					Customer_Name: '',
					display_name: 'Select Customer',
					Customer_Mobile: '',
					Customer_Address: '',
					Customer_Type: ''
				},
				product: {
					product_name: '',
					model: '',
					imei: '',
					quantity: 1,
					status: 'p',
					company_id: null
				},
				expenses: [],
				cost: {
					id: '',
					name: '',
					rate: '',
					unit: 0,
					amount: 0
				},
				cart: [],
				costCart: [],
				serviceOnProgress: false,
				companies: [],
				company: null,
				cartProductEditIndex: null,
			}
		},
		watch: {
			company(company) {
				if(company == undefined) return;
				this.product.company_id = company.id
			}
		},
		async created(){
			this.getCompanies();
			if(this.service.serviceId != 0) {
				this.getServices();
			}
			
			this.getCustomers();
			this.getExpenses();
		},
		methods:{
			getCustomers() {
				axios.get('/get_customers')
				.then(res => {
					this.customers = res.data;
					this.customers.unshift({
						Customer_SlNo: 'C01',
						Customer_Code: '',
						Customer_Name: '',
						display_name: 'General Customer',
						Customer_Mobile: '',
						Customer_Address: '',
						Customer_Type: 'G'
					})
				})
			},
			getCompanies() {
				axios.post('/get_company')
				.then(res => {
					this.companies = res.data
				})
			},
			getExpenses() {
				axios.get('/get_expense')
				.then(res => {
					this.expenses = res.data.map((item, unit) => {
						item.unit = 1;
						item.amount = item.rate * item.unit
						return item;
					});
				})
			},
			addToCart() {
				if(this.product.product_name == '' || this.product.model == '' || this.product.imei == '' || this.product.quantity == 0) {
					alert('Input field is required!');
					return;
				}

				if(this.product.status == '') {
					alert("Please select status");
					return;
				}

				if(this.product.status == 't' || this.product.status == 'r') {
					if(this.product.company_id == null) {
						alert('Select company name');
						return;
					}
				}

				let product = {
					product_name: this.product.product_name,
					model: this.product.model,
					imei: this.product.imei,
					quantity: this.product.quantity,
					status: this.product.status,
					company_id: this.product.company_id
				}

				if (this.cartProductEditIndex != null) {
					let editProduct = this.cart[this.cartProductEditIndex];
					let checkIfExist = this.cart.findIndex(p => (p.imei == product.imei) && (p.imei != editProduct.imei));

					if (checkIfExist > -1) {
						alert('Product imei already exist');
						return;
					}

					this.cart[this.cartProductEditIndex] = product;

				}
				else {
					let checkProduct = this.cart.findIndex(item => item.product_name == this.product.product_name && item.imei == this.product.imei);
					if(checkProduct > -1) {
						alert('Product name already exists');
						return;
					}
					this.cart.unshift(product);
				}
				this.clearProduct();
			},
			checkImeiNo() {
				axios.post('/check_imei', { imei: this.product.imei})
				.then(res => {
					if(res.data.success) {
						alert(res.data.message);
					}
				})
				.catch(err => {
					alert(err.response.data.message)
				})
			},
			addToCosting() {
				if(this.cost.expense == '' || this.cost.rate == '' || this.cost.unit == '') {
					alert('Input field is required');
					return;
				}

				// if(this.cost.rate == 0) {
				// 	alert('Expense rate is required');
				// 	return;
				// }

				let checkCost = this.costCart.findIndex(item => item.expense == this.cost.expense);
				if(checkCost > -1) {
					alert('Expense name already exists');
					return;
				}

				let cost = {
					expense_id: this.cost.id,
					expense: this.cost.name,
					price: this.cost.rate,
					unit: this.cost.unit,
					amount: this.cost.amount
				}

				this.costCart.unshift(cost);
				this.clearCost();
				// this.refs.expense.focus();
			},
			calculateTotal() {
				this.cost.amount = parseFloat(this.cost.rate) * parseFloat(this.cost.unit);
			},
			async saveService() {
				if(this.selectedCustomer.Customer_SlNo == '') {
					alert('Select customer');
					return;
				}
				if(this.cart.length == 0) {
					alert('Product cart is empty');
					return;
				}
				if(this.costCart.length == 0) {
					alert('Costing cart is empty');
					return;
				}

				let url = "";
				if(this.service.serviceId != 0) {
					url = '/update_service';
				} else {
					url = "/add_service";
					delete this.service.serviceId;
				}
				
				this.serviceOnProgress = true;
				this.service.total = this.costCart.reduce((p, c) => {return +p + +c.amount },0);
				this.service.due = parseFloat(this.service.total) - parseFloat(this.service.paid);
				this.service.customerId = this.selectedCustomer.Customer_SlNo;
				let data = {
					service: this.service,
					product: this.cart,
					costing: this.costCart
				}

				if(this.selectedCustomer.Customer_Type == 'G'){
					data.customer = this.selectedCustomer;
				}

				await axios.post(url, data)
				.then(async res => {
					
					let r = res.data;
					if(r.success){
						let conf = confirm('Repair success, Do you want to view invoice?');
						if(conf){
							window.open('/service_invoice/'+r.serviceId, '_blank');
							await new Promise(r => setTimeout(r, 1000));
							window.location = '/service';
						} else {
							window.location = '/service';
						}
					} else {
						alert(r.message);
						this.serviceOnProgress = false;
					}
				})
			},
			editCartItem(index) {
				let cartProduct = this.cart[index];
				Object.keys(cartProduct).forEach(key => {
					this.product[key] = cartProduct[key]
				})
				this.company = this.companies.find(item => item.id == cartProduct.company_id)
				this.cartProductEditIndex = index;
			},
			getServices() {
				axios.post('/get_service', {invoice: this.service.invoice})
				.then(res => {
					let r = res.data;
					let service = r.services[0];
					// console.log(service)
					this.service.serviceId = service.id;
					this.service.date = service.date;
					this.service.invoice = service.invoice;
					this.service.note = service.note;
					this.service.quantity = service.quantity;
					this.service.total = service.total;
					this.service.paid = service.paid;
					this.service.due = service.due;

					this.selectedCustomer = {
						Customer_SlNo: service.customer_id,
						Customer_Code: service.Customer_Code,
						Customer_Name: service.Customer_Name,
						display_name: service.Customer_Type == 'G' ? 'General Customer' : `${service.Customer_Code} - ${service.Customer_Name}`,
						Customer_Mobile: service.Customer_Mobile,
						Customer_Address: service.Customer_Address,
						Customer_Type: service.Customer_Type
					}

					r.serviceDetails.forEach(product => {
						let cartProduct = {
							product_name: product.product_name,
							model: product.model,
							imei: product.imei,
							quantity: product.quantity,
							status: product.service_status,
							company_id: product.company_id
						}

						this.cart.push(cartProduct);
					});

					r.expenseDetails.forEach(cost => {
						let expenseCart = {
							expense_id: cost.expense_id,
							expense: cost.expense,
							price: cost.price,
							unit: cost.quantity,
							amount: cost.amount,
						}
						this.costCart.push(expenseCart)
					})
				})
			},
			removeFromCart(sl) {
				this.cart.splice(sl, 1)
			},
			removeFromCostCart(sl) {
				this.costCart.splice(sl, 1)
			},
			clearProduct() {
				this.product.product_name = "";
				this.product.model = "";
				this.product.imei = "";
				this.product.status = 'p';
				this.company = null;
			},
			clearCost() {
				this.cost.expense = "";
				this.cost.rate = "";
				this.cost.unit = 1;
				this.cost.amount = 0;
			}
		}
	})
</script>