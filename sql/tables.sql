drop table users;

create table users
(
    id          serial      not null,
    username    varchar(32) not null unique,
    password    varchar(60),
    create_date timestamp default current_timestamp,
    token       varchar(120),

    constraint table_name_pk
        primary key (id)
);


