<template>
  <div class="static-page-container">
    <div class="static-header glass-panel">
      <h1>Terms & Conditions</h1>
      <p>Please read these terms carefully before using our website.</p>
    </div>
    
    <div class="static-content glass-panel">
      <section>
        <h2>1. General Conditions</h2>
        <p>By accessing and placing an order with Maneesha Fashion, you confirm that you are in agreement with and bound by the terms of service contained herein. These terms apply to the entire website and any email or other type of communication between you and Maneesha Fashion.</p>
      </section>

      <section>
        <h2>2. Products and Pricing</h2>
        <p>All products listed on the website are subject to availability. We reserve the right to modify or discontinue any product at any time without notice. Prices for our products are subject to change without notice. All prices are listed in Sri Lankan Rupees (LKR).</p>
      </section>

      <section>
        <h2>3. Orders and Payments</h2>
        <p>When you place an order, you will receive an order confirmation email. For Bank Deposits, your order will not be processed or delivered until the funds have cleared in our account. Please ensure you upload the correct payment slip.</p>
      </section>

      <section>
        <h2>4. Delivery Information</h2>
        <p>We offer island-wide delivery in Sri Lanka. Delivery times may vary depending on your location and the shipping method selected. Delivery charges are calculated at checkout based on your district.</p>
      </section>

      <section>
        <h2>5. Intellectual Property</h2>
        <p>All content included on this site, such as text, graphics, logos, images, and software, is the property of Maneesha Fashion or its content suppliers and protected by international copyright laws.</p>
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
