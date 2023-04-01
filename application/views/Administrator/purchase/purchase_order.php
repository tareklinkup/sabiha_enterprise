<style>
	.modal-mask {
		position: fixed;
		z-index: 9998;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, .5);
		display: table;
		transition: opacity .3s ease;
	}

	.modal-wrapper {
		display: table-cell;
		vertical-align: middle;
	}

	.modal-container {
		width: 400px;
		margin: 0px auto;
		background-color: #fff;
		border-radius: 2px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
		transition: all .3s ease;
		font-family: Helvetica, Arial, sans-serif;
	}

	.modal-header {
		padding-bottom: 0 !important;
	}

	.modal-header h3 {
		margin-top: 0;
		color: #42b983;
	}

	.modal-body {
		overflow-y: auto !important;
		height: 300px !important;
		margin: -8px -14px -44px !important;
	}

	.modal-default-button {
		float: right;
	}

	.modal-enter {
		opacity: 0;
	}

	.modal-leave-active {
		opacity: 0;
	}

	.modal-enter .modal-container,
	.modal-leave-active .modal-container {
		-webkit-transform: scale(1.1);
		transform: scale(1.1);
	}

	.modal-footer {
		padding-top: 14px !important;
		margin-top: 30px !important;
	}

	.v-select {
		margin-bottom: 5px;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
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

	#branchDropdown .vs__actions button {
		display: none;
	}

	#branchDropdown .vs__actions .open-indicator {
		height: 15px;
		margin-top: 7px;
	}
</style>

<div class="row" id="purchase">
	<!-- purchase product serial modal -->
	<div style="display:none" id="serial-modal" v-if="" v-bind:style="{display:serialModalStatus?'block':'none'}">
		<transition name="modal">
			<div class="modal-mask">
				<div class="modal-wrapper">
					<div class="modal-container">
						<div class="modal-header">
							<slot name="header">
								<h3>Serial Number Add</h3>
							</slot>
						</div>

						<div class="modal-body">
							<slot name="body">
								<form @submit.prevent="serial_add_action">
									<div class="form-group">
										<div class="col-sm-12" style="display: flex; margin-bottom: 5px;">
											<input type="text" autocomplete="off" ref="serialnumberadd" id="serial_number" name="serial_number" v-model="get_serial_number" class="form-control" placeholder="Please Enter Serial Number" style="height: 30px;" />

											<input type="submit" :disabled="is_disabled == true ? true:false" class="btn btn-sm primary" style="border: none; font-size: 13px; line-height: 0.38; margin-left: -1px; background-color: #42b983 !important;height: 29px; margin-left: 2px;" value="Add">

										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-12" style="margin-bottom: 10px;">
											<label for="sequence" style="user-select: none">
												<input type="checkbox" v-model="is_sequence" id="sequence"> Sequence
											</label>
										</div>
									</div>
								</form>
							</slot>
							<table class="table" style="margin-top: 15px;">
								<thead>
									<tr>
										<th scope="col">SL</th>
										<th scope="col">Serial</th>
										<th scope="col">Product</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>

									<tr v-for="(product, sl) in serial_cart">
										<th scope="row">{{sl+1}}</th>
										<td>{{product.serialNumber}}</td>
										<td>{{product.name}}</td>
										<td @click="remove_serial_item(product.serialNumber)"> <span class="badge badge-danger badge-pill" style="cursor:pointer"><i class="fa fa-times"></i></td>
									</tr>

								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<slot name="footer">
								<button class="modal-default-button" @click="serialHideModal" style="background: #59b901;border: none;font-size: 18px;color: white;">
									OK
								</button>
								<button class="modal-default-button" @click="serialHideModal" style="background: rgb(255, 255, 255);border: none;font-size: 18px;color: #de0000;margin-right: 6px;">
									Close
								</button>
							</slot>
						</div>
					</div>
				</div>
			</div>
		</transition>
	</div>
	<!-- purchase product serial modal -->

	<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
		<div class="row">
			<div class="form-group">
				<label class="col-xs-4 col-lg-2 control-label"> Invoice no </label>
				<div class="col-xs-8 col-lg-2">
					<input type="text" id="invoice" name="invoice" v-model="purchase.invoice" readonly style="padding:2px 3px;width:100%;" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-4 col-lg-2 control-label"> Purchase For </label>
				<div class="col-xs-8 col-lg-2">
					<v-select id="branchDropdown" v-bind:options="branches" v-model="selectedBranch" label="Brunch_name" disabled></v-select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-4 col-lg-2 control-label"> Date </label>
				<div class="col-xs-8 col-lg-2">
					<input class="form-control" id="purchaseDate" name="purchaseDate" type="date" v-model="purchase.purchaseDate" v-bind:disabled="userType == 'u' ? true : false" style="width: 100%;" />
				</div>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-9 col-lg-9">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Supplier & Product Information</h4>
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
						<div class="col-xs-12 col-lg-6">
							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Supplier </label>
								<div class="col-xs-7">
									<v-select v-bind:options="suppliers" v-model="selectedSupplier" v-on:input="onChangeSupplier" label="display_name"></v-select>
								</div>
								<div class="col-xs-1" style="padding: 0;">
									<a href="<?= base_url('supplier') ?>" title="Add New Supplier" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
								</div>
							</div>

							<div class="form-group" style="display:none;" v-bind:style="{display: selectedSupplier.Supplier_Type == 'G' ? '' : 'none'}">
								<label class="col-xs-4 control-label no-padding-right"> Name </label>
								<div class="col-xs-8">
									<input type="text" placeholder="Supplier Name" class="form-control" v-model="selectedSupplier.Supplier_Name" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Mobile No </label>
								<div class="col-xs-8">
									<input type="text" placeholder="Mobile No" class="form-control" v-model="selectedSupplier.Supplier_Mobile" v-bind:disabled="selectedSupplier.Supplier_Type == 'G' ? false : true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Address </label>
								<div class="col-xs-8">
									<textarea class="form-control" v-model="selectedSupplier.Supplier_Address" v-bind:disabled="selectedSupplier.Supplier_Type == 'G' ? false : true"></textarea>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-lg-6">
							<form v-on:submit.prevent="addToCart">
								<div class="form-group">
									<label class="col-xs-4 control-label no-padding-right"> Product </label>
									<div class="col-xs-7">
										<v-select v-bind:options="products" v-model="selectedProduct" label="display_text" v-on:input="onChangeProduct"></v-select>
									</div>
									<div class="col-xs-1" style="padding: 0;">
										<a href="<?= base_url('product') ?>" title="Add New Product" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
									</div>
								</div>

								<div class="form-group" style="display:none;">
									<label class="col-xs-4 control-label no-padding-right"> Group Name</label>
									<div class="col-xs-8">
										<input type="text" id="group" name="group" class="form-control" placeholder="Group name" readonly />
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-4 control-label no-padding-right"> Pur. Rate </label>
									<div class="col-xs-3">
										<input type="text" id="purchaseRate" name="purchaseRate" class="form-control" placeholder="Pur. Rate" v-model="selectedProduct.Product_Purchase_Rate" v-on:input="productTotal" required />
									</div>

									<label class="col-xs-1 control-label no-padding-right"> Qty </label>
									<div v-if="selectedProduct.is_serial == true" class="col-xs-4" style="display: flex;">
										<input type="number" step="0.01" id="quantity" autocomplete="off" name="quantity" class="form-control" placeholder="Quantity" ref="quantity" v-model="selectedProduct.quantity" v-on:input="productTotal" required />
										<button type="button" id="show-modal" @click="serialShowModal" style="background: rgb(195 36 36 / 83%); color: white; border: none; font-size: 15px; height: 24px;width:36px; margin-left: 1px;margin-right:2px"><i class="fa fa-plus"></i></button>
									</div>
									<div v-else class="col-xs-4">
										<input type="number" step="0.01" id="quantity" autocomplete="off" name="quantity" class="form-control" placeholder="Quantity" ref="quantity" v-model="selectedProduct.quantity" v-on:input="productTotal" required />
									</div>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-4 control-label no-padding-right"> Serial Qty </label>
									<div class="col-sm-8" style="display: flex;">										<input type="text" step="0.01" id="quantity" autocomplete="off" name="quantity" class="form-control" placeholder="Serial Quantity" ref="quantity" v-model="selectedProduct.quantity" v-on:input="productTotal" />

										<button type="button" id="show-modal" @click="serialShowModal" style="background: rgb(210, 0, 0); color: white; border: none; font-size: 15px; height: 24px; margin-left: 1px;"><i class="fa fa-plus"></i></button>
									</div>
								</div> -->

								<div class="form-group" style="display:none;">
									<label class="col-xs-4 control-label no-padding-right"> Cost </label>
									<div class="col-xs-3">
										<input type="text" id="cost" name="cost" class="form-control" placeholder="Cost" readonly />
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-4 control-label no-padding-right"> Total Amount </label>
									<div class="col-xs-8">
										<input type="text" id="productTotal" name="productTotal" class="form-control" readonly v-model="selectedProduct.total" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-4 control-label no-padding-right"> Selling Price </label>
									<div class="col-xs-8">
										<input type="text" id="sellingPrice" name="sellingPrice" class="form-control" v-model="selectedProduct.Product_SellingPrice" />
									</div>
								</div>

								<div class="form-group" style="display: none;">
									<label class="col-sm-4 control-label no-padding-right"> Warranty <small>(Month)</small> </label>
									<div class="col-sm-8">
										<input type="number" v-model.number="selectedProduct.warranty_month" min="0" class="form-control" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-4 control-label no-padding-right"> </label>
									<div class="col-xs-8">
										<button type="submit" class="btn btn-default pull-right">Add Cart</button>
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
						<tr>
							<th style="width:4%;color:#000;">SL</th>
							<th style="width:20%;color:#000;">Product Name</th>
							<th style="width:13%;color:#000;">Category</th>
							<th style="width:13%;color:#000;">SubCategory</th>
							<th style="width:10%;color:#000;">Purchase Rate</th>
							<th style="width:5%;color:#000;">Quantity</th>
							<th style="width:13%;color:#000;">Total Amount</th>
							<th style="width:10%;color:#000;display:none;">Warranty</th>
							<th style="width:8%;color:#000;">Action</th>
						</tr>
					</thead>
					<tbody style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
						<tr v-for="(product, sl) in cart">
							<td>{{ sl + 1}}</td>
							<td>{{ product.name }} <br>
								(<span v-for="(SerialCartStore, ind) in product.SerialCartStore ">
									{{ SerialCartStore.serialNumber }}<span v-if="product.SerialCartStore.length > 1">, </span>
								</span>)
							</td>
							<td>{{ product.categoryName }}</td>
							<td>{{ product.subcategoryName }}</td>
							<td>{{ product.purchaseRate }}</td>
							<td>{{ product.quantity }}</td>
							<td>{{ product.total }}</td>
							<td style="display: none;">{{ product.warrantyMonth }}</td>
							<td><a href="" v-on:click.prevent="removeFromCart(sl)"><i class="fa fa-trash"></i></a></td>
						</tr>

						<tr>
							<td colspan="8"></td>
						</tr>

						<tr style="font-weight: bold;">
							<td colspan="5">Note</td>
							<td colspan="3">Total</td>
						</tr>

						<tr>
							<td colspan="5"><textarea style="width: 100%;font-size:13px;" placeholder="Note" v-model="purchase.note"></textarea></td>
							<td colspan="3" style="padding-top: 15px;font-size:18px;">{{ purchase.total }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="col-xs-12 col-md-3 col-lg-3">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Amount Details</h4>
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
						<div class="col-xs-12">
							<div class="table-responsive">
								<table style="color:#000;margin-bottom: 0px;">
									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Sub Total</label>
												<div class="col-xs-12">
													<input type="number" id="subTotal" name="subTotal" class="form-control" v-model="purchase.subTotal" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right"> Vat </label>
												<div clsss="col-xs-12">													
													<input type="number" id="vatPercent" name="vatPercent" v-model="vatPercent" min="0" v-on:input="calculateTotal" style="height:25px;margin-left: 12px;width: 38%;" />
													<span> % </span>
													<input type="number" id="vat" name="vat" v-model="purchase.vat" readonly style="height:25px;width: 42%;" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Discount</label>
												<div class="col-xs-12">
													<input type="number" id="discount" name="discount" class="form-control" min="0" v-model="purchase.discount" v-on:input="calculateTotal" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Transport / Labour Cost</label>
												<div class="col-xs-12">
													<input type="number" id="freight" name="freight" class="form-control" min="0" v-model="purchase.freight" v-on:input="calculateTotal" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total</label>
												<div class="col-xs-12">
													<input type="number" id="total" class="form-control" v-model="purchase.total" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Paid</label>
												<div class="col-xs-12">
													<input type="number" id="paid" class="form-control" min="0" v-model="purchase.paid" v-on:input="calculateTotal" v-bind:disabled="selectedSupplier.Supplier_Type == 'G' ? true : false" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Due</label>
												<div class="col-xs-6">
													<input type="number" id="due" name="due" class="form-control" v-model="purchase.due" readonly />
												</div>
												<div class="col-xs-6">
													<input type="number" id="previousDue" name="previousDue" class="form-control" v-model="purchase.previousDue" readonly style="color:red;" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<div class="col-xs-6">
													<input type="button" class="btn btn-success" value="Purchase" v-on:click="savePurchase" v-bind:disabled="purchaseOnProgress == true ? true : false" style="background:#000;color:#fff;padding:3px;width:100%;">
												</div>
												<div class="col-xs-6">
													<input type="button" class="btn btn-info" onclick="window.location = '<?php echo base_url(); ?>purchase'" value="New Purch.." style="background:#000;color:#fff;padding:3px;width:100%;">
												</div>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#purchase',
		data() {
			return {
				purchase: {
					purchaseId: parseInt('<?php echo $purchaseId; ?>'),
					invoice: '<?php echo $invoice; ?>',
					purchaseFor: '',
					purchaseDate: moment().format('YYYY-MM-DD'),
					supplierId: '',
					subTotal: 0.00,
					vat: 0.00,
					discount: 0.00,
					freight: 0.00,
					total: 0.00,
					paid: 0.00,
					due: 0.00,
					previousDue: 0.00,
					note: ''
				},
				vatPercent: 0.00,
				branches: [],
				selectedBranch: {
					brunch_id: "<?php echo $this->session->userdata('BRANCHid'); ?>",
					Brunch_name: "<?php echo $this->session->userdata('Brunch_name'); ?>"
				},
				suppliers: [],
				selectedSupplier: {
					Supplier_SlNo: null,
					Supplier_Code: '',
					Supplier_Name: '',
					display_name: 'Select Supplier',
					Supplier_Mobile: '',
					Supplier_Address: '',
					Supplier_Type: ''
				},
				oldSupplierId: null,
				oldPreviousDue: 0,
				products: [],
				selectedProduct: {
					Product_SlNo: '',
					Product_Code: '',
					display_text: 'Select Product',
					Product_Name: '',
					Unit_Name: '',
					quantity: '',
					Product_Purchase_Rate: '',
					Product_SellingPrice: 0.00,
					total: '',
					warranty_month: 0
				},
				cart: [],
				purchaseOnProgress: false,
				userType: '<?php echo $this->session->userdata("accountType") ?>',
				serialModalStatus: false,
				get_serial_number: "",
				is_sequence: false,
				serial_cart: [],
				SerialCartStore: [],
				is_disabled: false,
			}
		},
		created() {
			this.getBranches();
			this.getSuppliers();
			this.getProducts();

			if (this.purchase.purchaseId != 0) {
				this.getPurchase();
			}
		},
		methods: {
			getBranches() {
				axios.get('/get_branches').then(res => {
					this.branches = res.data;
				})
			},
			getSuppliers() {
				axios.get('/get_suppliers').then(res => {
					this.suppliers = res.data;
					this.suppliers.unshift({
						Supplier_SlNo: 'S01',
						Supplier_Code: '',
						Supplier_Name: '',
						display_name: 'General Supplier',
						Supplier_Mobile: '',
						Supplier_Address: '',
						Supplier_Type: 'G'
					})
				})
			},
			getProducts() {
				axios.post('/get_products', {
					isService: 'false'
				}).then(res => {
					this.products = res.data;
				})
			},
			serialShowModal() {
				this.serialModalStatus = true;
			},
			serialHideModal() {
				this.serialModalStatus = false;
			},
			async serial_add_action() {
				this.is_disabled = true;
				
				if (this.selectedProduct.Product_SlNo == '') {
					alert("Please select a product");
					this.is_disabled = false;
					return;
				}

				if (this.selectedProduct.quantity <= 0 || this.selectedProduct.quantity == '' || this.selectedProduct.quantity == undefined) {
					alert("Please enter valid Quantity");
					this.is_disabled = false;
					return;
				}

				if (this.selectedProduct.quantity <= this.serial_cart.length) {
					alert(`Invalid Quantity !! You have maximum quantity is ${this.selectedProduct.quantity}.`);
					this.get_serial_number = '';
					this.is_disabled = false;
					return;
				}

				if (this.get_serial_number.trim() == '') {
					alert("Serial Number is Required.");
					this.is_disabled = false;
					return;
				}

				let cartInd = this.serial_cart.findIndex(p => p.serialNumber == this.get_serial_number.trim());

				if (cartInd > -1) {
					alert('Serial Number already exists in Serial List');
					this.is_disabled = false;
					return;
				}

				let serial_cart_obj = {
					productId: this.selectedProduct.Product_SlNo,
					name: this.selectedProduct.Product_Name,
					categoryId: this.selectedProduct.ProductCategory_ID,
					categoryName: this.selectedProduct.ProductCategory_Name,
					purchaseRate: this.selectedProduct.Product_Purchase_Rate,
					salesRate: this.selectedProduct.Product_SellingPrice,
					quantity: this.selectedProduct.quantity,
					total: this.selectedProduct.total,
				}

				if (this.is_sequence) {
					let _serial = this.get_serial_number;
					let _serialAlphabets = _serial.match(/^[a-zA-Z].*\D/)
					let _serialNumbers = _serial.match(/0?\d+$/)
					let firstSerial = _serialNumbers;
					let serialQty = this.selectedProduct.quantity;
					let lastSerial = +firstSerial + +serialQty;
					let prefixed = _serialAlphabets != null ? _serialAlphabets : '';

					let uniqueCheck = await this.isSerialNumberUnique(prefixed + _serial)
					if (uniqueCheck) {
						alert(`Item '${_serial}' already purchased !!`)
						this.is_disabled = false;
						return
					}

					if (firstSerial && lastSerial) {
						for (let serial = firstSerial; serial < lastSerial; serial++) {
							let uniqueCheck = await this.isSerialNumberUnique(prefixed + serial)
							if (uniqueCheck) {
								alert(`Sequence item '${prefixed}${serial}' already purchased !!`)
								this.is_disabled = false;
								return
							}
							let serialObj = {
								...serial_cart_obj
							};
							serialObj.serialNumber = prefixed + serial;
							this.serial_cart.push(serialObj);
						}
					}

					this.is_sequence = false;
				} else {
					let _serialNumber = this.get_serial_number;
					let uniqueCheck = await this.isSerialNumberUnique(_serialNumber)
					if (uniqueCheck) {
						alert(`Item '${_serialNumber}' already purchased !!`)
						this.is_disabled = false;
						return
					}
					let serialObj = {
						...serial_cart_obj
					};
					serialObj.serialNumber = _serialNumber;
					this.serial_cart.push(serialObj);
				}
				this.is_disabled = false;

				this.get_serial_number = '';

				this.$refs.serialnumberadd.focus();
			},
			async isSerialNumberUnique(serial_number) {
				let hasSerialNumber = false;

				await axios.post('/check_serial_number', {
					get_serial_number: serial_number
				}).then(res => {
					if (res.data > 0)
						hasSerialNumber = true;
				})

				return hasSerialNumber;
			},
			async remove_serial_item(serialNumber) {
				var newImeiCart = this.serial_cart.filter((el) => {
					return el.serialNumber != serialNumber;
				});
				this.serial_cart = newImeiCart;
			},
			onChangeSupplier() {
				if (this.selectedSupplier.Supplier_SlNo == null) {
					return;
				}

				if (event.type == 'readystatechange') {
					return;
				}

				if (this.purchase.purchaseId != 0 && this.oldSupplierId != parseInt(this.selectedSupplier.Supplier_SlNo)) {
					let changeConfirm = confirm('Changing supplier will set previous due to current due amount. Do you really want to change supplier?');
					if (changeConfirm == false) {
						return;
					}
				} else if (this.purchase.purchaseId != 0 && this.oldSupplierId == parseInt(this.selectedSupplier.Supplier_SlNo)) {
					this.purchase.previousDue = this.oldPreviousDue;
					return;
				}

				let supplierCode = this.selectedSupplier.Supplier_Code;

				if (supplierCode.charAt(0) == 'C') {
					axios.post('/get_customer_combine_due', {
						code: supplierCode
					}).then(res => {
						if (res.data.length > 0) {
							this.purchase.previousDue = res.data[0].dueAmount;
						} else {
							this.purchase.previousDue = 0;
						}
					})
				} else {
					axios.post('/get_supplier_due', {
						supplierId: this.selectedSupplier.Supplier_SlNo
					}).then(res => {
						if (res.data.length > 0) {
							this.purchase.previousDue = res.data[0].due;
						} else {
							this.purchase.previousDue = 0;
						}
					})
				}

				this.calculateTotal();
			},
			onChangeProduct() {
				this.$refs.quantity.focus();
				this.selectedProduct.warranty_month = 0;
			},
			productTotal() {
				this.selectedProduct.total = (parseFloat(this.selectedProduct.quantity) * parseFloat(this.selectedProduct.Product_Purchase_Rate)).toFixed(2);
			},
			addToCart() {
				let cartInd = this.cart.findIndex(p => p.productId == this.selectedProduct.Product_SlNo);
				if (cartInd > -1) {
					alert('Product exists in cart');
					return;
				}

				let product = {
					productId: this.selectedProduct.Product_SlNo,
					name: this.selectedProduct.Product_Name,
					categoryId: this.selectedProduct.ProductCategory_ID,
					categoryName: this.selectedProduct.ProductCategory_Name,
					subcategoryName: this.selectedProduct.subcategoryName,
					purchaseRate: this.selectedProduct.Product_Purchase_Rate,
					salesRate: this.selectedProduct.Product_SellingPrice,
					quantity: this.selectedProduct.quantity,
					total: this.selectedProduct.total,
					// discount: this.selectedProduct.discount,
					warrantyMonth: this.selectedProduct.warranty_month ?? 0,
					SerialCartStore: []
				}

				this.serial_cart.forEach((obj) => {
					// let getObj = Object.assign(obj, {
					// 	discount: this.selectedProduct.discount
					// });
					let getObj = Object.assign(obj, {
						objLength: this.cart.length
					});
					product.SerialCartStore.push(getObj);
				});

				if (product.quantity == '' || product.quantity == 0) {
					alert('Product quantity is required');
					return;
				}
				if (this.selectedProduct.is_serial == 1 && product.SerialCartStore.length == 0) {
					alert('This product only serial purchase available');
					return;
				}
				if (this.selectedProduct.is_serial == 0 && product.SerialCartStore.length > 0) {
					alert('This product only non serial purchase available');
					return;
				}
				if (this.selectedProduct.is_serial == 1 && product.quantity != product.SerialCartStore.length) {
					alert('Proudct serial incompleted. Must need more serial quantity');
					return;
				}

				this.cart.push(product);
				this.serial_cart = [];
				this.get_serial_number = '';
				this.clearSelectedProduct();
				this.calculateTotal();
			},
			async removeFromCart(ind) {
				if (this.cart[ind].id) {
					let stock = await axios.post('/get_product_stock', {
						productId: this.cart[ind].productId
					}).then(res => res.data);
					if (this.cart[ind].quantity > stock) {
						alert('Stock unavailable');
						return;
					}
				}
				this.cart.splice(ind, 1);
				this.calculateTotal();
			},
			clearSelectedProduct() {
				this.selectedProduct = {
					Product_SlNo: '',
					Product_Code: '',
					display_text: 'Select Product',
					Product_Name: '',
					Unit_Name: '',
					quantity: '',
					Product_Purchase_Rate: '',
					Product_SellingPrice: 0.00,
					total: '',
					warranty_month: 0
				}
			},
			calculateTotal() {
				this.purchase.subTotal = this.cart.reduce((prev, curr) => {
					return prev + parseFloat(curr.total);
				}, 0).toFixed(2);
				this.purchase.vat = ((this.purchase.subTotal * parseFloat(this.vatPercent)) / 100).toFixed(2);

				this.purchase.vat = isNaN(this.purchase.vat) || this.purchase.vat == '' ? 0 : this.purchase.vat;
				this.vatPercent = isNaN(this.vatPercent) || this.vatPercent == '' ? 0 : this.vatPercent;
				this.purchase.discount = isNaN(this.purchase.discount) || this.purchase.discount == '' ? 0 : this.purchase.discount;
				this.purchase.freight = isNaN(this.purchase.freight) || this.purchase.freight == '' ? 0 : this.purchase.freight;
				// this.purchase.paid = isNaN(this.purchase.paid) || this.purchase.paid == '' ? 0 : this.purchase.paid;

				this.purchase.total = ((parseFloat(this.purchase.subTotal) + parseFloat(this.purchase.vat) + parseFloat(this.purchase.freight)) - parseFloat(this.purchase.discount)).toFixed(2);
				if (this.selectedSupplier.Supplier_Type == 'G') {
					this.purchase.paid = this.purchase.total;
					this.purchase.due = 0;
				} else {
					if (event.target.id != 'paid') {
						this.purchase.paid = 0;
					}

					this.purchase.due = (parseFloat(this.purchase.total) - parseFloat(this.purchase.paid)).toFixed(2);
				}
			},
			savePurchase() {
				if (this.selectedSupplier.Supplier_SlNo == null) {
					alert('Select supplier');
					return;
				}

				if (this.purchase.purchaseDate == '') {
					alert('Enter purchase date');
					return;
				}

				if (this.cart.length == 0) {
					alert('Cart is empty');
					return;
				}

				this.purchase.supplierId = this.selectedSupplier.Supplier_SlNo;
				this.purchase.purchaseFor = this.selectedBranch.brunch_id;

				this.purchaseOnProgress = true;

				let data = {
					purchase: this.purchase,
					cartProducts: this.cart
				}

				if (this.selectedSupplier.Supplier_Type == 'G') {
					data.supplier = this.selectedSupplier;
				}

				let url = '/add_purchase';
				if (this.purchase.purchaseId != 0) {
					url = '/update_purchase';
				}

				// console.log(data);
				// return

				axios.post(url, data).then(async res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						let conf = confirm('Do you want to view invoice?');
						if (conf) {
							window.open(`/purchase_invoice_print/${r.purchaseId}`, '_blank');
							await new Promise(r => setTimeout(r, 1000));
							window.location = '/purchase';
						} else {
							window.location = '/purchase';
						}
					} else {
						this.purchaseOnProgress = false;
					}
				})
			},
			getPurchase() {
				axios.post('/get_purchases', {
					purchaseId: this.purchase.purchaseId
				}).then(res => {
					let r = res.data;
					let purchase = r.purchases[0];

					this.selectedSupplier.Supplier_SlNo = purchase.Supplier_SlNo;
					this.selectedSupplier.Supplier_Code = purchase.Supplier_Code;
					this.selectedSupplier.Supplier_Name = purchase.Supplier_Name;
					this.selectedSupplier.Supplier_Mobile = purchase.Supplier_Mobile;
					this.selectedSupplier.Supplier_Address = purchase.Supplier_Address;
					this.selectedSupplier.Supplier_Type = purchase.Supplier_Type;
					this.selectedSupplier.display_name = purchase.Supplier_Type == 'G' ? 'General Supplier' : `${purchase.Supplier_Code} - ${purchase.Supplier_Name}`;

					this.purchase.invoice = purchase.PurchaseMaster_InvoiceNo;
					this.purchase.purchaseFor = purchase.PurchaseMaster_PurchaseFor;
					this.purchase.purchaseDate = purchase.PurchaseMaster_OrderDate;
					this.purchase.supplierId = purchase.Supplier_SlNo;
					this.purchase.subTotal = purchase.PurchaseMaster_SubTotalAmount;
					this.purchase.vat = purchase.PurchaseMaster_Tax;
					this.purchase.discount = purchase.PurchaseMaster_DiscountAmount;
					this.purchase.freight = purchase.PurchaseMaster_Freight;
					this.purchase.total = purchase.PurchaseMaster_TotalAmount;
					this.purchase.paid = purchase.PurchaseMaster_PaidAmount;
					this.purchase.due = purchase.PurchaseMaster_DueAmount;
					this.purchase.previousDue = purchase.previous_due;
					this.purchase.note = purchase.PurchaseMaster_Description;

					this.oldSupplierId = purchase.Supplier_SlNo;
					this.oldPreviousDue = purchase.previous_due;

					this.vatPercent = (this.purchase.vat * 100) / this.purchase.subTotal;

					r.purchaseDetails.forEach(product => {
						let cartProduct = {
							id: product.PurchaseDetails_SlNo,
							productId: product.Product_IDNo,
							name: product.Product_Name,
							categoryId: product.ProductCategory_ID,
							categoryName: product.ProductCategory_Name,
							purchaseRate: product.PurchaseDetails_Rate,
							salesRate: product.Product_SellingPrice,
							quantity: product.PurchaseDetails_TotalQuantity,
							total: product.PurchaseDetails_TotalAmount,
							warrantyMonth: product.warranty_month,
							SerialCartStore: []
						}

						product.serial.forEach((obj) => {
							let serial_cart_obj = {
								productId: obj.ps_prod_id,
								name: product.Product_Name,
								categoryId: product.ProductCategory_ID,
								categoryName: product.ProductCategory_Name,
								purchaseRate: product.PurchaseDetails_Rate,
								salesRate: product.Product_SellingPrice,
								quantity: product.PurchaseDetails_TotalQuantity,
								total: product.PurchaseDetails_TotalAmount,
								serialNumber: obj.ps_serial_number,
								discount: product.PurchaseDetails_Discount
							}
							cartProduct.SerialCartStore.push(serial_cart_obj);
						})

						this.cart.push(cartProduct);
					})
				})
			}
		}
	})
</script>