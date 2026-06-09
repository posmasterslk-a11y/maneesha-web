<template>
  <div class="static-page-container">
    <div class="static-header glass-panel">
      <h1>Return & Refund Policy</h1>
      <p>We want you to be completely satisfied with your purchase.</p>
    </div>
    
    <div class="static-content glass-panel">
      <section>
        <h2>1. Returns</h2>
        <p>We accept returns within 7 days of the delivery date. To be eligible for a return, your item must be unused, unwashed, and in the same condition that you received it. It must also be in the original packaging with all tags attached.</p>
      </section>

      <section>
        <h2>2. Non-returnable Items</h2>
        <p>Several types of goods are exempt from being returned, including intimate apparel, customized items, and sale items. Please contact us if you have questions about your specific item.</p>
      </section>

      <section>
        <h2>3. Refunds</h2>
        <p>Once your return is received and inspected, we will notify you of the approval or rejection of your refund. If approved, your refund will be processed, and a credit will automatically be applied to your bank account within 5-7 business days.</p>
      </section>

      <section>
        <h2>4. Exchanges</h2>
        <p>We only replace items if they are defective or damaged. If you need to exchange an item for a different size, please return the original item and place a new order, or contact our customer support for assistance.</p>
      </section>

      <section>
        <h2>5. Shipping Returns</h2>
        <p>You will be responsible for paying for your own shipping costs for returning your item. Shipping costs are non-refundable. If you receive a refund, the cost of return shipping will be deducted from your refund.</p>
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
