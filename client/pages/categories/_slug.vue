<template>
<div>
	<b-container class="main">
	  	<b-breadcrumb>
	  		<b-breadcrumb-item :to="{name: 'index'}" id="home-breadcrumb">
	  			Home
	  		</b-breadcrumb-item>
	  		<b-breadcrumb-item 
	  		v-for="ancestor in category.ancestors" :key="ancestor.id"
	  		:to="{name: 'categories-slug', params: {slug: ancestor.slug}}">
	  			{{ ancestor.name }}
	  		</b-breadcrumb-item>
	  	</b-breadcrumb>
		<b-row>
			<b-col class="left-sidebar">
				<b-nav vertical>
					<b-nav-item v-for="child in category.children" :key="child.id"
					:to="{name: 'categories-slug', params: {slug: child.slug}}">
						{{ child.name }}
					</b-nav-item>
				</b-nav>
				<form @submit.prevent="loadProducts">
					<div class="form-group row">
						<div class="col">
							<label for="min_price">Min price</label>
							<input id="min_price" name="min_price" 
							type="number" class="form-control"
							v-model="filter.min_price">
							</input>							
						</div>

						<div class="col">
							<label for="max_price">Max price</label>
							<input id="max_price" name="max_price" 
							type="number" class="form-control"
							v-model="filter.max_price">
							</input>							
						</div>
					</div>

					<b-button type="submit" variant="primary">Filter</b-button>
				</form>
			</b-col>

			<b-col cols="10" class="main-content">

				<b-card
				:title="category.name">
					<b-card-text>
						{{ category.description }}
					</b-card-text>
				</b-card>

				<div class="card-list">
					<div class="card-list-item card m-3"
					v-for="product in products"
					>
						<nuxt-link 
						:to="{name: 'products-slug', params:{slug: product.slug}}">
							<img :src="$storageUrl(product.image)" alt="" class="card-img-top img-md">

							<div class="card-footer">
								{{ product.name }} <br>
								<span class="price">
									{{ product.price }}
								</span>
							</div>
						</nuxt-link>
					</div>
				</div>

			</b-col>
		</b-row>
	</b-container>
</div>
</template>

<script>
export default {
	layout: 'basic',

	data() {
		return {
			category_key: this.$route.params.slug,
			category: {},
			products: [],
			filter: {
				min_price: undefined,
				max_price: undefined,
			},
		}
	},

	mounted() {
		this.$axios.post(`categories/${this.category_key}`)
		.then(resp => {
			this.category = resp.data.data;
		});

		this.loadProducts();
	},

	methods: {
		loadProducts() {
			let filter = this.prepareFilterArgs(this.filter);

			this.$axios.post(
				`categories/${this.category_key}/products`,
				filter
			)
			.then(resp => {
				this.products = resp.data.data;
			})
			.catch(errors => {
				console.log(errors);
			});
		},

		prepareFilterArgs(filter) {
			let result = {};

			for (let key in filter) {
				if (filter[key] !== '') {
					result[key] = filter[key];
				};
			}

			return result;
		}
	},	
}	
</script>

<style lang="scss">
.product-img-sm {
	border: 2px solid #343a40;
	border-radius: 10%;
	width: 10rem;
	height: 10rem;
}	


</style>
