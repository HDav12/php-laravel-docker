// server.js
import express from "express";
import { createMollieClient } from "@mollie/api-client";

const app = express();
app.use(express.json());
app.use(express.urlencoded({ extended: false })); // voor webhook

const mollie = createMollieClient({ apiKey: process.env.MOLLIE_API_KEY });

// 1) Start betaling vanaf je frontend
app.post("/api/payments", async (req, res) => {
  const { amount, description, orderId } = req.body; // bv. "10.00", "Bestelling #123"
  try {
    const payment = await mollie.payments.create({
      amount: { value: amount, currency: "EUR" },
      description,
      redirectUrl: `${process.env.BASE_URL}/bedankt`,
      webhookUrl: `${process.env.BASE_URL}/api/mollie-webhook`,
      metadata: { orderId }
    });
    // Sla payment.id op bij je order in je DB
    res.json({ checkoutUrl: payment.getCheckoutUrl() });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: "Betaling aanmaken faalde" });
  }
});

// 2) Webhook: Mollie vertelt je de eindstatus
app.post("/api/mollie-webhook", async (req, res) => {
  try {
    const id = req.body.id; // Mollie stuurt payment id
    const payment = await mollie.payments.get(id);

    // Update je orderstatus op basis van payment status:
    if (payment.isPaid()) {
      // markeer order als betaald
    } else if (payment.isCanceled() || payment.isExpired() || payment.isFailed()) {
      // markeer als mislukt/geannuleerd
    }
    res.sendStatus(200);
  } catch (e) {
    console.error(e);
    res.sendStatus(500);
  }
});

app.listen(3000, () => console.log("Server draait op :3000"));
