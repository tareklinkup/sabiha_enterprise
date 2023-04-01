<div class="row" id="expense">
    <div class="col-md-8 col-md-offset-2">
        <form @submit.prevent="saveexpense">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label for="name" class="col-md-4">Exp. Name <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" v-model="expense.name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-md-4">Exp. Rate <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="number" min="0" class="form-control" v-model="expense.rate" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-md-4"></label>
                        <div class="col-md-8">
                            <input type="submit" class="btn btn-success btn-block" value="Save">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <hr>
    </div>
    <div class="col-md-8 col-md-offset-2">
        <div class="table-responsive">
            <datatable :columns="columns" :data="expenses" :filter-by="filter">
                <template scope="{ row }">
                    <tr>
                        <td>{{ row.sl }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.rate }}</td>
                        <td>
                            <?php if($this->session->userdata('accountType') != 'u'){?>
                            <button type="button" class="button edit" @click="editExpense(row)">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button type="button" class="button" @click="deleteExpense(row.id)">
                                <i class="fa fa-trash"></i>
                            </button>
                            <?php }?>
                        </td>
                    </tr>
                </template>
            </datatable>
            <datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vuejs-datatable.js"></script>
<script>
    const app = new Vue({
        el: '#expense',
        data: {
            expense: {
                id: null,
                name: '',
                rate: ''
            },
            expenses: [],
            columns: [
                { label: 'Serial', field: 'sl', align: 'center', filterable: false },
                { label: 'Expense Name', field: 'name', align: 'center' },
                { label: 'Expense Rate', field: 'rate', align: 'center' },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        },
        created() {
            this.getexpenses();
        },
        methods: {
            getexpenses() {
                axios.post('/get_expense')
                .then(res => {
                    this.expenses = res.data.map((item, sl) => {
                        item.sl = sl + 1;
                        return item;
                    })
                })
            },
            saveexpense() {
                if(this.expense.name == '') {
                    alert('Name is required');
                    return;
                }

                let url = '';
                if(this.expense.id != null) {
                    url = '/update_expense';
                } else {
                    url = '/add_expense';
                    delete this.expense.id;
                }

                axios.post(url, this.expense)
                .then(res => {
                    alert(res.data.message);
                    if(res.data.success) {
                        this.resetForm();
                        this.getexpenses();
                    }
                })
                .catch(err => {
                    alert(err.response.data.message)
                })
            },
            editExpense(expense) {
                Object.keys(expense).forEach(key => {
                    this.expense[key] = expense[key];
                })
            },
            deleteExpense(id) {
                if(confirm('Are you sure ?')) {
                    axios.post('/delete_expense', {id: id})
                    .then(res => {
                        alert(res.data.message);
                        if(res.data.success) {
                            this.getexpenses();
                        }
                    })
                }
            },
            resetForm() {
                this.expense.id = null;
                this.expense.name = '';
                this.expense.rate = '';
            }
        }
    })
</script>