2. Complete REST API Endpoint List (/api/v1/)

REST API Prompt

# Role & Context
You are an expert API Architect and Laravel Developer. Build a robust, secure, and versioned RESTful API (`api/v1/`) to power the mobile application for the Restaurant & Hotel Management System. Use Laravel Sanctum for token authentication.

## Core API Endpoints & Modules Needed:
1. **Authentication API:** Endpoints for User Registration, Login, OTP Verification, Social Login, Profile Update, and Push Notification Token Registration (Firebase FCM).
2. **Home & Banners API:** Endpoints fetching active promotional banners, top categories, featured menu items, and available hotel rooms.
3. **Food Ordering & Cart API:** Add/remove cart items, apply discount coupons, place online food orders with delivery coordinates, and track order status history.
4. **Table Reservation API:** Submit table booking requests, view active/past reservations, and cancel bookings.
5. **Hotel Room Booking API:** Search available rooms by date range, calculate pricing dynamically, handle booking checkout payloads, and fetch booking receipts.
6. **[Added Idea] Live Notification & Order Tracking Webhooks / Polls:** Endpoints delivering real-time status changes for food delivery tracking and kitchen preparation updates optimized for mobile consumption.

## Deliverables Needed:
- API Routes (`routes/api.php`) structured under middleware groups (`auth:sanctum`).
- API Resource Transformers (`JsonResource`) for clean, consistent JSON responses.
- Form Requests with precise error handling returning structured JSON validation failures (`422`).

All private endpoints require Authorization: Bearer <token> via Laravel Sanctum.
A. Authentication Module (/api/v1/auth)
Method
Endpoint
Access
Description
Request Payload / Params
POST
/register
Public
Register new mobile/web user
name, email, phone, password, password_confirmation
POST
/login
Public
User login & token generation
email (or phone), password
POST
/logout
Private
Revoke current access token
None (Bearer token required)
GET
/profile
Private
Fetch authenticated user profile
None
PUT
/profile
Private
Update user details & FCM token
name, phone, fcm_token

B. Home, Banners & Categories Module (/api/v1)
Method
Endpoint
Access
Description
Request Payload / Params
GET
/home
Public
Fetch active banners, top categories, & highlights
None
GET
/categories
Public
List all categories (food or room types)
type (query: food or room)
GET
/banners
Public
Fetch promotional slider banners
None

C. Restaurant & Food Ordering Module (/api/v1/food)
Method
Endpoint
Access
Description
Request Payload / Params
GET
/menu
Public
List food items with filters & pagination
category_id, search, sort_by
GET
/menu/{id}
Public
Get single food item details & modifiers
None
POST
/orders
Private
Place new online food order
order_type, items (array of item_id, qty, modifiers), address_id, payment_method
GET
/orders
Private
Fetch logged-in user's food order history
status (query filter)
GET
/orders/{id}
Private
Track live food order details and status
None
PUT
/orders/{id}/cancel
Private
Cancel a pending food order
reason

D. Table Booking Module (/api/v1/tables)
Method
Endpoint
Access
Description
Request Payload / Params
GET
/availability
Public
Check available tables for a slot
date, time_slot, guest_count
POST
/book
Private
Reserve a table
booking_date, time_slot, guest_count, special_requests
GET
/reservations
Private
List user's table booking history
None
DELETE
/reservations/{id}
Private
Cancel a table reservation
None

E. Hotel Room Booking Module (/api/v1/rooms)
Method
Endpoint
Access
Description
Request Payload / Params
GET
/search
Public
Search available rooms by date range
check_in, check_out, guests
GET
/rooms/{id}
Public
Get room details, gallery, & amenities
None
POST
/book
Private
Book a hotel room checkout
room_id, check_in, check_out, total_guests, payment_method
GET
/bookings
Private
Get user's room booking logs & passes
None
PUT
/bookings/{id}/cancel
Private
Cancel hotel booking
None

F. POS & Offline Billing Module (Admin / Staff App Only) (/api/v1/pos)
Method
Endpoint
Access
Description
Request Payload / Params
POST
/bill/generate
Staff/Admin
Create physical/counter POS bill
customer_name, phone, items, payment_mode, discount
GET
/kitchen/live
Staff/Admin
Fetch live active kitchen orders
None
PUT
/kitchen/order-status
Staff/Admin
Update order progress state
order_id, status (preparing, out_for_delivery)