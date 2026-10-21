# Role & Context
You are an expert Full-Stack Laravel Developer. Build a Role-Based Access Control (RBAC) Admin Panel and Real-Time Order Management Workflow for a combined Restaurant and Hotel Management System using Laravel (latest), Spatie Laravel-Permission package, Tailwind CSS, and Laravel Echo / Pusher (or WebSockets) for real-time updates.

## 1. User Roles & Permission Matrix (Spatie RBAC)
Implement middleware and gates to restrict access to specific dashboard sections based on 6 distinct user roles:
1. **Admin:** Full system access (All CRUD operations, financial reports, user role assignments, settings).
2. **Manager:** Oversees daily operations, can view all reports, manage inventory, approve refunds, and override orders.
3. **Receptionist:** Handles Front Desk operations (Hotel Room check-ins/check-outs, table bookings, guest management, and offline billing/POS).
4. **Kitchen Staff:** Access to the **Kitchen Display System (KDS)** screen to view incoming live food orders, update order preparation status, and mark items ready.
5. **Waiter:** Access to the mobile-friendly staff dashboard/POS to take dine-in table orders, view table status, and mark orders served.
6. **Delivery Boy:** Access to the delivery assignment screen to view assigned takeaway/delivery orders, navigate, and mark orders delivered.

---

## 2. Live Order Lifecycle Workflow (Step-by-Step)
Implement the automated state machine and real-time event broadcasting for food orders:
- **Step 1 (Order Placed):** When a customer places an order via Website or App, the status is set to `pending`. A real-time notification/sound alert is instantly triggered on the **Kitchen Staff KDS dashboard**.
- **Step 2 (Kitchen Preparation):** Kitchen Staff opens their dashboard, views the order details (items, quantity, modifiers/addons), and clicks **"Start Preparing"**. Order status updates to `preparing`.
- **Step 3 (Ready for Pickup / Serving):** Once cooked, the kitchen staff clicks **"Mark Ready"**. 
  - If **Dine-In**: A notification triggers on the **Waiter's** panel to pick up and serve the table. Status updates to `ready_to_serve`.
  - If **Delivery/Takeaway**: A notification triggers on the **Delivery Boy's** panel (or counter). Status updates to `out_for_delivery` or `ready_for_pickup`.
- **Step 4 (Completion):** Waiter or Delivery Boy marks the order as `completed` / `delivered` upon payment or handover.

---

## 3. Deliverables Needed
1. **Migrations & Seeders:** Database setup for roles, permissions, and default users for each of the 6 roles.
2. **Middleware & Gates:** Route protection ensuring Kitchen Staff cannot access billing/reports, and Receptionist cannot access kitchen control, etc.
3. **Live KDS & Dashboard Views:** Blade/Livewire components for:
   - **Kitchen KDS Screen:** Kanban-style columns (Pending, Preparing, Ready) with auto-refresh / WebSockets.
   - **Waiter/Staff Panel:** Quick table order & serving tracker.
   - **Delivery Panel:** Assigned order list with status toggle buttons.
4. **Controllers & Event Listeners:** Laravel Events (`OrderPlaced`, `OrderStatusUpdated`) broadcasting changes to the specific role channels.