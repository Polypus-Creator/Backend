FROM postgres
COPY ./sql/tables.sql /docker-entrypoint-initdb.d/
