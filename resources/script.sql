INSERT INTO `SOP`.`configs`(`id`, `key`, `value`, `created_at`, `updated_at`) VALUES (67, 'layout_5', '{\"sponsors\":{\"title\":\"Sponsors\",\"status\":1},\"popular_courses\":{\"title\":\"Popular Courses\",\"status\":1},\"search_section\":{\"title\":\"Search Section\",\"status\":1},\"latest_news\":{\"title\":\"Latest News, Courses\",\"status\":1},\"featured_courses\":{\"title\":\"Featured Courses\",\"status\":1},\"faq\":{\"title\":\"Frequently Asked Questions\",\"status\":1},\"course_by_category\":{\"title\":\"Course By Category\",\"status\":1},\"testimonial\":{\"title\":\"Testimonial\",\"status\":1},\"teachers\":{\"title\":\"Teachers\",\"status\":1},\"contact_us\":{\"title\":\"Contact us / Get in Touch\",\"status\":1}}', '2020-08-21 09:04:01', '2020-10-09 13:40:00');
UPDATE `SOP`.`configs` set `value` = 5 where `key` = 'theme_layout';

-- RUN iF NEED
DELETE FROM `admin_menu_items`;
INSERT INTO `admin_menu_items` (`id`, `label`, `link`, `parent`, `sort`, `class`, `menu`, `depth`, `created_at`, `updated_at`)
VALUES
	(1, 'Article & Videos', 'blog', 1, 4, NULL, 1, 0, '2020-08-21 09:04:01', '2020-10-10 08:59:22'),
	(2, 'Courses & Events', '', 2, 0, NULL, 1, 0, '2020-08-21 09:04:01', '2020-10-10 08:58:42'),
	(4, 'Long Term Programs', 'programs', 4, 1, NULL, 1, 0, '2020-08-21 09:04:01', '2020-10-10 08:59:22'),
	(5, 'Contact', 'contact', 5, 3, NULL, 1, 0, '2020-08-21 09:04:01', '2020-10-10 08:59:36'),
	(6, 'About Us', 'about-us', 6, 2, NULL, 1, 0, '2020-08-21 09:04:01', '2020-10-10 08:59:36');

