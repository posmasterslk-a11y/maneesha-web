<template>
  <div class="static-page-container">
    <div class="static-header glass-panel">
      <h1>Privacy Policy</h1>
      <p>Last updated: June 2026</p>
    </div>
    
    <div class="static-content glass-panel">
      <section>
        <h2>1. Information We Collect</h2>
        <p>At Maneesha Fashion, we collect personal information when you place an order, including your name, email address, phone number, and delivery address. We also collect non-personal data such as browser type and IP address to improve our website experience.</p>
      </section>

      <section>
        <h2>2. How We Use Your Information</h2>
        <p>Your information is used strictly for fulfilling your orders, communicating with you about your purchases, and providing customer support. We may also send you promotional emails if you have opted in to receive them.</p>
      </section>

      <section>
        <h2>3. Data Protection</h2>
        <p>We implement strict security measures to protect your personal data. We do not sell, trade, or rent your personal identification information to others. Your payment details (such as Bank Deposit slips) are processed securely and used only for verifying transactions.</p>
      </section>

      <section>
        <h2>4. Cookies</h2>
        <p>Our website uses "cookies" to enhance your shopping experience, remember your cart items, and save your preferences for future visits. You may choose to set your web browser to refuse cookies, but some parts of the site may not function properly.</p>
      </section>

      <section>
        <h2>5. Your Rights</h2>
        <p>You have the right to access, update, or request deletion of your personal information. If you have any concerns regarding your data, please contact us at info@maneeshafashion.lk.</p>
      </section>
    </div>
  </div>
</template>

<style scoped>
.static-page-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}
.static-header {
  padding: 40px;
  text-align: center;
  border-radius: var(--radius-lg);
  margin-bottom: 30px;
}
.static-header h1 {
  font-size: 2.5rem;
  color: var(--primary-gold);
  margin-bottom: 10px;
}
.static-content {
  padding: 40px;
  border-radius: var(--radius-lg);
}
.static-content section {
  margin-bottom: 30px;
}
.static-content h2 {
  font-size: 1.4rem;
  margin-bottom: 15px;
  color: var(--text-dark-primary);
}
body.dark-mode .static-content h2 {
  color: var(--text-light-primary);
}
.static-content p {
  color: var(--text-dark-secondary);
  line-height: 1.7;
}
body.dark-mode .static-content p {
  color: var(--text-light-secondary);
}
</style>
