<template>
  <div class="container mt-5">
    <div v-if="loading" class="loader-overlay">
      <div class="loader"></div>
      <div class="loader-text">يرجى الانتظار...</div>
    </div>

    <!-- Success Message and Rating -->
    <div v-else-if="checkout == 3" class="success-overlay">
      <div class="success-icon">&#10004;</div>
      <h2 class="success-message">تم الطلب بنجاح</h2>
      <p class="success-text mb-0">شكراً لتسوقك معنا. سوف نقوم بمعالجة طلبك قريباً.</p>

      <!-- Rating Section -->
      <div class="rating-section">
        <h3 class="text-center mb-1">قيم تجربتك مع عملية الشراء</h3>
        <p class="text-center mb-1">نود أن نسمع رأيك حول تجربتك في التسوق معنا.</p>

        <!-- Rating Stars -->
        <div class="rating-stars text-center mt-3">
          <span v-for="star in 5" :key="star" @click="setRating(star)" class="star" :class="{ active: rating >= star }">&#9733;</span>
        </div>

        <!-- Error message for missing rating -->
        <p v-if="errorMessage" class="text-danger text-center">{{ errorMessage }}</p>

        <!-- Notes Section -->
        <div class="mt-4">
          <textarea v-model="notes" class="form-control" rows="4" placeholder="أضف ملاحظاتك هنا..."></textarea>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-3">
          <button class="btn btn-primary" @click="submitRating">إرسال التقييم</button>
          <a href="/" class="btn btn-secondary mt-2 btn-block text-light">انتقال للصفحة الرئيسية</a>
        </div>
      </div>
    </div>

    <!-- Cart and Checkout -->
    <div v-else>
      <!-- Cart Items -->
      <div v-if="!checkout" class="row p-3">
        <div class="col-md-12">
          <div class="axil-product-cart-area axil-section-gap">
            <div class="container">
              <div class="axil-product-cart-wrap">
                <div class="product-table-heading">
                  <h4 class="title">سلة المشتريات</h4>
                  <a href="#" @click.prevent="clearCart" class="cart-clear">مسح سلة المشتريات</a>
                </div>
                <div class="cart-items">
                  <div v-for="item in cartItems" :key="item.id" class="cart-item p-4 card mb-3">
                    <div class="card-body d-flex p-3">
                      <div class="row">
                        <div class="col-4 position-relative">
                          <a :href="`/website/show-product/${item.product_id}`">
                            <img :src="`/storage/${item.product.image}`" :alt="item.product.name" class="img-fluid rounded">
                          </a>
                        </div>
                        <div class="col-8 d-flex align-items-middle justify-content-center" style="flex-direction: column;">
                          <h5 class="card-title" style="font-size: 20px; font-weight: bold;">
                            <a :href="`/website/show-product/${item.product_id}`">{{ item.product.name }}</a>
                          </h5>
                          <p class="card-text" style="font-size: 16px; font-weight: bold;">
                            الفئة: {{ item.variant ? item.variant.name : 'N/A' }}
                          </p>
                          <p class="card-text" style="font-size: 16px; font-weight: bold;">
                            السعر:
                            <span v-if="item.variant">
                              {{ getItemPrice(item) }} نقطة
                            </span>
                            <span v-else>
                              {{ item.product.prices.find(p => p.payment_method_id == selectedPaymentMethod).price }} نقطة
                            </span>
                          </p>
                          <p class="card-text" style="font-size: 16px; font-weight: bold;">
                            الكمية: {{ item.quantity }}
                          </p>
                          <p v-if="item.couponApplied" class="text-success" style="font-size: 16px; font-weight: bold;">
                            تم تطبيق الكوبون على هذا المنتج
                          </p>
                          <button @click.prevent="removeItem(item.id)" class="btn btn-danger btn-sm">إزالة</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="cartItems.length == 0" class="text-center">سلة المشتريات فارغة.</div>
                <div class="cart-update-btn-area">
                  <div class="input-group product-cupon">
                    <input placeholder="أدخل رمز القسيمة" v-model="couponCode" type="text" class="form-control">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-outline-secondary" @click="applyCoupon">تطبيق</button>
                    </div>
                  </div>
                  <div class="update-btn mt-3">
                    <button @click.prevent="updateCart" class="btn btn-outline-primary">تحديث السلة</button>
                  </div>
                </div>
                <div class="row mt-4" v-if="cartItems.length">
                  <div class="col-12">
                    <div class="axil-order-summery">
                      <h5 class="title mb-3">ملخص الطلب</h5>
                      <table class="table">
                        <tbody>
                          <tr class="order-total">
                            <td>الإجمالي قبل التخفيض</td>
                            <td class="order-total-amount">{{ totalAmount }} نقطة</td>
                          </tr>
                          <tr v-if="discountValue > 0">
                            <td>إجمالي التخفيضات</td>
                            <td class="order-total-amount">-{{ discountValue }} نقطة</td>
                          </tr>
                          <tr>
                            <td>الإجمالي بعد التخفيض</td>
                            <td class="order-total-amount">{{ discountedTotal }} نقطة</td>
                          </tr>
                        </tbody>
                      </table>
                      <button v-if="customerId" @click="checkout = 1" class="btn btn-primary btn-block">الانتقال إلى الدفع</button>
                    </div>
                      <div v-if="!customerId" class=" mt-4">
                      <div v-if="!customerId" class="alert alert-dismissible fade show" role="alert" style="background: linear-gradient(135deg, #fbd786, #f7797d); color: #333; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); padding: 15px;">
                        <div class="d-flex align-items-center">
                          <i class="fas fa-exclamation-circle mr-4" style="font-size: 24px; color: #fff; margin-left: 10px;"></i>
                          <div>
                            <strong class="text-white">تنبيه!</strong>
                            <p class="mb-0 text-white">اذا كان لديك حساب مسبقًا يرجى <a href="/login" class="alert-link" style="color: #fff; text-decoration: underline;">تسجيل الدخول</a> قبل الشراء. ان لم يكن لديك حساب من قبل يمكنك <a href="/register" class="alert-link" style="color: #fff; text-decoration: underline;">إنشاء حساب جديد</a>.</p>
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
      </div> <!-- End of Cart Items -->

      <!-- Checkout -->
      <div v-else-if="checkout" class="row p-3">
        <div class="col-md-12">
          <h3 class="text-right">حدد طريقة دفع:</h3>
          <div class="container">
            <div class="col-12 mb-5">
              <div class="axil-order-summery p-4 mt--80">
                <p class="text-center m-0 font-20" style="font-weight: bold;">
                  القيمة المطلوبة: {{ discountedTotal }} نقطة
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="container">
            <div class="card">
              <div class="card-body p-4">
                <div class="payment-methods mb-3" style="margin-bottom: 30px!important;">
                  <div
                    v-for="paymentMethod in paymentMethods"
                    :key="paymentMethod.id"
                    class="payment method p-3 my-4"
                    :class="{ 'active': selectedPaymentMethod == paymentMethod.id }"
                    @click="selectPaymentMethod(paymentMethod.id)"
                    :disabled="paymentMethod.id == 1 && !customerId"
                  >
                    <img :src="'/images/payment_methods/' + paymentMethod.image" class="payment-icon">
                    <span v-if="paymentMethod.id == 1">
                      <span v-if="!customerId">
                      يمكنك <a href="/register" class="text-primary" style="font-weight: bold;">انشاء حساب جديد </a> او <a href="/login" class="text-primary" style="font-weight: bold;">تسجيل الدخول</a> للاستفادة من خدمة الدفع بالمحفظة
                      </span>
                      <span v-else>
                        محفظتي <br> الرصيد الحالي: {{ myBalance }}
                      </span>
                    </span>
                    
                    <span v-else>
                      {{ paymentMethod.name }} <br> يجب التحويل على رقم الهاتف \ ID: {{ paymentMethod.my_contact }}
                    </span>
                  </div>
                </div>
                <div
                  v-if="selectedPaymentMethod == 1 && myBalance >= parseFloat(discountedTotal)"
                  class="alert alert-info mt-3"
                >
                  سيتم الخصم القيمة بشكل مباشر
                </div>
                <div
                  v-if="selectedPaymentMethod == 1 && myBalance < parseFloat(discountedTotal)"
                  class="alert alert-danger mt-3"
                >
                  الرصيد المتاح في المحفظة لا يكفي لإتمام الطلب.
                </div>


                <!-- guest user name -->
                <div v-if="!customerId" class="form-group mt-3">
                  <label for="">اسمك كاملاََ</label>
                  <input type="text" class="form-control" v-model="name" />
                </div>

                <div v-if="!customerId" class="form-group">
                    <label for="">رقم هاتفك (واتساب) </label>
                    <input type="tel" class="form-control" v-model="phone" placeholder="مثال: +966501234567" />
                </div>

                <div v-if="selectedPaymentMethod !== 1" class="mt-3">
                  <label> يرجى كتابة تاريخ التحويل والرقم الذي تم التحويل منه أو أرقام الكروت  </label>
                  <textarea v-model="notes" class="form-control" rows="7"></textarea>
                </div>

                <div class="btn-group">
                  <button
                    @click="submitOrder"
                    class="btn btn-primary mt-3"
                    :disabled="(selectedPaymentMethod == 1 && myBalance < parseFloat(discountedTotal)) || (selectedPaymentMethod != 1 && !notes) || (selectedPaymentMethod == 1 && !customerId) "
                  >
                    تأكيد الطلب
                  </button>
                  <button @click="checkout = false" class="btn btn-secondary mt-3">العودة</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- End of Checkout -->
    </div>
  </div>
</template>

<script>
export default {
  props: {
    customerId: {
      type: String,
      required: true
    },
    session: {
      type: String,
      required: true
    },
    payment: {
      type: Number,
      required: true,
    }
  },
  data() {
    return {
      cartItems: [],
      couponCode: '',
      totalAmount: 0,
      discountedTotal: 0,
      currency: null,
      loading: true,
      coupon: null,
      couponSuccess: false,
      couponMessage: '',
      checkout: false,
      paymentMethods: [],
      paymentMethod: 1,
      myBalance: 0,
      name:'',
      phone:'',
      selectedPaymentMethod: 1,
      notes: null,
      discountValue: 0,
      rating: 0,
      fileName: '', // لحفظ اسم الملف المرفوع
      file: null, // لحفظ الملف نفسه
    };
  },
  methods: {
    setRating(star) {
      this.rating = star;
    },

    async submitRating() {
      if (!this.rating) {
        this.$toastr.error("يرجى تحديد تقييم قبل الإرسال.");
        return;
      }

      try {
        const response = await this.$instance.post("/submit-rating", {
          rating: this.rating,
          notes: this.notes,
          customer_id: this.customerId,
        });

        if (response.data.success) {
          this.$toastr.success("تم إرسال تقييمك بنجاح. شكراً لك!");
        } else {
          this.$toastr.error("حدث خطأ أثناء إرسال التقييم.");
        }
      } catch (error) {
        this.$toastr.error("حدث خطأ أثناء إرسال التقييم.");
      }
    },

    fetchCartItems() {
      this.loading = true;
      this.$instance.get('/cart-items', { params: { paymentMethod: this.payment, customer_id: this.customerId, session_id:this.session } })
        .then(response => {
          this.cartItems = response.data.items;
          this.currency = response.data.currency;
          this.paymentMethods = response.data.paymentMethods;
          this.myBalance = parseFloat(response.data.myBalance);
          this.calculateDiscountedTotal();
          this.loading = false;
        }).then(() => {
          if(!this.selectPaymentMethod) {
              this.selectedPaymentMethod = 1;
          }
        })
        .catch(error => {
          console.error("حدث خطأ أثناء جلب عناصر السلة!", error);
          this.loading = false;
        });
    },
    
    clearCart() {
      this.loading = true;
      this.$instance.post('/cart/clear?session_id=' + this.session + '&customer_id=' + this.customerId)
        .then(response => {
          this.cartItems = [];
          this.totalAmount = 0;
          this.discountedTotal = 0;
          this.loading = false;
        })
        .catch(error => {
          console.error("حدث خطأ أثناء مسح السلة!", error);
          this.loading = false;
        });
    },
    
    removeItem(itemId) {
      this.loading = true;
      this.$instance.post(`/cart/remove/${itemId}`)
        .then(response => {
          this.fetchCartItems();
        })
        .catch(error => {
          console.error("حدث خطأ أثناء إزالة العنصر!", error);
          this.loading = false;
        });
    },
    
    applyCoupon() {
      this.loading = true;
      this.$instance.post('/apply-coupon', { code: this.couponCode })
        .then(response => {
          if (response.data.coupon) {
            this.coupon = response.data.coupon;

            // Check if the coupon applies to any items in the cart
            const applicableItems = this.getApplicableItems();

            if (applicableItems.length == 0) {
              this.couponSuccess = false;
              this.coupon = null; // Reset the coupon
              this.couponMessage = 'هذا الكوبون غير صالح للمنتجات الموجودة في السلة.';
              this.$toastr.error(this.couponMessage);
            } else {
              this.couponSuccess = true;
              this.couponMessage = response.data.message || 'تم تطبيق الكوبون بنجاح.';
              this.$toastr.success(this.couponMessage);
              this.calculateDiscountedTotal();
            }
          } else {
            this.couponSuccess = false;
            this.couponMessage = response.data.message || 'كود الكوبون غير صالح.';
            this.$toastr.error(this.couponMessage);
          }
          this.loading = false;
        })
        .catch(() => {
          this.couponSuccess = false;
          this.couponMessage = 'حدث خطأ ما. يرجى المحاولة مرة أخرى.';
          this.$toastr.error(this.couponMessage);
          this.loading = false;
        });
    },
    
    getApplicableItems() {
      return this.cartItems.filter(item => {
        let couponApplies = false;

        if (this.coupon.product_id && this.coupon.variant_id) {
          // Coupon specifies both product_id and variant_id
          if (item.product_id == this.coupon.product_id && item.variant_id == this.coupon.variant_id) {
            couponApplies = true;
          }
        } else if (this.coupon.product_id) {
          // Coupon specifies only product_id
          if (item.product_id == this.coupon.product_id) {
            couponApplies = true;
          }
        } else if (!this.coupon.product_id && !this.coupon.variant_id) {
          // Coupon applies to all items
          couponApplies = true;
        }

        return couponApplies;
      });
    },

    updateCart() {
      this.fetchCartItems();
    },

    // Get item price - use calculated_price which automatically uses SmileOne or MooGold
    getItemPrice(item) {
      if (item.variant) {
        // Use calculated_price if available (works for SmileOne and MooGold)
        if (item.variant.calculated_price) {
          return item.variant.calculated_price;
        }
        // Fallback to variant price
        if (item.variant.prices && item.variant.prices.length > 0) {
          const priceObj = item.variant.prices.find(p => p.payment_method_id == this.selectedPaymentMethod);
          return priceObj ? priceObj.price : item.variant.prices[0].price;
        }
      }
      // Fallback to product price
      if (item.product.prices && item.product.prices.length > 0) {
        const priceObj = item.product.prices.find(p => p.payment_method_id == this.selectedPaymentMethod);
        return priceObj ? priceObj.price : item.product.prices[0].price;
      }
      return 0;
    },

    // Get item cost - use calculated_cost which automatically uses SmileOne or MooGold
    getItemCost(item) {
      if (item.variant) {
        // Use calculated_cost if available (works for SmileOne and MooGold)
        if (item.variant.calculated_cost) {
          return item.variant.calculated_cost;
        }
      }
      return null; // No cost for products without calculated_cost
    },

    calculateDiscountedTotal() {
      let totalAmount = 0; // إجمالي قبل أي خصومات
      let totalAfterCouponDiscounts = 0; // إجمالي بعد تطبيق خصومات القسيمة
      let totalCouponDiscount = 0; // إجمالي خصم القسيمة المطبق

      // الحصول على العناصر القابلة للقسيمة
      const applicableItems = this.coupon ? this.getApplicableItems() : [];

      this.cartItems.forEach(item => {
        // Use getItemPrice method for consistent pricing
        let itemPrice = this.getItemPrice(item);
        let itemCost = this.getItemCost(item);

        // Parse the price and multiply by quantity
        let itemTotal = parseFloat(itemPrice) * parseFloat(item.quantity);

        item.price = itemPrice; // Save the price for display
        item.cost = itemCost; // Save the cost for order submission

        // Adding to the total before any discounts
        totalAmount += itemTotal;

        // Apply product discount if available
        if (item.product && item.product.discount && item.product.discount.discount_percentage) {
          let productDiscountPercentage = parseFloat(item.product.discount.discount_percentage);
          let productDiscountAmount = itemTotal * (productDiscountPercentage / 100);
          itemTotal -= productDiscountAmount;
        }

        // Initialize coupon applied flag
        item.couponApplied = false;

        // Now apply coupon discount if applicable
        let couponDiscountAmount = 0;

        if (this.coupon && applicableItems.includes(item)) {
          item.couponApplied = true; // Mark item as having coupon applied

          if (this.coupon.discount_type == 'percentage' && this.coupon.discount_percentage) {
            let couponDiscountPercentage = parseFloat(this.coupon.discount_percentage);
            couponDiscountAmount = itemTotal * (couponDiscountPercentage / 100);
          } else if (this.coupon.discount_type == 'amount' && this.coupon.discount_amount) {
            // For fixed amount discounts, distribute the discount across applicable items
            couponDiscountAmount = parseFloat(this.coupon.discount_amount) / applicableItems.length;
          }
          itemTotal -= couponDiscountAmount;
          totalCouponDiscount += couponDiscountAmount;
        }

        // Add to the total after applying coupon discounts
        totalAfterCouponDiscounts += itemTotal;
      });

      // Ensure totals aren't negative
      if (totalAfterCouponDiscounts < 0) {
        totalAfterCouponDiscounts = 0;
      }

      this.totalAmount = totalAmount.toFixed(2); // Total before discount
      this.discountedTotal = totalAfterCouponDiscounts.toFixed(2); // Total after discount
      this.discountValue = totalCouponDiscount.toFixed(2); // Total discount applied
    },

    selectPaymentMethod(paymentMethodId) {
      if (paymentMethodId == 1 && !this.customerId) {
        this.$toastr.error('يرجى تسجيل الدخول أو إنشاء حساب للاستفادة من خدمة الدفع بالمحفظة.');
        this.selectedPaymentMethod = null; // منع اختيار المحفظة
        return;
      }
      this.selectedPaymentMethod = paymentMethodId;
      this.payment = paymentMethodId;
      this.fetchCartItems();
      this.loading = true;
    },

    submitOrder() {
      if (!this.selectedPaymentMethod) {
        this.$toastr.error('يرجى تحديد طريقة الدفع');
        return;
      }

  
      const orderData = new FormData();
      orderData.append('paymentMethod', this.selectedPaymentMethod);
      orderData.append('couponCode', this.couponCode);
      orderData.append('notes', this.notes);
      orderData.append('discountedTotal', this.discountedTotal);
      orderData.append('totalAmount', this.totalAmount);
      orderData.append('cartItems', JSON.stringify(this.cartItems));
      orderData.append('session', this.session);
      orderData.append('name', this.name);
      orderData.append('phone', this.phone);
      orderData.append('customer_id', this.customerId);
      console.log(JSON.stringify(this.cartItems));

      if (this.file) {
        orderData.append('transferEvidence', this.file); // إضافة الصورة إلى FormData
      }

      this.loading = true;
      this.$instance.post('/submit-order', orderData)
        .then(response => {
          this.$toastr.success('تم تأكيد الطلب بنجاح');
          this.notes = null;
          this.clearCart();
          this.fetchCartItems();
          this.checkout = 3;
          this.loading = false;
        })
        .catch(error => {
          console.error("حدث خطأ أثناء تأكيد الطلب!", error.response.data.error);
          this.$toastr.error('حدث خطأ أثناء تأكيد الطلب');
          this.$toastr.error(error.response.data.error);
          this.loading = false;
        });
    },

    // Handle file input change
    onFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.fileName = file.name; // حفظ اسم الملف
        this.file = file; // حفظ الملف نفسه
      }
    },
  },
  computed: {
    applicableItemsCount() {
      if (!this.coupon || this.coupon.discount_type !== 'amount') {
        return 0;
      }

      return this.getApplicableItems().length;
    },
  },
  mounted() {
    this.fetchCartItems();
  }
};
</script>

<style scoped>
/* Loader styles */
.loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 1000;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}

.loader-text {
  margin-top: 20px;
  font-size: 20px;
  color: #3498db;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Payment method styles */
.payment-methods .payment.method {
  cursor: pointer;
  transition: all 0.3s ease-in-out;
  border: 2px solid #ddd;
  border-radius: 8px;
  display: flex;
  align-items: center;
  padding: 10px;
}

.payment-methods .payment.method:hover {
  background-color: #f0f0f0;
  border-color: #0056b3;
}

.payment-methods .payment.method.active {
  background-color: #0056b3;
  color: white;
  border-color: #0056b3;
  transform: scale(1.02);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.payment-icon {
  width: 30px;
  margin-right: 10px;
}

/* Success message styles */
.success-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%; 
  background-color: #fff;
  z-index: 1000;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.success-icon {
  font-size: 100px;
  color: #4CAF50;
  margin-bottom: 20px;
}

.success-message {
  font-size: 30px;
  font-weight: bold;
  color: #4CAF50;
  margin-bottom: 10px;
}

.success-text {
  font-size: 20px;
  color: #555555;
}

/* Cart styles */
.cart-items {
  margin-top: 20px;
}

.cart-item {
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
}

.img-fluid {
  max-width: 100%;
  height: auto;
}

.card-title {
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.card-text {
  font-size: 1rem;
  margin-bottom: 5px;
}

.quantity-input {
  width: 60px;
}

.badge-primary {
  margin-right: 5px;
}

@media (max-width: 768px) {
  .card-body {
    flex-direction: column;
    align-items: flex-start;
  }
  .card-title {
    margin-bottom: 5px;
  }
  .quantity-input {
    width: 100%;
    max-width: 100px;
  }
}

/* Rating Stars */
.rating-stars {
  font-size: 30px;
  color: #d3d3d3; /* Light gray for unselected stars */
  cursor: pointer;
}

.rating-stars .star {
  margin: 0 5px;
  transition: color 0.3s;
}

.rating-stars .star.active {
  color: #ffc107; /* Gold color for active stars */
}

/* Notes textarea */
textarea.form-control {
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 10px;
  resize: none;
}

textarea.form-control:focus {
  border-color: #007bff;
}

/* Error message */
.text-danger {
  color: #dc3545;
}

/* Upload Section Styles */
.upload-section {
  margin-top: 20px;
}

.upload-box {
  border: 2px dashed #007bff;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  background-color: #f9f9f9;
}

.upload-box img {
  width: 100px; /* Adjust the size as needed */
  cursor: pointer;
}

.upload-box input[type="file"] {
  display: none; /* Hide the input file element */
}
</style>
