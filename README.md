# Task Management System

Task Management System which is built on 
* Laravel Backend
* Laravel Blade Frontend
* MySql Database

## Installation/ Setup
* https://github.com/amansodhi/Task-Management.git
* cp .env.example .env
* php artisan composer update
* php artisan key:generate
* php artisan migrate

## To Store the permission in database
```python
php artisan migrate

INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'read_task', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES (2, 'create_task', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES (3, 'update_task', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES (4, 'delete_task', NULL, NULL);

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'Read Only', NULL, NULL);
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES (2, 'ALL', NULL, NULL);

INSERT INTO `permissions_role` (`permissions_id`, `role_id`) VALUES (1, 1);
INSERT INTO `permissions_role` (`permissions_id`, `role_id`) VALUES (1, 2);
INSERT INTO `permissions_role` (`permissions_id`, `role_id`) VALUES (2, 2);
INSERT INTO `permissions_role` (`permissions_id`, `role_id`) VALUES (3, 2);
INSERT INTO `permissions_role` (`permissions_id`, `role_id`) VALUES (4, 2);

INSERT INTO `role_user` (`role_id`, `user_id`) VALUES (2, 1);
```

## Modules/ Features
* Login user
* Register User
* Create/Update/Delete Task
* Mark complete  task
* Send notification on Task Creation, Task Updating and Deadline is tomorrow via email using database queue driver
* Implemented CSRF security