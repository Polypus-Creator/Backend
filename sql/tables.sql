create table users
(
    id                serial not null,
    username          text   not null unique,
    password          text,
    create_date       timestamp default current_timestamp,
    token             text,
    security_question text,
    security_answer   text,
    last_login        timestamp default current_timestamp,

    constraint table_name_pk
        primary key (id)
);

create or replace function update_last_login()
    returns trigger as
$$
begin
    if new.token is not null then
        update users
        set last_login = current_timestamp
        where id = new.id;
    end if;
    return null;
end ;
$$ language 'plpgsql';

create trigger login_trigger
    after update
        of token
    on users
    for each row
execute procedure update_last_login();

create table webs
(
    id               serial not null,
    user_id          int    not null
        constraint webs_users_id_fk
            references users
            on update cascade on delete cascade,
    website_name     text,
    description      text,
    category         text,
    primary_colour   text,
    secondary_colour text,
    font             text,
    data             json
);

create table tickets
(
    id          serial
        constraint tickets_pk
            primary key,
    user_id     int
        constraint tickets_users_id_fk
            references users
            on update cascade on delete set null,
    title       text,
    description text,
    resolved    bool      default false,
    urgent      bool      default false,
    created     timestamp default current_timestamp
);

create index token_index
    on users (token);
