Database Schema Specifications (MySQL / Laravel Migrations)
Core Tables & Relationships Summary
users: id, name, email, phone, password, role (admin, staff, customer, delivery), remember_token, timestamps.
categories: id, name, type (food, room), slug, image, status (active, inactive), timestamps.
items / products: id, category_id, name, description, price, image, type (food, room), stock_qty, is_available, timestamps.
rooms: id, category_id, room_number, floor, price_per_night, max_adults, max_children, status (available, booked, maintenance), timestamps.
inventories: id, item_name, sku, current_stock, unit (kg, pcs, ltr), low_alert_qty, supplier_name, timestamps.
banners: id, title, image_url, target_url, position_type (home_slider, app_popup), status, timestamps.
orders: id, user_id, order_type (dine_in, takeaway, delivery), table_id, total_amount, discount, final_amount, payment_status (paid, unpaid), order_status (pending, preparing, completed, cancelled), timestamps.
order_items: id, order_id, item_id, quantity, price, total_price, modifiers_json, timestamps.
table_bookings: id, user_id, table_number, booking_date, time_slot, guest_count, status (pending, confirmed, cancelled), timestamps.
room_bookings: id, user_id, room_id, check_in_date, check_out_date, total_guests, total_price, payment_status, booking_status (confirmed, checked_in, checked_out, cancelled), timestamps.
transactions: id, order_id (nullable), room_booking_id (nullable), payment_method (cash, upi, stripe, razorpay), transaction_id, amount, status, timestamps.