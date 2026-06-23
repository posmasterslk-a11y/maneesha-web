<template>
  <div class="checkout-page container">
    <div class="checkout-header">
      <h1 class="luxury-title">Secure <span class="gold-gradient-text">Checkout</span></h1>
      <p>Fill out your delivery credentials and select a payment gateway to secure your order.</p>
    </div>

    <div v-if="cart.length > 0" class="checkout-layout">
      <!-- Left Forms Panel -->
      <form @submit.prevent="placeOrder" class="checkout-form-panel">
        <!-- 1. Shipping Credentials -->
        <div class="section-card glass-panel">
          <h3 class="luxury-title section-title"><i class="fa-solid fa-truck"></i> 1. Delivery Details</h3>
          
          <div class="form-grid">
            <div class="form-group span-2">
              <label class="form-label">Full Name</label>
              <input type="text" v-model="orderData.name" class="form-input" required placeholder="e.g. Priyantha Perera" />
            </div>
            
            <div class="form-group">
              <label class="form-label">Phone Number</label>
              <input type="tel" v-model="orderData.phone" class="form-input" required placeholder="e.g. 0771234567" pattern="[0-9]{10}" minlength="10" maxlength="10" title="Please enter exactly 10 digits" />
            </div>
            
            <div class="form-group">
              <label class="form-label">Email Address</label>
              <input type="email" v-model="orderData.email" class="form-input" required placeholder="e.g. priyantha@gmail.com" />
            </div>

            <div class="form-group span-2">
              <label class="form-label">Delivery Address</label>
              <textarea v-model="orderData.address" class="form-input text-area" required placeholder="Street address, City, Sri Lanka" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">District</label>
              <select v-model="orderData.district" class="form-input select-input" required>
                <option value="" disabled>Select District</option>
                <option v-for="dist in districts" :key="dist" :value="dist">{{ dist }}</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Postal Code</label>
              <input type="text" v-model="orderData.postal" class="form-input" required placeholder="e.g. 10100" />
            </div>
          </div>
        </div>

        <!-- 2. Payment Method Selector -->
        <div class="section-card glass-panel">
          <h3 class="luxury-title section-title"><i class="fa-solid fa-credit-card"></i> 2. Payment Channel</h3>
          
          <div class="payment-methods-grid">
            <!-- PayHere is temporarily disabled until approved
            <label :class="['pay-method-card', { active: orderData.paymentMethod === 'payhere' }]">
              <input type="radio" v-model="orderData.paymentMethod" value="payhere" class="hidden-radio" />
              <div class="pay-card-content">
                <i class="fa-solid fa-credit-card pay-icon"></i>
                <div>
                  <h5>PayHere Gateway</h5>
                  <p>Visa, MasterCard, Genie, Frimi, eZCash</p>
                </div>
              </div>
            </label>
            -->


            <label :class="['pay-method-card', { active: orderData.paymentMethod === 'bank_deposit' }]">
              <input type="radio" v-model="orderData.paymentMethod" value="bank_deposit" class="hidden-radio" />
              <div class="pay-card-content">
                <i class="fa-solid fa-building-columns pay-icon"></i>
                <div>
                  <h5>Direct Bank Deposit</h5>
                  <p>Transfer manually and upload payment slip</p>
                </div>
              </div>
            </label>
          </div>

          <!-- Bank details panel + Slip Upload Input (Visible only if 'bank_deposit' is chosen) -->
          <div v-if="orderData.paymentMethod === 'bank_deposit'" class="bank-details-panel animate-fade-up">
            <h5 style="text-align: center; margin-bottom: 20px; font-size: 1.2rem;">Bank Accounts</h5>
            <div class="bank-account-list" style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
              <div v-if="bankAccounts.length === 0" class="w-full text-center py-4">Loading bank accounts...</div>
              <div class="bank-card" v-for="bank in bankAccounts" :key="bank.id" style="flex: 1; min-width: 200px; padding: 15px; border: 1px solid var(--primary-gold-light); border-radius: 8px; background: rgba(212,175,55,0.02);">
                <div class="bank-row" style="margin-bottom: 10px;">
                  <strong style="font-size: 1.1em; color: var(--primary-gold);">{{ bank.bank_name }}</strong>
                </div>
                <div class="bank-row">
                  <strong>Account Number:</strong> {{ bank.account_number }}
                </div>
                <div class="bank-row">
                  <strong>Account Name:</strong> {{ bank.account_name }}
                </div>
                <div class="bank-row">
                  <strong>Branch:</strong> {{ bank.branch }}
                </div>
              </div>
            </div>

            <div class="form-group mt-4">
              <label class="form-label">Upload Transfer Receipt Slip (JPG/PNG/PDF)</label>
              <div class="file-upload-zone">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>{{ slipFileName ? slipFileName : 'Click to select slip receipt' }}</span>
                <input type="file" @change="handleSlipUpload" accept="image/*,application/pdf" class="file-input-hidden" required />
              </div>
              
              <!-- Slip Preview overlay -->
              <div v-if="slipPreviewUrl" class="slip-preview-box" style="display:flex; justify-content:center; align-items:center; min-height: 100px;">
                <img v-if="!slipPreviewUrl.startsWith('data:application/pdf')" :src="slipPreviewUrl" alt="Transfer Slip Preview" />
                <div v-else style="text-align: center; color: var(--primary-gold); padding: 20px;">
                  <i class="fa-solid fa-file-pdf" style="font-size: 3rem; margin-bottom: 10px;"></i>
                  <div style="font-weight: bold;">PDF Document Selected</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <button type="submit" :disabled="isPlacing" class="btn-premium btn-gold btn-block checkout-submit-btn">
          <span v-if="isPlacing"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Processing Secure Checkout...</span>
          <span v-else>Confirm & Stitch My Order <i class="fa-solid fa-circle-check ml-2"></i></span>
        </button>
      </form>

      <!-- Right Summary Panel -->
      <div class="checkout-summary-panel glass-panel">
        <h3 class="luxury-title summary-title">Cart Verification</h3>
        
        <div class="summary-items">
          <div v-for="(item, idx) in cart" :key="idx" class="summary-item-row" style="display: flex; justify-content: space-between; align-items: center; padding-right: 5px;">
            <div style="flex: 1;">
              <strong class="item-name">{{ item.name }}</strong>
              <span class="item-size">{{ item.size }}</span>
              <span class="item-qty">Qty: {{ item.quantity }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
              <strong class="item-total">LKR {{ formatNumber(item.price * item.quantity) }}</strong>
              <button type="button" @click="removeItem(idx)" title="Remove Item" style="background: none; border: none; cursor: pointer; padding: 4px; color: #ef4444; display: flex; align-items: center; justify-content: center; border-radius: 4px; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fee2e2'" onmouseout="this.style.backgroundColor='transparent'">
                <i class="fa-solid fa-trash-can"></i>
              </button>
            </div>
          </div>
        </div>

        <hr class="divider" />

        <div class="totals-block">
          <div class="row">
            <span>Stitched Items</span>
            <span>{{ totalQty }}</span>
          </div>
          <div class="row">
            <span>Delivery Fee</span>
            <span class="font-bold">
              <span v-if="deliveryFee === 0" class="text-sm text-gray-500 font-normal mr-2 text-muted">(Select district)</span>
              LKR {{ formatNumber(deliveryFee) }}
            </span>
          </div>
          <hr class="divider" />
          <div class="row grand-row total-highlight-box">
            <span>Total Payable</span>
            <span class="gold-gradient-text">LKR {{ formatNumber(grandTotal) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="empty-checkout glass-panel">
      <i class="fa-solid fa-bag-shopping"></i>
      <h3>Checkout is locked.</h3>
      <p>Please add clothing to your shopping cart before attempting checkout.</p>
      <NuxtLink to="/shop" class="btn-premium btn-gold mt-4">Go to Collection</NuxtLink>
    </div>

    <!-- Custom Notification Dialog -->
    <div :class="['modal-overlay', { open: notifyDialog.isOpen }]" @click.self="closeNotify">
      <div class="notify-modal">
        <div class="modal-body text-center py-4">
          <i v-if="notifyDialog.type === 'success'" class="fa-solid fa-circle-check text-success mb-3" style="font-size: 3rem;"></i>
          <i v-else class="fa-solid fa-circle-xmark text-danger mb-3" style="font-size: 3rem;"></i>
          
          <h4 class="luxury-title mb-2">{{ notifyDialog.title }}</h4>
          <p class="mb-4">{{ notifyDialog.message }}</p>
          <div class="flex justify-center">
            <button @click="closeNotify" class="btn-premium btn-gold">Okay</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, inject, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const cart = inject('cart')
const updateCart = inject('updateCart')

const isPlacing = ref(false)
const slipFileName = ref('')
const slipPreviewUrl = ref('')

const notifyDialog = ref({
  isOpen: false,
  type: 'success', 
  title: '',
  message: '',
  onClose: null
})

const openNotify = (type, title, message, onClose = null) => {
  notifyDialog.value = { isOpen: true, type, title, message, onClose }
}

const closeNotify = () => {
  if (notifyDialog.value.onClose) {
    notifyDialog.value.onClose()
  }
  notifyDialog.value.isOpen = false
}

const districts = [
  'Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya',
  'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar',
  'Vavuniya', 'Mullaitivu', 'Batticaloa', 'Ampara', 'Trincomalee',
  'Kurunegala', 'Puttalam', 'Anuradhapura', 'Polonnaruwa', 'Badulla',
  'Moneragala', 'Ratnapura', 'Kegalle'
]

const orderData = ref({
  name: '',
  phone: '',
  email: '',
  address: '',
  district: '',
  postal: '',
  paymentMethod: 'bank_deposit'
})

const totalQty = computed(() => {
  return cart.value.reduce((total, item) => total + item.quantity, 0)
})

const subtotal = computed(() => {
  return cart.value.reduce((total, item) => total + (item.price * item.quantity), 0)
})

const deliveryCharges = ref({})
const bankAccounts = ref([])

const deliveryFee = computed(() => {
  if (!orderData.value.district) return 0
  return deliveryCharges.value[orderData.value.district] ?? 300
})

const grandTotal = computed(() => subtotal.value + deliveryFee.value)

onMounted(async () => {
  try {
    const res = await fetch('https://api-maneesha.posmasters.lk/api/settings/delivery-charges')
    deliveryCharges.value = await res.json()
  } catch (err) {
    console.error('Failed to load delivery charges', err)
  }

  try {
    const resBanks = await fetch('https://api-maneesha.posmasters.lk/api/bank-accounts')
    bankAccounts.value = await resBanks.json()
  } catch (err) {
    console.error('Failed to load bank accounts', err)
  }
})

const removeItem = (index) => {
  const newCart = [...cart.value]
  newCart.splice(index, 1)
  updateCart(newCart)
}

const handleSlipUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    slipFileName.value = file.name
    // Generate preview
    const reader = new FileReader()
    reader.onload = (e) => {
      slipPreviewUrl.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const placeOrder = async () => {
  isPlacing.value = true
  
  const orderId = Math.floor(100000 + Math.random() * 900000)
  const orderIdStr = String(orderId);

  if (orderData.value.paymentMethod === 'payhere') {
    try {
      const formattedAmount = Number(grandTotal.value).toFixed(2);
      const response = await fetch('https://api-maneesha.posmasters.lk/api/payhere/hash', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          order_id: orderIdStr,
          amount: formattedAmount,
          currency: 'LKR'
        })
      });
      
      if (!response.ok) {
        throw new Error("Failed to generate payment hash");
      }
      
      const data = await response.json();
      console.log('Payment Hash Data:', data);

      const nameParts = orderData.value.name.split(' ');
      const fName = nameParts[0] || 'Customer';
      const lName = nameParts.length > 1 ? nameParts.slice(1).join(' ') : 'Name';

      var payment = {
        "sandbox": true,
        "merchant_id": data.merchant_id, 
        "return_url": window.location.origin + "/orders",
        "cancel_url": window.location.origin + "/checkout",
        "notify_url": "https://api-maneesha.posmasters.lk/api/payhere/ipn",
        "order_id": orderIdStr,
        "items": "Maneesha Fashion Clothing",
        "amount": formattedAmount,
        "currency": "LKR",
        "hash": data.hash, 
        "first_name": fName,
        "last_name": lName,
        "email": orderData.value.email || 'test@test.com',
        "phone": orderData.value.phone || '0771234567',
        "address": orderData.value.address || 'Sri Lanka',
        "city": orderData.value.district || 'Colombo',
        "country": "Sri Lanka"
      };

      payhere.onCompleted = function onCompleted(oid) {
        completeOrderLocally(oid);
      };
      payhere.onDismissed = function onDismissed() {
        isPlacing.value = false;
      };
      payhere.onError = function onError(error) {
        isPlacing.value = false;
        openNotify('error', 'Payment Error', error);
      };

      payhere.startPayment(payment);
    } catch (error) {
      isPlacing.value = false;
      openNotify('error', 'Payment Error', error.message);
    }
  } else {
    // For COD and Bank Deposit
    setTimeout(() => {
      completeOrderLocally(orderId);
    }, 2500)
  }
}

const completeOrderLocally = async (orderId) => {
    try {
      const payload = {
        customer_name: orderData.value.name,
        phone: orderData.value.phone,
        email: orderData.value.email || 'customer@example.com',
        address: orderData.value.address,
        district: orderData.value.district,
        postal_code: orderData.value.postal,
        payment_method: orderData.value.paymentMethod,
        total_amount: grandTotal.value,
        bank_slip: slipPreviewUrl.value || null,
        items: cart.value.map(item => ({
          id: item.id,
          name: item.name,
          size: item.size || 'Standard',
          price: item.price,
          quantity: item.quantity,
          image: item.image
        }))
      };

      const response = await fetch('https://api-maneesha.posmasters.lk/api/checkout/order', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      if (!response.ok) {
        const errData = await response.json().catch(() => null);
        throw new Error(errData?.message || 'Failed to save order to backend');
      }

      const responseData = await response.json();
      const actualOrderNumber = responseData.order ? responseData.order.order_number : orderId;

      if (typeof window !== 'undefined') {
        localStorage.setItem('maneesha-customer-phone', orderData.value.phone);
        
        // Save to order history
        const history = JSON.parse(localStorage.getItem('maneesha-order-history') || '[]');
        history.push({
            order_number: actualOrderNumber,
            date: new Date().toISOString()
        });
        localStorage.setItem('maneesha-order-history', JSON.stringify(history));
      }

      updateCart([]);
      isPlacing.value = false;
      openNotify('success', 'Order Confirmed', `Congratulations! Your order #${actualOrderNumber} has been successfully registered.`, () => {
        router.push('/orders');
      });
    } catch (error) {
      console.error(error);
      isPlacing.value = false;
      openNotify('error', 'Order Failed', error.message || 'Error placing order. Please contact support.');
    }
}

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>

<style scoped>
.checkout-header {
  text-align: center;
  margin-bottom: 50px;
}

.checkout-header h1 {
  font-size: 3rem;
  margin-bottom: 10px;
}

.checkout-header p {
  color: var(--text-dark-secondary);
}

body.dark-mode .checkout-header p {
  color: var(--text-light-secondary);
}

.checkout-layout {
  display: grid;
  grid-template-columns: 1.6fr 1fr;
  gap: 40px;
  align-items: start;
}

.checkout-form-panel {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.section-card {
  padding: 30px;
}

.section-title {
  font-size: 1.25rem;
  margin-bottom: 25px;
  border-bottom: 2px solid var(--primary-gold);
  padding-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.section-title i {
  color: var(--primary-gold);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

.span-2 {
  grid-column: span 2;
}

.text-area {
  resize: vertical;
}

.select-input {
  cursor: pointer;
}

/* Payment selection */
.payment-methods-grid {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-bottom: 20px;
}

.pay-method-card {
  border: 1px solid var(--bg-light-border);
  border-radius: var(--radius-md);
  padding: 16px 20px;
  cursor: pointer;
  display: block;
  transition: var(--transition-smooth);
}

body.dark-mode .pay-method-card {
  border: 1px solid var(--bg-dark-border);
}

.pay-method-card:hover {
  border-color: var(--primary-gold);
}

.pay-method-card.active {
  border-color: var(--primary-gold);
  background: rgba(212, 175, 55, 0.04);
  box-shadow: 0 0 0 2px var(--primary-gold);
}

.hidden-radio {
  display: none;
}

.pay-card-content {
  display: flex;
  align-items: center;
  gap: 18px;
}

.pay-icon {
  font-size: 1.8rem;
  color: var(--primary-gold);
}

.pay-card-content h5 {
  font-weight: 700;
  font-size: 0.95rem;
  margin-bottom: 3px;
}

.pay-card-content p {
  font-size: 0.75rem;
  color: var(--text-dark-secondary);
}

body.dark-mode .pay-card-content p {
  color: var(--text-light-secondary);
}

/* Bank deposit panel */
.bank-details-panel {
  background: rgba(212,175,55,0.04);
  border: 1px dashed var(--primary-gold-light);
  padding: 24px;
  border-radius: var(--radius-md);
  margin-top: 20px;
}

.bank-details-panel h5 {
  font-size: 0.95rem;
  font-weight: 700;
  margin-bottom: 15px;
  text-transform: uppercase;
}

.bank-account-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
  font-size: 0.85rem;
}

.file-upload-zone {
  border: 2px dashed var(--bg-light-border);
  background: var(--bg-light);
  border-radius: var(--radius-md);
  padding: 30px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  position: relative;
  transition: var(--transition-smooth);
}

body.dark-mode .file-upload-zone {
  border-color: var(--bg-dark-border);
  background: var(--bg-dark);
}

.file-upload-zone:hover {
  border-color: var(--primary-gold);
}

.file-upload-zone i {
  font-size: 2.2rem;
  color: var(--primary-gold);
}

.file-upload-zone span {
  font-size: 0.85rem;
  font-weight: 600;
}

.file-input-hidden {
  position: absolute;
  inset: 0;
  opacity: 0;
  cursor: pointer;
}

.slip-preview-box {
  margin-top: 15px;
  border-radius: var(--radius-md);
  overflow: hidden;
  max-height: 200px;
  border: 1px solid var(--bg-light-border);
}

body.dark-mode .slip-preview-box {
  border-color: var(--bg-dark-border);
}

.slip-preview-box img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.checkout-submit-btn {
  padding: 16px;
  font-size: 1rem;
}

/* Summary Panel */
.checkout-summary-panel {
  padding: 40px;
}

.summary-title {
  font-size: 1.4rem;
  margin-bottom: 25px;
  border-bottom: 2px solid var(--primary-gold);
  padding-bottom: 10px;
}

.summary-items {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 25px;
}

.summary-item-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  font-size: 0.9rem;
}

.item-name {
  display: block;
  font-size: 0.95rem;
}

.item-size {
  display: block;
  font-size: 0.75rem;
  color: var(--primary-gold-dark);
  font-weight: 600;
  margin-top: 2px;
}

.item-qty {
  display: block;
  font-size: 0.75rem;
  color: var(--text-dark-secondary);
  margin-top: 2px;
}

body.dark-mode .item-qty {
  color: var(--text-light-secondary);
}

.divider {
  border: none;
  height: 1px;
  background: var(--bg-light-border);
  margin: 20px 0;
}

body.dark-mode .divider {
  background: var(--bg-dark-border);
}

.totals-block {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.totals-block .row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  font-size: 0.95rem;
}

.totals-block .row span:first-child {
  color: var(--text-dark-secondary);
}

body.dark-mode .totals-block .row span:first-child {
  color: var(--text-light-secondary);
}

.grand-row {
  font-size: 1.25rem;
  font-weight: 700;
}

.grand-row span:first-child {
  color: var(--text-dark-primary) !important;
}

body.dark-mode .grand-row span:first-child {
  color: var(--text-light-primary) !important;
}

/* Empty Checkout */
.empty-checkout {
  padding: 80px 40px;
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
}

.empty-checkout i {
  font-size: 4rem;
  color: var(--primary-gold);
  margin-bottom: 25px;
}

.payment-methods h3 {
  font-family: 'Cinzel', serif;
  margin-bottom: 20px;
}

/* Modal Styling */
.modal-overlay {
  position: fixed;
  top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(5px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}
.modal-overlay.open {
  opacity: 1;
  visibility: visible;
}
.notify-modal {
  background: var(--bg-light);
  border: 1px solid var(--border-light);
  width: 90%;
  max-width: 450px;
  border-radius: var(--radius-lg);
  padding: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  transform: translateY(20px);
  transition: transform 0.3s ease;
}
body.dark-mode .notify-modal {
  background: var(--bg-dark-card);
  border-color: var(--border-dark);
}
.modal-overlay.open .notify-modal {
  transform: translateY(0);
}
.text-success { color: #10b981; }
.text-danger { color: #ef4444; }
.py-4 { padding-top: 30px; padding-bottom: 30px; }
.mb-2 { margin-bottom: 10px; }
.mb-3 { margin-bottom: 15px; }
.mb-4 { margin-bottom: 25px; }
.flex { display: flex; }
.justify-center { justify-content: center; }

.empty-checkout h3 {
  font-size: 1.4rem;
  margin-bottom: 10px;
}

.empty-checkout p {
  color: var(--text-dark-secondary);
  margin-bottom: 30px;
}

body.dark-mode .empty-checkout p {
  color: var(--text-light-secondary);
}

.mt-4 {
  margin-top: 20px;
}

/* Responsive adjustments */
@media (max-width: 991px) {
  .checkout-layout {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .checkout-form-panel {
    gap: 20px;
  }
  
  .section-card {
    padding: 20px 15px;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
  
  .span-2 {
    grid-column: span 1;
  }
  
  .payment-methods-grid {
    gap: 10px;
  }
  
  .pay-card-content {
    gap: 12px;
  }
}
</style>
