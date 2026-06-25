<template>
  <div class="static-page-container">
    <div class="static-header glass-panel">
      <h1>Return & Refund Policy</h1>
      <p>Please read our return and refund policy carefully before placing an order.</p>
    </div>
    
    <div class="static-content glass-panel">
      <section>
        <h2>1. Return & Refund Policy</h2>
        <p>We do not accept returns, exchanges, or issue refunds for any purchased items, <strong>except</strong> in the case of a mistake on our part (e.g., sending the wrong item) or if the product or parcel arrives damaged.</p>
      </section>

      <section>
        <h2>2. Verify Before Ordering</h2>
        <p>To ensure complete satisfaction, we strongly advise you to carefully review all product details before confirming your order. Please double-check the <strong>price</strong>, <strong>size</strong>, <strong>color</strong>, and any other relevant specifications, as we do not accept returns for incorrect selections made by the customer.</p>
      </section>

      <section>
        <h2>3. Defective, Damaged, or Incorrect Items</h2>
        <p>If you receive a defective or damaged item, or an item different from what you ordered due to our mistake, please contact our customer support team immediately upon delivery with clear photographic evidence of the damage or mistake. We will review such cases and arrange a return or exchange accordingly.</p>
      </section>
    </div>
  </div>
</template>

<style scoped>
.static-page-container { max-width: 900px; margin: 0 auto; padding: 20px; }
.static-header { padding: 40px; text-align: center; border-radius: var(--radius-lg); margin-bottom: 30px; }
.static-header h1 { font-size: 2.5rem; color: var(--primary-gold); margin-bottom: 10px; }
.static-content { padding: 40px; border-radius: var(--radius-lg); }
.static-content section { margin-bottom: 30px; }
.static-content h2 { font-size: 1.4rem; margin-bottom: 15px; color: var(--text-dark-primary); }
body.dark-mode .static-content h2 { color: var(--text-light-primary); }
.static-content p { color: var(--text-dark-secondary); line-height: 1.7; }
body.dark-mode .static-content p { color: var(--text-light-secondary); }
</style>
