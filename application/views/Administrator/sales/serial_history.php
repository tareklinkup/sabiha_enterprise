<style>
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
</style>

<div id="stock">
	<div class="row">
		<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
			<div class="form-group" style="margin-top:10px;">
				<label class="col-sm-1 col-sm-offset-1 control-label no-padding-right"> Select Serial </label>
				<div class="col-sm-2"> 
					<v-select v-bind:options="serial_list" v-model="selectedSerial" label="ps_serial_number"></v-select>
				</div>
            </div> 
         
			<div class="form-group">
				<div class="col-sm-2"  style="margin-left:15px;">
					<input type="button" class="btn btn-primary" value="Show Report" v-on:click="getStock" style="margin-top:0px;border:0px;height:28px;">
				</div>
			</div>
		</div>
	</div>
	<div class="row" v-if="searchType != null" style="display:none" v-bind:style="{display: searchType == null ? 'none' : ''}">
		<div class="col-md-12">
			<a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
            <h6>Purchase Serial Detail </h6> 
			<table class="table" style="display: none;" :style="{display: serialReport.purchaseReport ? '' : 'none'}"> 
				<tr v-for="(serial, sl) in serialReport.purchaseReport">
					<td>Supplier Name : </td>
					<td>{{  serial.Supplier_Name }}</td>
				</tr>
				<tr v-for="(serial, sl) in serialReport.purchaseReport">
					<td>Supplier Code : </td>
					<td>{{  serial.Supplier_Code }}</td>
				</tr>
				<tr v-for="(serial, sl) in serialReport.purchaseReport">
					<td>Supplier Mobile : </td>
					<td>{{  serial.Supplier_Mobile }}</td>
				</tr>
				<tr v-for="(serial, sl) in serialReport.purchaseReport">
					<td>Purchase Invoice  : </td>
					<td>{{  serial.invoiceNo }}</td>
				</tr>
				<tr v-for="(serial, sl) in serialReport.purchaseReport">
					<td>Product Name  : </td>
					<td>{{  serial.Product_Name }}</td>
				</tr>
				<tr v-for="(serial, sl) in serialReport.purchaseReport">
					<td>Purchase Date  : </td>
					<td>{{  serial.purchaseDate }}</td>
				</tr>
				<tr v-for="(serial, sl) in serialReport.purchaseReport">
					<td>Warranty Month  : </td>
					<td>{{ serial.warranty_month }} <small v-if="serial.warranty_month">Months</small></td>
				</tr>
				<tr v-for="(serial, sl) in serialReport.purchaseReport">
					<td>Purchase Branch  : </td>
					<td>{{ serial.Brunch_name }}</td>
				</tr>
            </table>
        </div>
        
        <div class="col-md-6">
        <h6>Sale Serial Detail </h6> 
			<table class="table" style="display: none;" :style="{display: serialReport.saleReport ? '' : 'none'}">
                   
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Customer Name : </td>
                       <td>{{  serial.Customer_Name }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Customer Code : </td>
                       <td>{{  serial.Customer_Code }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Customer Mobile : </td>
                       <td>{{  serial.Customer_Mobile }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Sale Invoice  : </td>
                       <td>{{  serial.invoiceNo }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Product Name  : </td>
                       <td>{{  serial.Product_Name }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Sale Date  : </td>
                       <td>{{  serial.saleDate }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Warranty Date: </td>
                       <td><span v-if="serial.warranty_date">{{ serial.warranty_date }}</span></td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Sales Branch : </td>
                       <td>{{ serial.Brunch_name }}</td>
                   </tr>
            </table>
		</div>
	</div>
</div>





<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>

<script>
	Vue.component('modal', {
	template: '#serial-modal'
	})
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#stock',
		data(){
			return {
				searchTypes: [
					{text: 'Current Stock', value: 'current'},
					{text: 'Total Stock', value: 'total'},
					{text: 'Category Wise Stock', value: 'category'},
					{text: 'Product Wise Stock', value: 'product'},
					//{text: 'Brand Wise Stock', value: 'brand'}
				],
				selectedSearchType: {
					text: 'select',
					value: ''
				},
				searchType: null,
				categories: [],
				selectedCategory: null,
				products: [],
				selectedProduct: null,
				brands: [],
				selectedBrand: null,
				selectionText: '',
				stock: [],
				serials:[],
				totalStockValue: 0.00,
				current_quantity:0,
                serial_list:[],
                selectedSerial:null,
                serialReport:[]

			}
		},
		created(){
            this.getSerialS();
		},
		methods:{
            async getSerialS(){
				await axios.post(`/get_serial_list`).then((res)=>{
					this.serial_list = res.data;
				})
            },
			async getStock(){
				await axios.post('/get_serial_report',{serial:this.selectedSerial.ps_serial_number})
				.then((res)=>{
					this.serialReport = res.data;
				});
			},
			onChangeSearchType(){
				if(this.selectedSearchType.value == 'category' && this.categories.length == 0){
					this.getCategories();
				} else if(this.selectedSearchType.value == 'brand' && this.brands.length == 0){
					this.getBrands();
				} else if(this.selectedSearchType.value == 'product' && this.products.length == 0){
					this.getProducts();
				}
			},
			getCategories(){
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
			getProducts(){
				axios.get('/get_products').then(res => {
					this.products =  res.data;
				})
			},
			getBrands(){
				axios.get('/get_brands').then(res => {
					this.brands = res.data;
				})
			},
			async print(){
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">${this.selectedSearchType.text} Report</h4 style="text-align:center">
						<h6 style="text-align:center">${this.selectionText}</h6>
					</div>
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#stockContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}, left=0, top=0`);
				reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				reportWindow.document.body.innerHTML += reportContent;

				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}
	})
</script>