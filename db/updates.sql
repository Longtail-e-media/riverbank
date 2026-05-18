-- 2026-05-18 [SMS]
INSERT INTO `tbl_users` (`id`, `first_name`, `middle_name`, `last_name`, `contact`, `email`, `optional_email`, `username`, `password`, `accesskey`, `image`, `group_id`, `access_code`, `facebook_uid`, `facebook_accesstoken`, `facebook_tokenexpire`, `status`, `sortorder`, `added_date`)
VALUES (NULL, 'Super', '', 'admin', '', 'support@longtail.info', '', 'superadmin', MD5('LTAp@nel#123'), '', '', '1', '', '', '', current_timestamp(), '1', '2', '2026-05-18');
INSERT INTO `tbl_modules` (`id`, `parent_id`, `name`, `link`, `mode`, `icon_link`, `status`, `sortorder`, `added_date`, `properties`) VALUES (NULL, '0', 'Schema Mgmt', 'schema/list', 'schema', 'icon-list', '1', '4', '2026-05-18', '');
CREATE TABLE `tbl_schemas` (
    `id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `schema_code` text NOT NULL,
    `status` int(1) NOT NULL,
    `sortorder` int(11) NOT NULL,
    `meta_title` varchar(255) NOT NULL,
    `meta_keywords` varchar(255) NOT NULL,
    `meta_description` varchar(255) NOT NULL,
    `added_date` varchar(50) NOT NULL,
    `modified_date` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `tbl_schemas` (`id`, `title`, `schema_code`, `status`, `sortorder`, `meta_title`, `meta_keywords`, `meta_description`, `added_date`, `modified_date`) VALUES
(2, 'Home', '', 1, 5, '', '', '', '2026-05-18 15:14:57', '2026-05-18 15:14:57'),
(3, 'Contact', '', 1, 0, '', '', '', '2026-05-18 15:15:08', '2026-05-18 15:15:08'),
(4, 'Gallery', '', 1, 2, '', '', '', '2026-05-18 15:15:17', '2026-05-18 15:15:17'),
(5, 'Facilities', '', 1, 4, '', '', '', '2026-05-18 15:16:49', '2026-05-18 15:27:42'),
(6, 'Offer', '', 1, 3, '', '', '', '2026-05-18 15:17:04', '2026-05-18 15:17:04'),
(7, 'Blog', '', 1, 1, '', '', '', '2026-05-18 15:17:34', '2026-05-18 15:17:34');
ALTER TABLE `tbl_schemas`ADD PRIMARY KEY (`id`);
ALTER TABLE `tbl_schemas` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
