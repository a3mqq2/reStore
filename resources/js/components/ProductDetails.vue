<template>
  <div>
    <div v-if="isLoading" class="loader"></div>
    <div v-if="!isLoading" class="container mt-5">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <div class="card">
            <div class="position-sticky sticky-top m-3">
              <div class="single-product-thumbnail axil-product">
                <div class="thumbnail">
                  <img :src="productImage" alt="Product Images" />
                  <div v-if="product.discount && product.discount.discount_percentage" class="discount-badge">
                    -{{ product.discount.discount_percentage }}%
                  </div>
                  <div class="price-amount mt-3">
                    <span id="product-price">
                      <span v-if="discountedPrice !== displayedPrice">
                        <del>{{ displayedPrice }} نقطة</del>
                        {{ discountedPrice }} نقطة
                      </span>
                      <span v-else>
                        {{ displayedPrice }} نقطة
                      </span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card mt-3 p-4">
            <div class="position-sticky sticky-top m-3">
              <h5>تفاصيل المنتج :</h5>
              <p class="text-right" style="color: #fff!important;font-size:20px !important;">{{ product.description }}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="container">
            <h3 class="m-0">{{ product.name }}</h3>
            <p v-if="product.category_id == 1" class="m-0 text-right text-primary" style="font-weight:bold;margin-bottom:03px !important">
              ملاحظة : هذا المنتج يتطلب بيانات لشرائه ، اضغط على اضافة للسلة لاكمال البيانات.
            </p>


            <p class="text-danger font-weight-bold" style="font-weight: bold;" >في حال لديك اي استفسارات يمكنكم التواصل معنا او رؤية  <a href="https://re-store.com.ly/faq">الأسئلة الشائعة</a> </p>

            <div v-if="product.requirements.length" class="requirements mt-4">
              <h5>متطلبات المنتج</h5>
              <ul>
                <li v-for="requirement in product.requirements" :key="requirement.id" class="mb-2">
                  <label class="h3" style="font-size: 20px;">{{ requirement.name }}</label>
                  <div v-if="requirement.type == 'text'">
                    <input type="text" style="font-size: 20px;" class="form-control border" :name="'requirement_' + requirement.id" v-model="form.requirements[requirement.id]" />
                  </div>
                  <div v-else>
                    <select class="form-control" :name="'requirement_' + requirement.id" v-model="form.requirements[requirement.id]">
                      <option v-for="item in requirement.list_items" :key="item.id" :value="item.item">
                        {{ item.item }}
                      </option>
                    </select>
                  </div>
                </li>
              </ul>
            </div>

            <div v-if="username" class="user-card card bg-primary text-light p-4">
                <span><i class="fa fa-user"></i> اسم المستخدم : {{ username }}</span>
            </div>

            <!-- <div class="variants-table mt-4">
              <h5>حدد طريقة دفع</h5>
              <div class="d-flex flex-wrap">
                <button
                  v-for="payment in payment_methods"
                  :key="payment.id"
                  :class="['btn', 'btn-outline-secondary', 'm-1', 'variant-option', { active: selectedPaymentMethod == payment }]"
                  @click="updatePaymentMethod(payment)"
                >
                  {{ payment.name }} <img :src="'/images/payment_methods/' + payment.image" class="payment-icon" width="40">
                </button>
              </div>
            </div> -->
            <div class="variants-table mt-4">
              <h5>الفئات</h5>
              <div class="d-flex flex-wrap">
                <button
                  v-for="variant in product.variants"
                  :key="variant.id"
                  :class="['btn', 'btn-outline-secondary', 'm-1', 'variant-option', { active: selectedVariant == variant.id }]"
                  @click="updatePrice(variant)"
                >
                  <input type="radio" name="variant" :value="variant.id" hidden />
                  {{ variant.name }} <br /> {{ getPriceForPaymentMethod(variant) }} نقطة
                </button>
              </div>
            </div>
            <div class="quantity-wrapper mt-3 d-flex justify-content-center align-items-center">
              <button class="btn btn-outline-secondary quantity-btn" @click="decreaseQuantity">-</button>
              <input type="text" class="text-center" style="color: #000 !important; text-align: center !important; max-width: 120px; margin: 0 20px;" v-model="quantity" readonly />
              <button class="btn btn-outline-secondary quantity-btn" @click="increaseQuantity">+</button>
            </div>
            <div class="product-action-wrapper d-flex align-items-center mt-4">
              <button class="axil-btn text-white mb-4 btn-bg-primary w-100" style="color: #fff!important;" @click="addToCart">شراء المنتج</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import debounce from 'lodash/debounce';
import _ from 'lodash';

export default {
  props: {
    productId: String,
    paymentMethodId: String,
    customerId: { type: String, default: "" },
    sessionId: { required: true },
  },
  data() {
    return {
      product: null,
      selectedVariant: null,
      displayedPrice: null,
      discountedPrice: null,
      currency: '',
      quantity: 1,
      isLoading: true,
      payment_methods: [],
      selectedPaymentMethod: null,
      form: { requirements: {} },
      username:'',
    };
  },
  computed: {
    productImage() {
      return `/storage/${this.product.image}`;
    },
  },
  watch: {
    form: {
      handler: _.debounce(async function () {
        if (this.checkRequirementsCompleted() && this.product.smileone_name) {
          await this.fetchUserData();
        }
      }, 700),
      deep: true,
    },
  },
  methods: {
    async fetchProduct() {
      try {
        const response = await this.$instance.get(`/products/${this.productId}`);
        this.product = response.data;
        this.product.requirements.forEach(req => this.$set(this.form.requirements, req.id, ''));
        this.product.variants = this.product.variants.filter(v => v.is_active == 1);

        // Select variant with minimum price
        if (this.product.variants.length) {
          const cheapestVariant = this.product.variants.reduce((min, variant) => {
            const minPrice = this.getPriceForPaymentMethod(min);
            const variantPrice = this.getPriceForPaymentMethod(variant);
            return variantPrice < minPrice ? variant : min;
          }, this.product.variants[0]);

          this.updatePrice(cheapestVariant);
        }
      } catch (error) { }
    },
    async fetchPaymentMethods() {
      try {
        const response = await this.$instance.get(`/payment-methods`);
        this.payment_methods = response.data;
        this.selectedPaymentMethod = response.data[0];
        this.currency = response.data[0].currency;
      } catch (error) { }
    },
    async fetchUserData() {
        let formData = new FormData();
        formData.append('requirements', Object.values(this.form.requirements));
        formData.append('product_id', this.product.id);
          this.$instance.post('/smileone/role-query',formData).then(r => {
            if(r.data.status != 200) {
              this.$toastr.error('يجب التحقق من جميع المتطلبات انها مكتوبه بشكل صحيح');
            } else {
              this.username = r.data.username;
            }
          }).catch(e => {
            console.log(e);
          });
    },
    checkRequirementsCompleted() {  
      return Object.values(this.form.requirements).every(value => value);
    },
    getPriceForPaymentMethod(variant) {
      // Use calculated_price which automatically uses SmileOne or MooGold based on availability
      if (variant.calculated_price) {
        return variant.calculated_price;
      }
      // Fallback to regular prices if calculated_price is not available
      if (!variant.prices) return 0;
      const priceObj = variant.prices.find(price => price.payment_method_id == this.selectedPaymentMethod.id);
      return priceObj ? priceObj.price : (variant.prices.length ? variant.prices[0].price : 0);
    },
    updatePaymentMethod(payment) {
      this.selectedPaymentMethod = payment;
      this.currency = payment.currency;
    },
    updatePrice(variant) {
      this.selectedVariant = variant.id;
      this.displayedPrice = this.getPriceForPaymentMethod(variant);
      this.discountedPrice = this.getDiscountedPrice(this.displayedPrice);
    },
    getDiscountedPrice(price) {
      if (this.product.discount?.discount_percentage) {
        const discount = price * (this.product.discount.discount_percentage / 100);
        return (price - discount).toFixed(2);
      }
      return price;
    },
    increaseQuantity() {
      this.quantity++;
    },
    decreaseQuantity() {
      if (this.quantity > 1) this.quantity--;
    },
    async addToCart() {
      try {
        // Check provider balance before adding to cart (for SmileOne/Moogold products)
        if (this.product.smileone_name || this.product.moogold_id) {
          const balanceCheck = await this.$instance.post('/check-provider-balance', {
            product_id: this.productId,
          });

          if (!balanceCheck.data.available) {
            this.$toastr.error(balanceCheck.data.message);
            return;
          }
        }

        await this.$instance.post('/cart/add', {
          customer_id: this.customerId,
          product_id: this.productId,
          session_id: this.sessionId,
          payment_method_id: this.selectedPaymentMethod.id,
          quantity: this.quantity,
          variant_id: this.selectedVariant,
          requirements: this.form.requirements,
        });
        this.$toastr.success('تمت إضافة المنتج إلى السلة بنجاح');
      } catch (error) {
        this.$toastr.error('يجب تحديد كافة خيارات المنتج');
      }
    },
  },
  async mounted() {
    await this.fetchPaymentMethods();
    await this.fetchProduct();
    this.isLoading = false;
  },
};
</script>


<style scoped>
/* Dark Mode Variables */
:root {
  --dark-bg: #0a0e1a;
  --dark-bg-secondary: #151923;
  --dark-bg-card: #1a1f2e;
  --dark-border: #2a2f3e;
  --gold-primary: #c9a636;
  --gold-secondary: #b89530;
  --text-primary: #e4e4e7;
  --text-secondary: #a1a1aa;
  --text-muted: #71717a;
}

/* Loader styles */
.loader {
  border: 8px solid var(--dark-border);
  border-radius: 50%;
  border-top: 8px solid var(--gold-primary);
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Card Styles */
.card {
  background-color: var(--dark-bg-card) !important;
  border: 1px solid var(--dark-border) !important;
  border-radius: 12px !important;
  color: var(--text-primary) !important;
}

/* Product Image Section */
.single-product-thumbnail {
  position: relative;
}

.thumbnail img {
  border-radius: 12px;
  border: 1px solid var(--dark-border);
}

/* Discount Badge styles */
.discount-badge {
  position: absolute;
  top: 15px;
  left: 15px;
  background-color: #ef4444;
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 1.1rem;
  font-weight: 800;
  z-index: 2;
}

/* Price Amount */
.price-amount {
  text-align: center;
  padding: 25px;
  background-color: var(--dark-bg-secondary);
  border-radius: 12px;
  border: 1px solid var(--dark-border);
  margin-top: 20px;
}

.price-amount span {
  font-size: 2rem;
  color: var(--gold-primary);
  font-weight: 900;
}

.price-amount del {
  color: var(--text-muted);
  font-size: 1.5rem;
  margin-left: 15px;
}

/* Product Title */
h3 {
  color: var(--text-primary) !important;
  font-size: 2.5rem !important;
  font-weight: 900 !important;
  margin-bottom: 20px !important;
}

h5 {
  color: var(--text-primary) !important;
  font-size: 1.8rem !important;
  font-weight: 800 !important;
  margin-bottom: 25px !important;
  display: flex;
  align-items: center;
  gap: 12px;
}

h5::before {
  content: '';
  width: 5px;
  height: 30px;
  background-color: var(--gold-primary);
  border-radius: 3px;
}

p {
  color: var(--text-secondary) !important;
  line-height: 1.8 !important;
  font-size: 1.1rem !important;
}

/* User Card */
.user-card {
  background-color: var(--gold-primary) !important;
  color: var(--dark-bg) !important;
  border-radius: 12px !important;
  padding: 20px !important;
  font-size: 1.2rem !important;
  font-weight: 800 !important;
  margin-top: 20px !important;
  border: none !important;
}

/* Requirements Section */
.requirements {
  background-color: var(--dark-bg-card);
  border: 1px solid var(--dark-border);
  border-radius: 12px;
  padding: 25px;
  margin-bottom: 25px;
}

.requirements ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.requirements li {
  margin-bottom: 20px;
}

.requirements label {
  color: var(--text-primary);
  font-weight: 700;
  font-size: 1.1rem;
  margin-bottom: 10px;
  display: block;
}

.requirements input,
.requirements select {
  width: 100%;
  padding: 14px 16px;
  background-color: var(--dark-bg-secondary);
  border: 1px solid var(--dark-border);
  border-radius: 8px;
  color: var(--text-primary);
  font-size: 1rem;
}

.requirements input:focus,
.requirements select:focus {
  outline: none;
  border-color: var(--gold-primary);
  box-shadow: 0 0 0 3px rgba(201, 166, 54, 0.1);
}

/* Variants Table Section - LUXURIOUS & BIGGER */
.variants-table {
  background-color: var(--dark-bg-card);
  border: 1px solid var(--dark-border);
  border-radius: 12px;
  padding: 30px;
  margin-bottom: 30px;
}

.variants-table h5 {
  margin-bottom: 25px;
}

/* Variant Options - BIGGER & MORE LUXURIOUS */
.variant-option {
  width: 100% !important;
  text-align: center;
  padding: 25px 35px !important;
  margin: 10px 0 !important;
  transition: all 0.3s ease;
  border: 2px solid var(--dark-border) !important;
  background-color: var(--dark-bg-secondary) !important;
  color: var(--text-primary) !important;
  border-radius: 12px !important;
  font-size: 1.4rem !important;
  font-weight: 800 !important;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.variant-option:hover {
  background-color: var(--dark-bg) !important;
  border-color: var(--gold-primary) !important;
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(201, 166, 54, 0.15);
}

.variant-option.active {
  background-color: var(--gold-primary) !important;
  color: var(--dark-bg) !important;
  border-color: var(--gold-primary) !important;
  box-shadow: 0 8px 24px rgba(201, 166, 54, 0.3);
}

/* Quantity Wrapper */
.quantity-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  padding: 30px;
  background-color: var(--dark-bg-card);
  border: 1px solid var(--dark-border);
  border-radius: 12px;
  margin-bottom: 25px;
}

.quantity-btn {
  width: 55px !important;
  height: 55px !important;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: var(--dark-bg-secondary) !important;
  border: 2px solid var(--dark-border) !important;
  color: var(--gold-primary) !important;
  border-radius: 10px !important;
  font-size: 1.8rem !important;
  font-weight: 800 !important;
  transition: all 0.3s ease;
}

.quantity-btn:hover {
  background-color: var(--gold-primary) !important;
  color: var(--dark-bg) !important;
  border-color: var(--gold-primary) !important;
}

.quantity-input {
  width: 100px !important;
  height: 55px !important;
  text-align: center;
  font-size: 1.5rem !important;
  font-weight: 800 !important;
  margin: 0 15px;
  border: 2px solid var(--dark-border) !important;
  background-color: var(--dark-bg-secondary) !important;
  color: var(--text-primary) !important;
  border-radius: 10px !important;
}

/* Purchase Button */
.product-action-wrapper {
  margin-top: 25px;
}

.btn-bg-primary {
  background-color: var(--gold-primary) !important;
  color: var(--dark-bg) !important;
  padding: 25px 40px !important;
  border-radius: 12px !important;
  font-size: 1.8rem !important;
  font-weight: 800 !important;
  border: none !important;
  transition: all 0.3s ease;
  box-shadow: 0 8px 24px rgba(201, 166, 54, 0.3);
  width: 100% !important;
}

.btn-bg-primary:hover {
  background-color: var(--gold-secondary) !important;
  color: var(--dark-bg) !important;
  transform: translateY(-2px);
  box-shadow: 0 12px 32px rgba(201, 166, 54, 0.5);
}

/* Form Controls */
.form-control {
  background-color: var(--dark-bg-secondary) !important;
  border: 1px solid var(--dark-border) !important;
  color: var(--text-primary) !important;
  border-radius: 8px !important;
}

.form-control:focus {
  background-color: var(--dark-bg-card) !important;
  border-color: var(--gold-primary) !important;
  color: var(--text-primary) !important;
  box-shadow: 0 0 0 3px rgba(201, 166, 54, 0.1);
}

.form-label {
  font-weight: 700;
  margin-bottom: 10px;
  color: var(--text-primary);
  font-size: 1.1rem;
}

/* Alert Styles */
.text-primary {
  color: var(--gold-primary) !important;
}

.text-danger {
  color: #ef4444 !important;
}

/* Links */
a {
  color: var(--gold-primary);
  text-decoration: none;
  font-weight: 700;
}

a:hover {
  color: var(--gold-secondary);
  text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
  h3 {
    font-size: 1.8rem !important;
  }

  h5 {
    font-size: 1.4rem !important;
  }

  .variant-option {
    padding: 20px 25px !important;
    font-size: 1.2rem !important;
  }

  .quantity-btn {
    width: 45px !important;
    height: 45px !important;
    font-size: 1.5rem !important;
  }

  .quantity-input {
    width: 80px !important;
    height: 45px !important;
    font-size: 1.2rem !important;
  }

  .btn-bg-primary {
    font-size: 1.4rem !important;
    padding: 20px 30px !important;
  }

  .price-amount span {
    font-size: 1.5rem;
  }

  .cashback-badge,
  .discount-badge {
    padding: 8px 14px;
    font-size: 0.9rem;
  }
}
</style>
