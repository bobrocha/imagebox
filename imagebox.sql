create table `users` (
	`id` int auto_increment,
	`fname` varchar(64),
	`lname` varchar(64),
	`email` varchar(256),
	`password` varchar(13),
	`salt` varchar(16),
	`signupdate` timestamp,
	`usertype` enum('admin', 'regular'),
	`active` tinyint(4),
	primary key(id)
);

create table photos (
	`id` int auto_increment,
	`title` varchar(256),
	`filename` varchar(256),
	`date` timestamp,
	`user` int,
	primary key(id)
);

create table tagged_photos (
	`id` int auto_increment,
	`photo_id` int,
	`tag_id` int,
	primary key(id),
	foreign key(photo_id) references photos(id) on delete cascade
);

create table tags (
	`id` int auto_increment,
	`tag` varchar(64),
	unique(tag),
	primary key(id)
);