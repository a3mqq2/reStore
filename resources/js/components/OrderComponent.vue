<template>
  <div class="row">
    <div class="col-md-12" v-if="isLoading">
      <div class="loader-container">
        <div class="loader"></div>
      </div>
    </div>
    <div class="col-md-12" v-else>
      <div v-if="selectedProduct" class="modal fade" id="requirementsModal" tabindex="-1" aria-labelledby="requirementsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="requirementsModalLabel">إدخال المتطلبات</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div v-if="selectedProduct && selectedProduct.requirements" v-for="(requirement, index) in selectedProduct.requirements" :key="index" class="mb-3">
                <label :for="'requirement-' + requirement.id">{{ requirement.name }}</label>
                <div v-if="requirement.type == 'text'">
                  <input type="text" class="form-control" v-model="requirement.value" />
                </div>
                <div v-else>
                  <select class="form-control" v-model="requirement.selectedItem">
                    <option v-for="item in requirement.list_items" :key="item.id" :value="item.id">{{ item.item }}</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
              <button type="button" class="btn btn-primary" @click="addProductWithRequirements">إضافة إلى الطلب</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-header bg-primary text-light">بيانات الطلب</div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="customer">الزبون</label>
              <v-select :options="customers" label="name" v-model="selectedCustomer"></v-select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="paymentMethod">طريقة الدفع</label>
              <v-select :options="paymentMethods" label="name" v-model="selectedPaymentMethod" @input="updatePricesByPaymentMethod"></v-select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="orderDate">تاريخ الطلب</label>
              <input type="date" name="date" class="form-control" v-model="orderDate" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <div class="card">
          <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs m-0">
              <li class="nav-item p-1">
                <a class="nav-link border" :class="{ active: activeTab == 1 }" @click="activeTab = 1">الشحن باستخدام ID</a>
              </li>
              <li class="nav-item p-1">
                <a class="nav-link border" :class="{ active: activeTab == 2 }" @click="activeTab = 2">اكواد الالعاب</a>
              </li>
            </ul>
          </div>
          <div class="card-body" style="height: 500px; overflow-y: auto;">
            <div class="row">
              <div v-for="product in filteredProducts" :key="product.id" class="col-12 col-md-6 mb-3">
                <div class="card border h-100">
                  <img :src="product.image" class="card-img-top" :alt="product.name" />
                  <div class="card-body">
                    <span>اسم المنتج: {{ product.name }}</span>
                    <div v-if="product.discount && product.discount.is_active">
                      <span class="badge badge-warning bg-warning"><i class="fas fa-percentage"></i> خصم {{ product.discount.discount_percentage }}%</span>
                    </div>
                    <div class="row">
                      <div class="col-12 mt-3" v-for="variant in product.variants" :key="variant.id">
                        <div class="card bg-light border border-primary">
                          <div class="card-body">
                            <span>اسم الفئة: {{ variant.name }}</span><br />
                            <span>سعر الفئة: {{ getVariantPrice(variant) }} نقطة </span><br />
                            <button class="btn btn-success mt-2 btn-sm" @click="selectVariant(product, variant)">
                              <i class="fa fa-cart-plus"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-7">
        <div class="card mb-4">
          <div class="card-header bg-primary text-light">لوحة الطلب</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>اسم المنتج</th>
                    <th>الكمية</th>
                    <th>الفئة</th>
                    <th>سعر الفئة</th>
                    <th>متطلبات الشحن</th>
                    <th>الإعدادات</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in selectedProducts" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.variant }}</td>
                    <td>{{ item.price }} نقطة</td>
                    <td>
                      <span class="badge badge-primary bg-primary m-2" v-for="(req, reqIndex) in item.requirements">{{ req.name }} : {{ req.value }}</span>
                    </td>
                    <td>
                      <button class="btn btn-danger btn-sm" @click="removeProduct(index)">حذف</button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="5" class="bg-light">الاجمالي الكلي</th>
                    <th colspan="2" class="bg-light"> {{ totalAmount.toFixed(2) }} {{ selectedPaymentMethod ? selectedPaymentMethod.currency.symbol : "" }} </th>
                  </tr>
                  <tr>
                    <th colspan="5" class="bg-light">الاجمالي بعد الخصم</th>
                    <th colspan="2" class="bg-light"> {{ discountedTotal.toFixed(2) }} {{ selectedPaymentMethod ? selectedPaymentMethod.currency.symbol : "" }} </th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-header bg-primary text-light">تطبيق الكوبون</div>
          <div class="card-body">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="أدخل الكوبون" v-model="couponCode" />
              <button class="btn btn-primary" @click="applyCoupon">تطبيق</button>
            </div>
            <div v-if="couponMessage" class="alert" :class="{'alert-success': couponSuccess, 'alert-danger': !couponSuccess}">
              {{ couponMessage }}
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-primary text-light">اتمام الطلب</div>
          <div class="card-body">
            <textarea v-model="orderNotes" cols="30" rows="5" class="form-control"></textarea>
            <button class="btn btn-success mt-4" @click="submitOrder">اتمام الطلب</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

export default {
  components: { vSelect },
  props: {
    orderId: {
      type: Number,
      required: false,
    }
  },
  data() {
    return {
      isLoading: false,
      customers: [],
      paymentMethods: [],
      products: [],
      selectedCustomer: null,
      selectedPaymentMethod: null,
      selectedProducts: [],
      orderDate: '',
      orderNotes: '',
      activeTab: 1,
      selectedProduct: null,
      selectedVariant: null,
      couponCode: '',
      couponMessage: '',
      couponSuccess: false,
      discountPercentage: 0,
      totalAmount: 0,
      discountedTotal: 0,
      coupon: null,
    };
  },
  computed: {
    filteredProducts() {
      return this.products.filter(product => product.category_id == this.activeTab);
    },
  },
  methods: {
    fetchOrderData() {
      if (!this.orderId) return;

      this.isLoading = true;
      this.$instance.get(`/orders/${this.orderId}`).then(response => {
        const order = response.data.order;
        this.selectedCustomer = order.customer;
        this.selectedPaymentMethod = order.payment_method;
        this.orderDate = order.order_date;
        this.orderNotes = order.order_notes;
        this.selectedProducts = order.products.map(product => ({
          ...product,
          requirements: product.requirements.map(req => ({
            ...req,
            value: req.value,
            selectedItem: req.selectedItem || '',
            list_items: req.list_items || []
          }))
        }));
        this.calculateTotals();
        this.isLoading = false;
      }).catch(error => {
        this.isLoading = false;
        this.$toastr.error('حدث خطأ أثناء جلب البيانات.');
      });
    },
    fetchProducts() {
      this.isLoading = true;
      this.$instance.get('/products').then(response => {
        this.products = response.data.products.map(product => {
          return {
            ...product,
          }
        });
        this.customers = response.data.customers;
        this.paymentMethods = response.data.paymentMethods;
        this.selectedPaymentMethod = this.paymentMethods[0]; // Set default payment method
        if (this.orderId) {
          this.fetchOrderData();
        } else {
          this.isLoading = false;
        }
      }).catch(error => {
        this.isLoading = false;
        this.$toastr.error('حدث خطأ أثناء جلب البيانات.');
      });
    },
    selectVariant(product, variant) {
      this.selectedProduct = {
        ...product,
        requirements: product.requirements ? product.requirements.map(req => ({
          ...req,
          value: '',
          selectedItem: '',
          list_items: req.list_items ? req.list_items : []
        })) : []
      };
      this.selectedVariant = variant;
      if (product.category_id == 1 && this.selectedProduct.requirements.length > 0) {
        this.showModal();
      } else {
        this.addProductToOrder(product, variant);
      }
    },
    showModal() {
      Vue.nextTick(() => {
        const modalElement = document.getElementById('requirementsModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
      });
    },
    addProductWithRequirements() {
      const requirements = this.selectedProduct.requirements.map(req => ({
        name: req.name,
        value: req.type == 'text' ? req.value : req.list_items.find(item => item.id == req.selectedItem).item
      }));

      const existingProductIndex = this.selectedProducts.findIndex(
        item => item.name == this.selectedProduct.name && item.variant == this.selectedVariant.name
      );

      if (existingProductIndex !== -1) {
        this.selectedProducts[existingProductIndex].quantity++;
        this.selectedProducts[existingProductIndex].requirements = requirements;
      } else {
        this.selectedProducts.push({
          product_id: this.selectedProduct.id,
          variant_id: this.selectedVariant.id,
          name: this.selectedProduct.name,
          quantity: 1,
          variant: this.selectedVariant.name,
          price: this.getVariantPrice(this.selectedVariant),
          originalPrice: this.selectedVariant.price,
          category_id: this.selectedProduct.category_id,
          requirements: requirements,
        });
      }

      this.selectedProduct = null;
      this.selectedVariant = null;
      const modalElement = document.getElementById('requirementsModal');
      const modal = bootstrap.Modal.getInstance(modalElement);
      modal.hide();
      this.calculateTotals();
    },
    addProductToOrder(product, variant) {
      const existingProductIndex = this.selectedProducts.findIndex(
        item => item.name == product.name && item.variant == variant.name
      );
  
      if (existingProductIndex !== -1) {
        this.selectedProducts[existingProductIndex].quantity++;
      } else {
        this.selectedProducts.push({
          product_id: product.id,
          variant_id: variant.id,
          name: product.name,
          category_id: product.category_id,
          quantity: 1,
          variant: variant.name,
          price: this.getVariantPrice(variant),
          originalPrice: variant.price,
          requirements: [],
        });
      }

      this.calculateTotals();
    },
    getVariantPrice(variant) {
      const paymentMethodPrice = variant.prices.find(price => price.payment_method_id == this.selectedPaymentMethod.id);
      if (paymentMethodPrice) {
        return this.calculateDiscountedPrice(paymentMethodPrice.price, variant.discount);
      }
      return variant.price;
    },
    calculateDiscountedPrice(price, discount) {
      if (discount && discount.is_active) {
        console.log(price);
        return price * (1 - discount.discount_percentage / 100);
      }
      return price;
    },
    updatePricesByPaymentMethod() {
      this.selectedProducts = this.selectedProducts.map(product => {
        const price = this.getVariantPrice(product);
        return {
          ...product,
          price
        };
      });
      this.calculateTotals();
    },
    removeProduct(index) {
      this.selectedProducts.splice(index, 1);
      this.calculateTotals();
    },
    applyCoupon() {
      this.$instance.post('/apply-coupon', { code: this.couponCode }).then(response => {
        if (response.data.coupon && response.data.coupon.discount_percentage) {
          this.coupon = response.data.coupon;
          this.couponSuccess = true;
          this.discountPercentage = response.data.coupon.discount_percentage;
          this.calculateDiscountedTotal();
          this.couponMessage = response.data.message || 'تم تطبيق الكوبون بنجاح.';
          this.$toastr.success(this.couponMessage);
        } else {
          this.couponSuccess = false;
          this.couponMessage = response.data.message || 'كود الكوبون غير صالح.';
          this.$toastr.error(this.couponMessage);
        }
      }).catch(() => {
        this.couponSuccess = false;
        this.couponMessage = 'حدث خطأ ما. يرجى المحاولة مرة أخرى.';
        this.$toastr.error(this.couponMessage);
      });
    },
    calculateTotals() {
      this.totalAmount = this.selectedProducts.reduce((total, product) => total + product.price * product.quantity, 0);
      this.calculateDiscountedTotal();
    },
    calculateDiscountedTotal() {
      this.discountedTotal = this.selectedProducts.reduce((total, product) => total + product.price * product.quantity, 0);
      this.discountedTotal = parseFloat(this.discountedTotal.toFixed(2)); // تقريب الاجمالي بعد الخصم
    },
    validateForm() {
      if (!this.selectedCustomer) {
        this.$toastr.error('يرجى اختيار الزبون.');
        return false;
      }
      if (!this.selectedPaymentMethod) {
        this.$toastr.error('يرجى اختيار طريقة الدفع.');
        return false;
      }
      if (this.selectedProducts.length == 0) {
        this.$toastr.error('يرجى إضافة منتجات إلى الطلب.');
        return false;
      }
      return true;
    },
    submitOrder() {
      if (!this.validateForm()) {
        return;
      }
      const formData = new FormData();
      formData.append('customer_id', this.selectedCustomer.id);
      formData.append('payment_method_id', this.selectedPaymentMethod.id);
      formData.append('order_date', this.orderDate);
      formData.append('order_notes', this.orderNotes);
      if (this.coupon) {
        formData.append('coupon_id', this.coupon.id);
      }
      formData.append('total_amount', this.totalAmount);
      formData.append('discounted_total', this.discountedTotal);

      this.selectedProducts.forEach((product, index) => {
        console.log(product);
        formData.append(`products[${index}][product_id]`, product.product_id);
        formData.append(`products[${index}][variant_id]`, product.variant_id);
        formData.append(`products[${index}][name]`, product.name);
        formData.append(`products[${index}][quantity]`, product.quantity);
        formData.append(`products[${index}][variant]`, product.variant);
        formData.append(`products[${index}][price]`, product.price);
        formData.append(`products[${index}][category_id]`, product.category_id);
        product.requirements.forEach((req, reqIndex) => {
          formData.append(`products[${index}][requirements][${reqIndex}][name]`, req.name);
          formData.append(`products[${index}][requirements][${reqIndex}][value]`, req.value);
        });
      });

      const url = this.orderId ? `/orders/${this.orderId}` : '/orders';
      const method = this.orderId ? 'put' : 'post';

      this.$instance[method](url, formData).then(response => {
        this.$toastr.success('تم إرسال الطلب بنجاح.');
        this.resetForm();
        setTimeout(() => {
          window.location.href = '/orders';
        }, 600);
      }).catch(error => {
        this.$toastr.error('حدث خطأ أثناء إرسال الطلب.');
      });
    },
    resetForm() {
      this.selectedCustomer = null;
      this.selectedPaymentMethod = null;
      this.selectedProducts = [];
      this.orderDate = new Date().toISOString().substr(0, 10);
      this.orderNotes = '';
      this.couponCode = '';
      this.couponMessage = '';
      this.couponSuccess = false;
      this.discountPercentage = 0;
      this.totalAmount = 0;
      this.discountedTotal = 0;
    },
    init() {
      this.fetchProducts();
      this.orderDate = new Date().toISOString().substr(0, 10);
    },
  },
  mounted() {
    this.init();
  },
};
</script>

<style scoped>
.loader-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
.loader {
  border: 16px solid #f3f3f3;
  border-top: 16px solid #3498db;
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.nav-link {
  color: #ffffff !important;
}
.nav-link.active {
  background-color: #ffffff !important;
  color: #007bff !important;
}
</style>
