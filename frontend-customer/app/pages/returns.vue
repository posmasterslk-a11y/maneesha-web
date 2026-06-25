<template>
  <div class="static-page-container">
    <div class="static-header glass-panel">
      <h1>Return & Refund Policy</h1>
      <p>Please read our return and refund policy carefully before placing an order.</p>
    </div>
    
    <div class="static-content glass-panel">
      <section>
        <h2>1. No Refunds or Returns</h2>
        <p>We do not accept returns, exchanges, or issue refunds for any purchased items under any circumstances. All sales are final.</p>
      </section>

      <section>
        <h2>2. Verify Before Ordering</h2>
        <p>To ensure complete satisfaction, we strongly advise you to carefully review all product details before confirming your order. Please double-check the <strong>price</strong>, <strong>size</strong>, <strong>color</strong>, and any other relevant specifications.</p>
      </section>

      <section>
        <h2>3. Defective or Incorrect Items</h2>
        <p>In the rare event that you receive a defective item or an item different from what you ordered, please contact our customer support team immediately upon delivery with clear photographic evidence. We will review such cases individually to find a fair resolution.</p>
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
