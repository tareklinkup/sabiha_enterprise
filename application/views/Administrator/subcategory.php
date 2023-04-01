<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select.open .dropdown-toggle{
		border-bottom: 1px solid #ccc;
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
	#products label{
		font-size:13px;
	}
	#products select{
		border-radius: 3px;
	}
	#products .add-button{
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display:block;
		text-align: center;
		color: white;
	}
	#products .add-button:hover{
		background-color: #41add6;
		color: white;
	}
</style>
<div id="app">
    <div class="widget-box">
        <div class="widget-header">
            <h4 class="widget-title">Sub Category Entry</h4>
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
                    <div class="col-md-4 col-md-offset-4">
                        <form @submit.prevent="saveCategory">
                            <div class="form-group">
                               <label for="catgory" class="col-md-3">Categories</label>
                               <div class="col-md-9">
                               <v-select v-bind:options="categories" v-model="selectedCategory" label="ProductCategory_Name"></v-select>
                               </div>
                            </div>
                            <div class="form-group">
                               <label for="name" class="col-md-3">Name</label>
                               <div class="col-md-9">
                                    <input type="text" class="form-control" v-model="category.name" required>
                               </div>
                            </div>
                            <div class="form-group">
                               <label for="name" class="col-md-3"></label>
                               <div class="col-md-9">
                                    <input type="submit" class="btn btn-sm btn-info" value="Save">
                               </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="form-inline">
                            <div class="form-group">
                                <label for="filter" class="sr-only">Filter</label>
                                <input type="text" class="form-control" v-model="filter" placeholder="Filter">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <datatable :columns="columns" :data="subcategories" :filter-by="filter">
                                <template scope="{ row }">
                                    <tr>
                                        <td>{{ row.sl }}</td>
                                        <td>{{ row.ProductCategory_Name }}</td>
                                        <td>{{ row.name }}</td>
                                        <td>
                                            <?php if($this->session->userdata('accountType') != 'u'){?>
                                            <button type="button" class="button edit" @click="editCategoary(row)">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button type="button" class="button" @click="deleteCategory(row.id)">
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
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    const app = new Vue({
        el: '#app',
        data: {
            category: {
                id: null,
                category_id: null,
                name: ''
            },
            categories: [],
            selectedCategory: null,
            subcategories: [],

            columns: [
                { label: 'Sl', field: 'sl', align: 'center', filterable: false },
                { label: 'Category Name', field: 'ProductCategory_Name', align: 'center' },
                { label: 'Sub. Name', field: 'name', align: 'center' },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        },
        watch: {
            selectedCategory(category) {
                if(category == undefined) return;
                this.category.category_id = category.ProductCategory_SlNo;
            }
        },
        created() {
            this.getCategories();
            this.getSubcategories();
        },
        methods: {
            getCategories(){
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
            getSubcategories() {
                axios.get('/get_subcategories')
                .then(res => {
                    this.subcategories = res.data.map((item, sl) => {
                        item.sl = sl + 1;
                        return item;
                    });
                })
            },
            async saveCategory() {
                if(this.category.category_id == null) {
                    alert('Select category first');
                    return;
                }
                if(this.category.name == '') {
                    alert('Category name is required');
                    return;
                }

                let url = '';
                if(this.category.id != null) {
                    url = '/update_subcategory';
                } else {
                    url = '/add_subcategory';
                    delete this.category.id;
                }

                await axios.post(url, this.category)
                .then(res => {
                    if(res.data.success) {
                        alert(res.data.message)
                        this.resetFrom();
                        this.getSubcategories();
                    } else {
                        alert(res.data.message)
                    }
                })
                .catch(err => {
                    alert(err.response.data.message);
                })
            },
            resetFrom() {
                this.category.name = '';
                this.selectedCategory = null;
            },
            editCategoary(category) {
                Object.keys(this.category).forEach(key => {
                    this.category[key] = category[key]
                })
                this.selectedCategory = this.categories.find(item => item.ProductCategory_SlNo == category.category_id)
            },
            async deleteCategory(id) {
                if(confirm('Are you sure to delete ?')) {
                    await axios.post('/delete_subcategory', {id: id})
                    .then(res => {
                        if(res.data.success) {
                            alert(res.data.message);
                            this.getSubcategories();
                        } else {
                            alert(res.data.message);
                        }
                    })
                    .catch(err => {
                        alert(err.response.data.message);
                    })
                }
            }
        }
    })
</script>