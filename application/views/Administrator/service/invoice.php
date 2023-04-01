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
        text-align: center;
	}
    .record-table th{
        text-align: center;
    }
</style>
<div id="invoices">
    <div class="col-md-8 col-md-offset-2">
        <div class="row">
            <div class="col-xs-12">
                <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
            </div>
        </div>
        <div id="invoiceContent">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div style="border-top: 1px dotted;border-bottom: 1px dotted;">
                        <h4>Repair Invoice</h4>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-7">
                    <strong>Customer Name:</strong> {{ service.Customer_Name }}<br>
                    <strong>Customer Mobile:</strong> {{ service.Customer_Mobile }} <br>
                    <strong>Customer Address:</strong> {{ service.Customer_Address }}
                </div>
                <div class="col-xs-5 text-right">
                    <strong>Save By:</strong> {{ service.added_by }}<br>
                    <strong>Invoice No.:</strong> {{ service.invoice }}<br>
                    <strong>Replace Date:</strong> {{ service.date }}<br>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-12">
                    <table class="record-table">
                        <thead>
                            <tr>
                                <td>Sl.</td>
                                <td>Product Name</td>
                                <td>Model</td>
                                <td>IMEI</td>
                                <td>Quantity</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, sl) in cart">
                                <td>{{ sl + 1 }}</td>
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

            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-12">
                    <table class="record-table">
                        <thead>
                            <tr>
                                <td>Sl.</td>
                                <td>Expense Name</td>
                                <td>Rate</td>
                                <td>Unit</td>
                                <td>Amount</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(expense, sl) in expenses">
                                <td>{{ sl + 1 }}</td>
                                <td>{{ expense.expense }}</td>
                                <td>{{ expense.price }}</td>
                                <td>{{ expense.quantity }}</td>
                                <td>{{ expense.amount }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-3 col-xs-offset-9">
                    <table style="width: 100%;margin-top: 10px">
                        <tr>
                            <td width="60%"><strong>Total:</strong></td>
                            <td width="40%" style="text-align: right;">{{ service.total }}</td>
                        </tr>
                        <tr>
                            <td width="60%"><strong>Paid:</strong></td>
                            <td width="40%" style="text-align: right;">{{ service.paid }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-bottom: 1px solid #ccc"></td>
                        </tr>
                        <tr>
                            <td width="60%"><strong>Due:</strong></td>
                            <td width="40%" style="text-align: right;">{{ service.due }}</td>
                        </tr>
                    </table>
                </div>
            </div>
    
            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-12">
                    <strong>Note: </strong>
                    <p style="white-space: pre-line">{{ service.note }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
    const app = new Vue({
        el: '#invoices',
        data: {
            invoice: '<?php echo $invoice?>',
            service: {},
            cart: [],
            expenses: []
        },
        async created() {
            await this.getServices();
        },
        methods: {
            async getServices() {
                await axios.post('/get_service', { invoice: this.invoice })
                .then(res => {
                    this.service = res.data.services[0];
                    this.cart = res.data.serviceDetails;
                    this.expenses = res.data.expenseDetails;
                })
            },
            async print(){
                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#invoiceContent').innerHTML}
							</div>
						</div>
                        <div class="row" style="border-bottom:1px solid #ccc;margin-bottom:5px;padding-bottom:6px;">
                            <div class="col-xs-6">
                                <span style="text-decoration:overline;">Received by</span><br><br>
                                ** THANK YOU FOR YOUR BUSINESS **
                            </div>
                            <div class="col-xs-6 text-right">
                                <span style="text-decoration:overline;">Authorized Signature</span>
                            </div>
                        </div>

                        <div class="row" style="font-size:12px;">
                            <div class="col-xs-6">
                                Print Date: ${moment().format('DD-MM-YYYY h:mm a')}, Printed by: ${this.service.added_by}
                            </div>
                        </div>
					</div>
				`;

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
                            text-align: center;
						}
						.record-table th{
							text-align: center;
						}
					</style>
				`;
				reportWindow.document.body.innerHTML += reportContent;
			

				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
        }
    })
</script>