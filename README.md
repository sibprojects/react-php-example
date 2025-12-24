# Async RabbitMQ Consumer with ReactPHP example

This project demonstrates an **asynchronous RabbitMQ consumer** implemented in PHP using **ReactPHP**, **Bunny**, and **React HTTP Browser**.

The application consumes messages from a RabbitMQ queue, processes the payload, sends HTTP requests for each item in the message, and acknowledges the message only after all requests are completed successfully.

---

## 🔄 Message Processing Flow

1. A message is received from RabbitMQ
2. Message body is decoded from JSON
3. Each `item` triggers an async HTTP request
4. All HTTP requests are executed in parallel
5. Once all promises resolve:
   - The message is acknowledged
6. If the process is interrupted before completion:
   - The message remains unacknowledged and can be re-queued

---

## 📦 Example Message Structure

```json
{
   "order": {
      "items": [
       {
         "orderItemNumber": "SKU123",
         "orderQuantity": 2
       },
       {
         "orderItemNumber": "SKU456",
         "orderQuantity": 1
       }
     ]
   }
}
